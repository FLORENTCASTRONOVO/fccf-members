<?php
declare(strict_types=1);

namespace FCCF\Members;

use WP_Roles;

defined('ABSPATH') || exit;

/**
 * Classe d'activation du plugin : création rôle membre + dossier protégé avec .htaccess
 */
class FCCF_Members {
  public static function activate(): void {
    // 🏢 Création du rôle membre
    add_role('fccf_member', 'FCCF Member', [
      'read'            => true,
      'fccf_download'   => true,
    ]);

    // 🔐 Création du dossier protégé
    $upload    = wp_upload_dir();
    $protected = trailingslashit($upload['basedir']) . 'protected';

    if (!file_exists($protected)) {
      wp_mkdir_p($protected);
    }

    $htaccess = $protected . '/.htaccess';
    if (!file_exists($htaccess)) {
      file_put_contents($htaccess, "Deny from all\n");
    }
  }
}
