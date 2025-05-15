## **1. 📄 Fichier `main.py`**

### **1.1. Description**

Le fichier `main.py` est le point d'entrée principal de l'application. Il gère l'orchestration des tâches liées à la gestion des switchs réseau et au nettoyage des logs obsolètes. Il utilise le protocole SNMP pour vérifier les ports des switchs et charge les configurations à partir de fichiers JSON.

### **1.2. Fonction `main()`**

La fonction principale initialise la configuration, nettoie les logs plus anciens que la période configurée, et vérifie les ports des switchs.

```python
def main():
    sleepTime = 600
    switchs_json = LoadJson('data/json/switchs.json')
    switchs_data = switchs_json.load()

    sleepTime = switchs_data["timeLog"] * 60

    date = datetime.now().replace(day=datetime.now().day - int(switchs_data["keepLog"]))
    date = date.strftime("%Y-%m-%d %H:%M:%S")

    delete_old_logs(date)
    verify_switchs_ports()
```

- **Utilité** : Gère l'automatisation des tâches en fonction des configurations JSON.

### **1.3. Fonction `delete_old_logs()`**

Supprime les anciens logs des switchs en fonction de la date limite spécifiée.

```python
def delete_old_logs(cutoff_date):
    cutoff_date = datetime.strptime(cutoff_date, "%Y-%m-%d %H:%M:%S")
    file_path = 'data/json/switchs.json'
    with open(file_path, 'r') as file:
        data = json.load(file)
    for switch in data.get('switchs', []):
        for port in switch.get('ports', []):
            history = port.get('history', [])
            filtered_history = [entry for entry in history if datetime.strptime(entry['date'], "%Y-%m-%d %H:%M:%S") >= cutoff_date]
            port['history'] = filtered_history
```

- **Utilité** : Élimine les logs obsolètes afin de maintenir des données à jour.

### **1.5. Fonction `verify_switchs_ports()`**

Cette fonction est responsable de la vérification des ports des switchs en utilisant le protocole SNMP. Elle effectue une boucle sur tous les switchs définis dans le fichier JSON et utilise les informations SNMP pour vérifier l'état des ports.

```python
def verify_switchs_ports():
    switchs_json = LoadJson('data/json/switchs.json')
    switchs_data = switchs_json.load()

    for switch in switchs_data["switchs"]:
        switch['ports'] = []
        if switch['community'] != "":
            macs, ports = get_addrs_mac(switch['ip'], switch['community'])
            for idx, port in enumerate(ports):
                mac = macs[idx]
                host = get_mac(mac)
                switch['ports'].append({"port": port, "mac": mac, "host": host, "status": "Connected", "history": []})
        else:
            switch['ports'].append({"port": "unknown", "mac": "unknown", "host": "unknown", "status": "Disconnected", "history": []})

    switchs_json.update(switchs_data)
```

- **Utilité** : Cette fonction parcourt chaque switch défini dans `switchs.json`, récupère les informations sur les adresses MAC et les ports via SNMP, et met à jour l'état des ports pour chaque switch. Si un switch n'a pas de communauté SNMP, il est marqué comme "Disconnected".

### **1.6. Fonction `get_addrs_mac()`**

Cette fonction est définie dans le fichier `snmp/oid.py` et est appelée dans `verify_switchs_ports()` pour récupérer les adresses MAC et les ports associés via le protocole SNMP.

---

### **1.7. Fonction `load_json_and_sleep()`**

Bien que cette fonction ne soit pas définie dans les extraits précédents, elle pourrait être utile pour effectuer une boucle continue dans l'application, avec des pauses entre chaque cycle.

```python
def load_json_and_sleep():
    switchs_json = LoadJson('data/json/switchs.json')
    switchs_data = switchs_json.load()
    sleepTime = switchs_data["timeLog"] * 60
    time.sleep(sleepTime)
```

- **Utilité** : Cette fonction charge les données JSON et attend pendant la durée spécifiée avant de recommencer une nouvelle boucle d'exécution.

### **1.8. Boucle Principale**

L'application pourrait inclure une boucle principale qui exécute continuellement les fonctions en les espaçant de pauses définies par le fichier JSON.

```python
while True:
    main()
    load_json_and_sleep()
```

- **Utilité** : Assure que l'application fonctionne en continu, en exécutant les tâches principales, puis en attendant avant de répéter le cycle.

---

## **2. 📄 Fichier `db/connection.py`**

### **2.1. Description**

Ce fichier gère la connexion à une base de données SQL Server à l'aide de `pyodbc`.

### **2.2. Classe `KasperskyDB`**

Cette classe initialise la connexion à la base de données **KAV** et contient une méthode pour établir la connexion.

