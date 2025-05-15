## **1. 📄 Fichier `index.php`**

### **1.1. Description**

Le fichier `index.php` est le point d'entrée principal de l'application **NAVI-Web**. Il assemble les différentes parties de la page d'accueil en important des composants tels que la barre de navigation et le pied de page.

### **1.2. Importation des Composants**

Ce fichier inclut plusieurs composants pour créer la structure de la page, notamment la barre de navigation (`navbar.php`) et le pied de page (`footer.php`).

```php
<?php
include 'templates/component/navbar/navbar.php';
include 'templates/component/footer/footer.php';
?>
```

- **Utilité** : Définit la structure de la page d'accueil en incluant les éléments de navigation et de pied de page.

---

## **2. 📄 Fichier `settings.php`**

### **2.1. Description**

Le fichier `settings.php` est utilisé pour gérer les paramètres de l'application, tels que la configuration des switchs et la gestion des alertes.

### **2.2. Chargement et Modification des Paramètres**

Les paramètres sont chargés à partir d'un fichier JSON (`switchs.json`) et peuvent être modifiés via un formulaire. Les modifications sont ensuite enregistrées dans le fichier JSON.

```php
$data = file_get_contents('data/json/switchs.json');
$switchs = json_decode($data, true);
```

- **Utilité** : Permet de modifier les configurations des switchs via une interface utilisateur et de mettre à jour ces informations dans un fichier JSON.

---

## **3. 📄 Fichier `switch_detail.php`**

### **3.1. Description**

Le fichier `switch_detail.php` affiche les détails d'un switch spécifique. Il récupère les données des switchs depuis un fichier JSON et les présente dans une interface utilisateur.

### **3.2. Affichage des Détails des Switchs**

Les informations sur les switchs, telles que les ports, les adresses MAC et l'état de connexion, sont chargées depuis le fichier `switchs.json` et affichées dans une table HTML.

```php
$data = file_get_contents('data/json/switchs.json');
$switch = json_decode($data, true);
```

- **Utilité** : Présente les informations détaillées sur les switchs, incluant les ports, les adresses MAC, et l'historique des connexions.

---

## **4. 📄 Fichier `warning.php`**

### **4.1. Description**

Le fichier `warning.php` gère l'affichage et la gestion des alertes associées aux switchs. Les alertes sont stockées dans un fichier JSON et peuvent être affichées, modifiées ou supprimées via l'interface utilisateur.

### **4.2. Chargement et Affichage des Alertes**

Les alertes sont chargées depuis le fichier `warning.json` et affichées dans une interface utilisateur.

```php
$data = file_get_contents('data/json/warning.json');
$warnings = json_decode($data, true);
```

- **Utilité** : Permet d'afficher et de gérer les alertes liées aux switchs, en affichant les messages et les dates d'alerte.

---

## **5. 📄 Fichier `whitelist.php`**

### **5.1. Description**

Le fichier `whitelist.php` gère l'affichage et la gestion des adresses MAC en liste blanche. Ces adresses sont exemptées de certaines actions, comme les alertes.

### **5.2. Gestion de la Liste Blanche**

Les adresses MAC en liste blanche sont chargées depuis le fichier `white_list.json` et peuvent être modifiées par l'utilisateur.

```php
$data = file_get_contents('data/json/white_list.json');
$whitelist = json_decode($data, true);
```

- **Utilité** : Permet à l'utilisateur d'ajouter ou de supprimer des adresses MAC de la liste blanche.

---

## **6. 📄 Fichiers dans `/templates/component/`**

### **6.1. Structure des Composants**

Le dossier **`/templates/component/`** contient plusieurs sous-dossiers qui organisent les différents composants de l'interface utilisateur, comme les formulaires d'ajout et de suppression d'éléments, la barre de navigation, et le pied de page.

#### **6.1.1. Composant `navbar.php`**

Affiche la barre de navigation de l'application.

```php
<nav>
  <ul>
    <li><a href="index.php">Accueil</a></li>
    <li><a href="settings.php">Paramètres</a></li>
    <li><a href="whitelist.php">Liste blanche</a></li>
  </ul>
</nav>
```

- **Utilité** : Facilite la navigation entre les différentes sections de l'application.

#### **6.1.2. Composant `footer.php`**

Affiche le pied de page commun à toutes les pages de l'application.

```php
<footer>
  <p>© 2024 NAVI-Web</p>
</footer>
```

- **Utilité** : Ajoute un pied de page standardisé à toutes les pages pour une mise en page cohérente.

---

## **7. 📄 Fichiers JSON dans `/data/json/`**

### **7.1. Fichier `switchs.json`**

- Contient la configuration des switchs, y compris les informations sur les ports, les adresses MAC, et les statuts des connexions.

### **7.2. Fichier `warning.json`**

- Stocke les informations sur les alertes générées pour les switchs, incluant les dates et descriptions des événements d'alerte.

### **7.3. Fichier `white_list.json`**

- Contient les adresses MAC qui sont exemptées des alertes ou d'autres actions, permettant une gestion personnalisée des exceptions.

---

### **Conclusion**

Le projet **NAVI-Web** est une application web modulaire bien organisée, avec une architecture basée sur des composants PHP pour la gestion des switchs, des alertes, et des listes blanches. Les informations de configuration et d'état sont stockées dans des fichiers JSON, facilitant la gestion des données dynamiques via une interface utilisateur intuitive.
