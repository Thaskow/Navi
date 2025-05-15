## 📥 Installation

### Installation de Python sur Linux :

1. **Ouvrez un terminal**.

2. **Mettre à jour les dépôts** :
   
   ```bash
   sudo apt update
   ```

3. **Installer Python 3** :
   
   ```bash
   sudo apt install python3
   ```

4. **Vérifiez l'installation** :
   
   ```bash
   python3 --version
   ```
   
   Vous devriez voir la version de Python installée.

5. **Installer `pip` (gestionnaire de packages)** :
   
   ```bash
   sudo apt install python3-pip
   ```

6. **Vérifiez `pip`** :
   
   ```bash
   pip3 --version
   ```

### Installation de Python sur Windows :

1. **Téléchargez l'installateur** :
   
   - Allez sur le site officiel de Python : [python.org](https://www.python.org/downloads/)
   - Téléchargez l'installateur pour Windows (le fichier `.exe`).

2. **Lancez l'installateur** :
   
   - Double-cliquez sur le fichier téléchargé.

3. **Options d'installation** :
   
   - Cochez **"Add Python to PATH"** pour ajouter Python au chemin système (facultatif mais recommandé).
   - Cliquez sur **"Install Now"** pour installer Python avec les paramètres par défaut.

4. **Vérifiez l'installation** :
   
   - Ouvrez l'Invite de commandes (cmd).
   - Tapez :
     
     ```cmd
     python --version
     ```
   - Vous devriez voir la version de Python installée.

5. **Installer `pip` (s'il n'est pas installé automatiquement)** :
   
   - En général, `pip` est installé automatiquement avec Python. Vérifiez avec :
     
     ```cmd
     pip --version
     ```

Avec ces étapes, vous devriez être prêt à utiliser Python sur votre système Linux ou Windows.

## 🛠️ Dépendances

Pour l'installation des dépendences rien de plus simple

```cmd
pip install -r requirements.txt
```

*⚠️ Attention d'avoir bien récupéré le fichier 'requirement.txt' au préalable ainsi que d'être dans le bon répertoire ⚠️*

## ▶️ Lancement de vérification

Pour lancer le projet, il suffira de lancer le script 'main.py' à la racine.

```cmd
python main.py
```

*⚠️ Après cette exécution purger le fichier JSON 'switch.json' pour ne pas avoir de soucis par la suite ⚠️*

## ⚙️Mise en place du CRON






