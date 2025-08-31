<?php
declare(strict_types=1);

namespace FCCF\Members;

defined('ABSPATH') || exit;

use WP_REST_Request;
use WP_REST_Response;
use WP_Query;

/**
 * Endpoint REST API pour afficher les fichiers protégés si l'utilisateur a le droit
 */
class FCCF_REST {
  public function __construct() {
    add_action('rest_api_init', function (): void {
      register_rest_route('fccf/v1', '/downloads', [
        'methods'             => 'GET',
        'permission_callback' => [$this, 'can_access'],
        'callback'            => [$this, 'endpoint_downloads'],
        'args' => [
          'limit' => [
            'type'    => 'integer',
            'default' => 50,
          ],
        ],
      ]);
    });
  }

  public function can_access(): bool {
    return current_user_can('fccf_download');
  }

  public function endpoint_downloads(WP_REST_Request $req): WP_REST_Response {
    $limit = (int) $req->get_param('limit');

    $query = new WP_Query([
      'post_type'      => 'attachment',
      'posts_per_page' => $limit,
      'meta_key'       => FCCFM_PROTECT_META,
      'meta_value'     => 1,
      'post_status'    => 'inherit',
    ]);

    $items = [];
    while ($query->have_posts()) {
      $query->the_post();
      $id = get_the_ID();
      $items[] = [
        'id'        => $id,
        'title'     => get_the_title(),
        'protected' => (bool) get_post_meta($id, FCCFM_PROTECT_META, true),
      ];
    }
    wp_reset_postdata();

    return rest_ensure_response($items);
  }
}

new FCCF_REST();
