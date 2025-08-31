<?php
/**
 * Rendu PHP du bloc "fccf/download-list"
 * Appelle le shortcode [fccf_downloads limit="x"] avec attribut dynamique
 * @package FCCF Members
 */

declare(strict_types=1);

use FCCF\Members\FCCF_Downloads;

defined('ABSPATH') || exit;

return function(array $attributes = []): string {
  $limit = isset($attributes['limit']) ? (int) $attributes['limit'] : 50;

  if (!class_exists(FCCF_Downloads::class)) return '';

  $instance = new FCCF_Downloads();
  return $instance->shortcode(['limit' => $limit]);
};
