=== FCCF Members ===
Contributors: florentcastronovo  
Tags: membres, téléchargement, protégé, bloc Gutenberg, sécurité  
Requires at least: 6.3  
Tested up to: 6.6  
Requires PHP: 8.1  
Stable tag: 1.0.0  
License: GPLv3  
License URI: https://www.gnu.org/licenses/gpl-3.0.html  

Téléchargements protégés et réservés aux membres avec bloc Gutenberg et sécurité avancée.

== Description ==

FCCF Members permet de :

* Créer un rôle `fccf_member` avec la capabilité `fccf_download`
* Marquer un fichier média comme "protégé"
* Gérer le téléchargement uniquement via lien sécurisé (nonce)
* Empêcher l'accès direct via `.htaccess`
* Afficher les fichiers via un shortcode `[fccf_downloads]` ou un bloc Gutenberg
* Exposer un endpoint REST `/wp-json/fccf/v1/downloads`

== Installation ==

1. Téléversez le dossier du plugin dans `wp-content/plugins/`
2. Activez-le via le menu Extensions de WordPress
3. Allez dans Réglages > Médias pour définir le dossier protégé (par défaut : `protected`)
4. Cochez "protégé" sur un média pour le rendre privé
5. Utilisez le bloc ou le shortcode `[fccf_downloads]` dans une page (ex : Espace Membres)

== FAQ ==

= Les fichiers sont-ils vraiment protégés ? =  
Oui. L'accès est sécurisé via nonce, rôle utilisateur et blocage serveur avec `.htaccess`.

= Peut-on personnaliser le bloc Gutenberg ? =  
Oui, le bloc est dynamique (render_callback PHP) avec réglage de la limite de fichiers.

== Screenshots ==

1. Bloc Gutenberg “Liste de téléchargements”
2. Metabox de protection sur un fichier média
3. Réglage du dossier protégé dans Médias

== Changelog ==

= 1.0.0 =
* Version initiale : rôle, shortcode, bloc Gutenberg, protection serveur, REST API

== Upgrade Notice ==

Version stable de départ compatible WP 6.3+, PHP 8.1+, WCAG 2.2, Gutenberg dynamique.
