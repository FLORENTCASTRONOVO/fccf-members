<?php
declare(strict_types=1);

namespace FCCF\Members;

defined('ABSPATH') || exit;

/**
 * Admin : réglage dossier protégé + metabox pour cocher "protégé" sur un média
 */
class FCCF_Admin {
  public function __construct() {
    add_action('admin_init', [$this, 'register_settings']);
    add_action('add_meta_boxes', [$this, 'add_media_metabox']);
    add_action('save_post_attachment', [$this, 'save_media_metabox']);
  }

  public function register_settings(): void {
    register_setting('media', 'fccfm_protected_dir', [
      'type'              => 'string',
      'sanitize_callback' => 'sanitize_text_field',
      'default'           => 'protected'
    ]);

    add_settings_section('fccfm_sec', __('FCCF Members', 'fccf-members'), '__return_false', 'media');

    add_settings_field('fccfm_protected_dir', __('Dossier protégé (uploads/...)', 'fccf-members'), function () {
      $val = esc_attr(get_option('fccfm_protected_dir', 'protected'));
      echo '<input type="text" name="fccfm_protected_dir" value="' . $val . '" class="regular-text" />';
    }, 'media', 'fccfm_sec');
  }

  public function add_media_metabox(): void {
    add_meta_box(
      'fccfm_protect',
      __('Téléchargement réservé aux membres', 'fccf-members'),
      [$this, 'mb_render'],
      'attachment',
      'side',
      'default'
    );
  }

  public function mb_render($post): void {
    $checked = (int) get_post_meta($post->ID, FCCFM_PROTECT_META, true);
    wp_nonce_field('fccfm_mb', 'fccfm_mb_nonce');
    echo '<label><input type="checkbox" name="' . FCCFM_PROTECT_META . '" value="1" ' . checked($checked, 1, false) . ' /> ' . esc_html__('Protégé', 'fccf-members') . '</label>';
  }

  public function save_media_metabox(int $post_id): void {
    if (!isset($_POST['fccfm_mb_nonce']) || !wp_verify_nonce($_POST['fccfm_mb_nonce'], 'fccfm_mb')) return;
    $val = isset($_POST[FCCFM_PROTECT_META]) ? 1 : 0;
    update_post_meta($post_id, FCCFM_PROTECT_META, $val);
  }
}

new FCCF_Admin();