```python
class KasperskyDB:
    def __init__(self):
        self.server = "VM-KASPERSKY\\KAV_CS_ADMIN_KIT"
        self.database = "KAV"
        self.username = "KSAdminSQL"
        self.password = "KS@dmin!2022"

    def connect(self):
        conn_str = (f'DRIVER={{SQL Server Native Client 11.0}};'
                    f'SERVER={self.server};'
                    f'DATABASE={self.database};'
                    f'UID={self.username};'
                    f'PWD={self.password}')
        try:
            conn = pyodbc.connect(conn_str)
            return conn.cursor()
        except pyodbc.Error as e:
            print(f"Connection failed: {e}")
```

- **Utilité** : Établit la connexion à la base de données SQL Server pour récupérer des informations sur les hôtes et autres données liées aux switchs.

---

## **3. 📄 Fichier `db/models.py`**

### **3.1. Description**

Ce fichier contient des fonctions qui interagissent avec la base de données via la classe **KasperskyDB**.

### **3.2. Fonction `get_mac()`**

Récupère le nom d'hôte associé à une adresse MAC dans la base de données.

```python
def get_mac(mac):
    db = KasperskyDB()
    cnxn = db.connect()
    cnxn.execute("SELECT h.strWinHostName FROM hosts h INNER JOIN dbo.hst_mac m ON h.nId = m.nIdHost WHERE m.strMac = ?", mac)
    row = cnxn.fetchone()
    cnxn.close()
    return row[0] if row else None
```

- **Utilité** : Permet de retrouver le nom de l'hôte associé à une adresse MAC spécifique.

---

## **4. 📄 Fichier `snmp/oid.py`**

### **4.1. Description**

Ce fichier gère l'extraction des adresses MAC et des ports associés via le protocole SNMP.

### **4.2. Fonction `get_addrs_mac()`**

Récupère les adresses MAC et les ports associés via SNMP.

```python
def get_addrs_mac(target, community):
    oidMac = '1.3.6.1.2.1.17.4.3.1.1'
    oidPort = '1.3.6.1.2.1.17.4.3.1.2'
    snmp_client = SNMPClient(target, community)
    responsePort = snmp_client.snmp_walk(oidPort)

    value_counts = {}
    for item in responsePort:
        value = item[1]
        if value not in value_counts:
            value_counts[value] = {"count": 1, "oid": [item[0]]}
        else:
            value_counts[value]["count"] += 1
            value_counts[value]["oid"].append(item[0])
```

- **Utilité** : Facilite la récupération des informations réseau via SNMP.

---

## **5. 📄 Fichier `snmp/snmp_client.py`**

### **5.1. Description**

Ce fichier définit la classe **SNMPClient** pour interagir avec des périphériques via SNMP.

### **5.2. Classe `SNMPClient`**

La classe permet de réaliser des requêtes SNMP pour récupérer des informations réseau.

```python
class SNMPClient:
    def __init__(self, target, community):
        self.target = target
        self.community = community

    def snmp_walk(self, oid):
        iterator = nextCmd(SnmpEngine(),
                           CommunityData(self.community),
                           UdpTransportTarget((self.target, 161)),
                           ContextData(),
                           ObjectType(ObjectIdentity(oid)),
                           lexicographicMode=False)
        response = []
        for errorIndication, errorStatus, errorIndex, varBinds in iterator:
            if errorIndication:
                print(f'Error: {errorIndication}')
                break
            elif errorStatus:
                print(f'Error: {errorStatus.prettyPrint()}')
                break
            else:
                for varBind in varBinds:
                    response.append([varBind[0].prettyPrint(), varBind[1].prettyPrint()])
        return response
```

- **Utilité** : Facilite l'interaction avec des périphériques réseau pour collecter des informations via SNMP.

---

## **6. 📄 Fichier `utils/json_utils.py`**

### **6.1. Description**

Ce fichier contient des fonctions utilitaires pour la manipulation des fichiers JSON.

### **6.2. Classe `LoadJson`**

Permet de charger et d'enregistrer des fichiers JSON.

```python
class LoadJson:
    def __init__(self, path):
        self.path = path

    def load(self):
        with open(self.path, 'r') as file:
            return json.load(file)

    def update(self, data):
        with open(self.path, 'w') as file:
            json.dump(data, file, indent=4)
```

- **Utilité** : Gère le chargement et la mise à jour des fichiers de configuration JSON.

---

## **7. 📄 Fichier `utils/logger.py`**

### **7.1. Description**

Ce fichier semble être destiné à la gestion des logs, mais son contenu est vide dans la version actuelle.

---

### **Conclusion**

Cette documentation présente en détail les principaux fichiers Python du projet **NAVI-Python**. Les fichiers gèrent les connexions aux bases de données, les interactions SNMP avec des périphériques réseau, ainsi que la manipulation des fichiers JSON pour les configurations et les logs.
