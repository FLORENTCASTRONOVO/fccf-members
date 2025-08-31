<?php
/**
 * Plugin Name: FCCF Members
 * Plugin URI: https://fccf.fr
 * Description: Gestion avancée des membres, fichiers protégés et téléchargements sécurisés via nonce.
 * Version: 1.0.0
 * Author: Florent Castronovo
 * Author URI: https://fccf.fr
 * Text Domain: fccf-members
 * Domain Path: /languages
 * Requires at least: 6.3
 * Requires PHP: 8.1
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

declare(strict_types=1);

if ( ! defined('ABSPATH') ) exit;

// 🔐 Sécurité & chemins constants
const FCCFM_VERSION = '1.0.0';
define('FCCFM_PATH', plugin_dir_path(__FILE__));
define('FCCFM_URL', plugin_dir_url(__FILE__));

defined('FCCFM_PROTECT_META') || define('FCCFM_PROTECT_META', '_fccf_protected');

// 📂 Chargement des composants
require_once FCCFM_PATH . 'includes/class-fccf-members.php';
require_once FCCFM_PATH . 'includes/class-fccf-admin.php';
require_once FCCFM_PATH . 'includes/class-fccf-downloads.php';
require_once FCCFM_PATH . 'includes/class-fccf-rest.php';
require_once FCCFM_PATH . 'includes/class-fccf-blocks.php';

// 📅 Activation : création rôle et dossier protégé
tregister_activation_hook(__FILE__, ['FCCF_Members', 'activate']);

// 🌐 Chargement traduction
add_action('init', function() {
  load_plugin_textdomain('fccf-members', false, dirname(plugin_basename(__FILE__)) . '/languages');
});
