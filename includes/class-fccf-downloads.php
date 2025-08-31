<?php
declare(strict_types=1);

namespace FCCF\Members;

defined('ABSPATH') || exit;

use WP_Query;

/**
 * Gestion des téléchargements sécurisés : lien à nonce, shortcode, streaming sans URL directe
 */
class FCCF_Downloads {
  public function __construct() {
    add_action('template_redirect', [$this, 'handle_download']);
    add_shortcode('fccf_downloads', [$this, 'shortcode']);

    add_action('wp_enqueue_scripts', function (): void {
      wp_enqueue_style('fccfm-downloads', FCCFM_URL . 'assets/css/downloads.css', [], FCCFM_VERSION);
      wp_enqueue_script('fccfm-frontend', FCCFM_URL . 'assets/js/downloads-frontend.js', [], FCCFM_VERSION, true);
    });
  }

  protected function protected_dir(): array {
    $upload = wp_upload_dir();
    $sub    = get_option('fccfm_protected_dir', 'protected');
    return [
      trailingslashit($upload['basedir']) . $sub,
      trailingslashit($upload['baseurl'])  . $sub,
    ];
  }

  public function handle_download(): void {
    if (empty($_GET['fccf_download'])) return;

    $att_id = absint($_GET['file'] ?? 0);
    $nonce  = $_GET['nonce'] ?? '';

    if (!is_user_logged_in() || !current_user_can('fccf_download') || !wp_verify_nonce($nonce, 'fccf_dl_' . $att_id)) {
      wp_die(esc_html__('Accès refusé.', 'fccf-members'), '', ['response' => 403]);
    }

    $is_protected = (int) get_post_meta($att_id, FCCFM_PROTECT_META, true);
    if (!$is_protected) {
      wp_die(esc_html__('Fichier non protégé.', 'fccf-members'), '', ['response' => 403]);
    }

    $file = get_attached_file($att_id);
    if (!$file || !file_exists($file)) {
      wp_die(esc_html__('Fichier introuvable.', 'fccf-members'), '', ['response' => 404]);
    }

    nocache_headers();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
  }

  public function list_items(int $limit = 50): string {
    $query = new WP_Query([
      'post_type'      => 'attachment',
      'posts_per_page' => $limit,
      'meta_key'       => FCCFM_PROTECT_META,
      'meta_value'     => 1,
      'post_status'    => 'inherit',
    ]);

    $output = '<ul class="fccfm-downloads">';
    while ($query->have_posts()) {
      $query->the_post();
      $id    = get_the_ID();
      $nonce = wp_create_nonce('fccf_dl_' . $id);
      $url   = add_query_arg([
        'fccf_download' => 1,
        'file'          => $id,
        'nonce'         => $nonce,
      ], home_url('/'));

      $output .= '<li><a href="' . esc_url($url) . '">' . esc_html(get_the_title()) . '</a></li>';
    }
    wp_reset_postdata();
    $output .= '</ul>';

    return $output;
  }

  public function shortcode(array $atts): string {
    $atts = shortcode_atts(['limit' => 50], $atts, 'fccf_downloads');

    if (!is_user_logged_in()) {
      return '<p>' . esc_html__('Connectez-vous pour voir les téléchargements.', 'fccf-members') . '</p>';
    }

    if (!current_user_can('fccf_download')) {
      return '<p>' . esc_html__('Vous n'avez pas les droits nécessaires.', 'fccf-members') . '</p>';
    }

    return $this->list_items((int) $atts['limit']);
  }
}

new FCCF_Downloads();