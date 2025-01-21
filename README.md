# Guide d'installation : PHP, MySQL, phpMyAdmin et configuration de projet sur Ubuntu avec WSL

## Prérequis
- WSL activé sur Windows.
- Ubuntu installé sur WSL.
- Système mis à jour :
  ```bash
  sudo apt update && sudo apt upgrade -y
  ```

---

## Étape 1 : Installer PHP et ses extensions nécessaires
1. Installer PHP :
   ```bash
   sudo apt install php php-cli php-mbstring php-xml php-curl php-zip unzip -y
   ```
2. Vérifier l'installation de PHP :
   ```bash
   php --version
   ```

---

## Étape 2 : Installer MySQL
1. Installer MySQL :
   ```bash
   sudo apt install mysql-server -y
   ```
2. Sécuriser l'installation de MySQL :
   ```bash
   sudo mysql_secure_installation
   ```
   - Suivre les instructions pour définir un mot de passe root et sécuriser l'installation.

3. Vérifier l'installation de MySQL :
   ```bash
   mysql --version
   ```
4. Tester le statut du serveur MySQL :
   ```bash
   sudo systemctl status mysql
   ```

---

## Étape 3 : Installer phpMyAdmin
1. Installer phpMyAdmin :
   ```bash
   sudo apt install phpmyadmin -y
   ```
2. Sélectionner **Apache** comme serveur web lorsqu'on vous le demande.
3. Configurer la base de données :
   - Fournir le mot de passe MySQL `root` lorsque cela est demandé.

4. Activer la configuration phpMyAdmin :
   ```bash
   sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin
   ```

5. Redémarrer Apache :
   ```bash
   sudo systemctl restart apache2
   ```

6. Accéder à phpMyAdmin :
   - Ouvrir votre navigateur et aller sur `http://localhost/phpmyadmin`.

---

## Étape 4 : Résoudre les problèmes de connexion `root` sur phpMyAdmin
Si vous rencontrez des problèmes pour vous connecter avec l'utilisateur `root` :

### Vérifier la configuration de MySQL pour `root`
1. Se connecter à MySQL :
   ```bash
   sudo mysql -u root
   ```

2. Vérifier le plugin d'authentification pour `root` :
   ```sql
   SELECT user, host, plugin FROM mysql.user;
   ```
   Vous devriez voir quelque chose comme :
   ```
   +------------------+-----------+-----------------------+
   | user             | host      | plugin                |
   +------------------+-----------+-----------------------+
   | root             | localhost | auth_socket           |
   +------------------+-----------+-----------------------+
   ```

3. Modifier l'utilisateur `root` pour utiliser un mot de passe :
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';
   FLUSH PRIVILEGES;
   ```

4. Quitter MySQL :
   ```bash
   exit
   ```

5. Tester la connexion `root` :
   ```bash
   mysql -u root -p
   ```
   Utiliser le mot de passe `root`.

### Mettre à jour la configuration de phpMyAdmin
1. Ouvrir le fichier de configuration de phpMyAdmin :
   ```bash
   sudo nano /etc/phpmyadmin/config.inc.php
   ```

2. Vérifier que les paramètres suivants sont présents :
   ```php
   $cfg['Servers'][$i]['auth_type'] = 'cookie';
   $cfg['Servers'][$i]['AllowNoPassword'] = false;
   ```

3. Enregistrer et quitter le fichier (`Ctrl + O`, puis `Ctrl + X`).

4. Redémarrer Apache :
   ```bash
   sudo systemctl restart apache2
   ```

5. Réessayer la connexion sur `http://localhost/phpmyadmin` avec :
   - **Nom d'utilisateur** : `root`
   - **Mot de passe** : `root`

---

## Étape 5 : Créer un projet PHP
1. Créer un dossier de projet :
   ```bash
   mkdir ~/mon_projet_php && cd ~/mon_projet_php
   ```
2. Créer un fichier PHP simple :
   ```bash
   echo "<?php phpinfo(); ?>" > index.php
   ```
3. Lancer un serveur de développement PHP :
   ```bash
   php -S localhost:8000
   ```
4. Ouvrir votre navigateur et aller sur :
   ```
   http://localhost:8000
   ```

---

## Dépannage
- Si vous rencontrez des problèmes, vérifiez le statut des services :
  ```bash
  sudo systemctl status mysql
  sudo systemctl status apache2
  ```
- Redémarrer les services si nécessaire :
  ```bash
  sudo systemctl restart mysql
  sudo systemctl restart apache2
  ```

