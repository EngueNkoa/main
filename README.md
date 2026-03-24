# FashionShop - Guide de déploiement sur Debian EC2

## Structure du projet
```
ecommerce/
├── index.php              # Page d'accueil
├── products.php           # Liste des produits
├── login.php              # Connexion
├── register.php           # Inscription
├── cart.php               # Panier
├── checkout.php           # Commande
├── add_to_cart.php        # Ajouter au panier
├── logout.php             # Déconnexion
├── database.sql           # Schéma de la base de données
├── .htaccess              # Configuration Apache
├── includes/
│   ├── config.php         # Configuration BDD + fonctions
│   ├── header.php         # En-tête public
│   └── footer.php         # Pied de page public
├── assets/
│   └── css/style.css      # Styles CSS
├── uploads/               # Images des produits
└── admin/
    ├── index.php          # Dashboard admin
    ├── products.php       # Gestion produits
    ├── categories.php     # Gestion catégories
    ├── orders.php         # Gestion commandes
    ├── order_detail.php   # Détail commande
    ├── users.php          # Gestion utilisateurs
    ├── header.php         # En-tête admin
    └── footer.php         # Pied de page admin
```

---

## ÉTAPE 1 — Installer les dépendances sur le serveur Debian

```bash
# Mettre à jour le système
sudo apt update && sudo apt upgrade -y

# Installer Apache, PHP, MySQL
sudo apt install -y apache2 php php-mysql php-gd php-mbstring mysql-server unzip

# Activer les modules Apache
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## ÉTAPE 2 — Configurer MySQL

```bash
# Sécuriser MySQL
sudo mysql_secure_installation

# Se connecter à MySQL
sudo mysql -u root -p

# Dans MySQL, créer la base de données et l'utilisateur
CREATE DATABASE ecommerce_db CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'shopuser'@'localhost' IDENTIFIED BY 'MotDePasseSecret123';
GRANT ALL PRIVILEGES ON ecommerce_db.* TO 'shopuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Importer le schéma SQL
sudo mysql -u shopuser -p ecommerce_db < /var/www/html/ecommerce/database.sql
```

---

## ÉTAPE 3 — Uploader les fichiers

### Depuis votre PC Windows, ouvrez CMD :
```cmd
# Copier tous les fichiers sur le serveur
scp -i tech.pem -r C:\chemin\vers\ecommerce admin@16.171.47.104:/var/www/html/
```

---

## ÉTAPE 4 — Configurer les permissions

```bash
# Aller dans le dossier
cd /var/www/html/ecommerce

# Donner les bonnes permissions
sudo chown -R www-data:www-data /var/www/html/ecommerce
sudo chmod -R 755 /var/www/html/ecommerce
sudo chmod -R 777 /var/www/html/ecommerce/uploads
```

---

## ÉTAPE 5 — Modifier la configuration BDD

```bash
sudo nano /var/www/html/ecommerce/includes/config.php
```

Modifiez ces lignes :
```php
define('DB_USER', 'shopuser');         // votre utilisateur MySQL
define('DB_PASS', 'MotDePasseSecret123');  // votre mot de passe MySQL
define('DB_NAME', 'ecommerce_db');
```

---

## ÉTAPE 6 — Configurer Apache

```bash
sudo nano /etc/apache2/sites-available/ecommerce.conf
```

Coller ceci :
```apache
<VirtualHost *:80>
    ServerName 16.171.47.104
    DocumentRoot /var/www/html/ecommerce

    <Directory /var/www/html/ecommerce>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/ecommerce_error.log
    CustomLog ${APACHE_LOG_DIR}/ecommerce_access.log combined
</VirtualHost>
```

```bash
# Activer le site
sudo a2ensite ecommerce.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

---

## ÉTAPE 7 — Ouvrir le port 80 dans AWS

Dans **AWS Console → EC2 → Groupe de sécurité → Règles entrantes** :
- Type : **HTTP** | Port : **80** | Source : **0.0.0.0/0**

---

## ÉTAPE 8 — Tester le site

Ouvrez votre navigateur :
```
http://16.171.47.104
```

### Connexion admin :
- **Email :** admin@shop.com
- **Mot de passe :** password

---

## Résolution des problèmes courants

| Problème | Solution |
|----------|----------|
| Page blanche | `sudo tail -f /var/log/apache2/error.log` |
| Erreur BDD | Vérifier config.php et les credentials MySQL |
| Images non affichées | `sudo chmod 777 uploads/` |
| 403 Forbidden | `sudo a2enmod rewrite && sudo systemctl restart apache2` |
