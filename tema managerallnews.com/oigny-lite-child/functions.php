<?php
/**
 * Child theme helpers for entity-based category profiles.
 */

if (!defined('ABSPATH')) {
	exit;
}

function managerallnews_register_entity_term_meta() {
	$auth_callback = static function () {
		return current_user_can('manage_categories');
	};

	$meta_fields = array(
		'entity_type' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'technical_context' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'default_article_image_url' => array(
			'type' => 'string',
			'sanitize_callback' => 'esc_url_raw',
		),
		'related_entities' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'profile_sources' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'profile_short_description' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'profile_body_html' => array(
			'type' => 'string',
			'sanitize_callback' => 'wp_kses_post',
		),
		'profile_image_url' => array(
			'type' => 'string',
			'sanitize_callback' => 'esc_url_raw',
		),
		'profile_gallery_urls' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
		),
		'bio_last_sync' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'bio_sources_hash' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'profile_sync_mode' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'profile_image_alt' => array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	foreach ($meta_fields as $meta_key => $config) {
		register_term_meta(
			'category',
			$meta_key,
			array(
				'type' => $config['type'],
				'single' => true,
				'show_in_rest' => true,
				'sanitize_callback' => $config['sanitize_callback'],
				'auth_callback' => $auth_callback,
			)
		);
	}
}
add_action('init', 'managerallnews_register_entity_term_meta');

function managerallnews_get_term_meta_string($term_id, $meta_key) {
	$value = get_term_meta($term_id, $meta_key, true);
	return is_string($value) ? trim($value) : '';
}

function managerallnews_get_related_entity_links($raw_related_entities) {
	if (!$raw_related_entities) {
		return array();
	}

	$entities = array_filter(array_map('trim', explode('|', $raw_related_entities)));
	$links = array();

	foreach ($entities as $entity_name) {
		$term = get_term_by('name', $entity_name, 'category');
		$links[] = array(
			'label' => $entity_name,
			'url' => $term ? get_term_link($term) : '',
		);
	}

	return $links;
}

function managerallnews_render_gallery($gallery_urls) {
	if (!$gallery_urls) {
		return '';
	}

	$urls = array_filter(array_map('trim', preg_split('/[\r\n|]+/', $gallery_urls)));
	if (empty($urls)) {
		return '';
	}

	ob_start();
	?>
	<div class="rm-entity-gallery">
		<?php foreach ($urls as $url) : ?>
			<figure class="rm-entity-gallery__item">
				<img src="<?php echo esc_url($url); ?>" alt="" loading="lazy" />
			</figure>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

function managerallnews_render_entity_profile_shortcode($atts = array()) {
	$atts = shortcode_atts(
		array(
			'term_id' => 0,
		),
		$atts,
		'rm_entity_profile'
	);

	$term = null;
	if (!empty($atts['term_id'])) {
		$term = get_term((int) $atts['term_id'], 'category');
	} elseif (is_category()) {
		$term = get_queried_object();
	}

	if (!$term || is_wp_error($term) || empty($term->term_id)) {
		return '';
	}

	$term_id = (int) $term->term_id;
	$entity_type = managerallnews_get_term_meta_string($term_id, 'entity_type');
	$technical_context = managerallnews_get_term_meta_string($term_id, 'technical_context');
	$short_description = managerallnews_get_term_meta_string($term_id, 'profile_short_description');
	$body_html = managerallnews_get_term_meta_string($term_id, 'profile_body_html');
	$image_url = managerallnews_get_term_meta_string($term_id, 'profile_image_url');
	$default_image_url = managerallnews_get_term_meta_string($term_id, 'default_article_image_url');
	$gallery_urls = managerallnews_get_term_meta_string($term_id, 'profile_gallery_urls');
	$related_entities_raw = managerallnews_get_term_meta_string($term_id, 'related_entities');
	$bio_last_sync = managerallnews_get_term_meta_string($term_id, 'bio_last_sync');
	$image_alt = managerallnews_get_term_meta_string($term_id, 'profile_image_alt');

	if (!$image_url) {
		$image_url = $default_image_url;
	}

	if (!$short_description && !empty($term->description)) {
		$short_description = wp_strip_all_tags($term->description);
	}

	if (!$body_html && !empty($term->description)) {
		$body_html = wpautop(wp_kses_post($term->description));
	}

	$related_entities = managerallnews_get_related_entity_links($related_entities_raw);
	$tool_url = apply_filters('managerallnews_tool_url', home_url('/check-reputation/'));
	$bio_last_sync_label = '';
	if ($bio_last_sync) {
		$bio_last_sync_timestamp = strtotime($bio_last_sync);
		if ($bio_last_sync_timestamp) {
			$bio_last_sync_label = wp_date('d/m/Y', $bio_last_sync_timestamp);
		}
	}
	$tool_label = $entity_type === 'Azienda'
		? sprintf('Analizza la reputazione del brand %s', $term->name)
		: sprintf('Analizza la reputazione di %s', $term->name);

	ob_start();
	?>
	<section class="rm-entity-profile">
		<div class="rm-entity-profile__hero">
			<div class="rm-entity-profile__copy">
				<?php if ($entity_type) : ?>
					<p class="rm-entity-profile__eyebrow"><?php echo esc_html($entity_type); ?> Profile</p>
				<?php endif; ?>

				<?php if ($short_description) : ?>
					<p class="rm-entity-profile__summary"><?php echo esc_html($short_description); ?></p>
				<?php endif; ?>

				<div class="rm-entity-profile__meta">
					<?php if ($technical_context) : ?>
						<span class="rm-entity-profile__chip"><?php echo esc_html($technical_context); ?></span>
					<?php endif; ?>
					<?php if ($bio_last_sync_label) : ?>
						<span class="rm-entity-profile__chip"><?php echo esc_html('Ultimo update: ' . $bio_last_sync_label); ?></span>
					<?php endif; ?>
				</div>

				<div class="rm-entity-profile__actions">
					<a class="rm-entity-profile__button" href="<?php echo esc_url($tool_url); ?>"><?php echo esc_html($tool_label); ?></a>
				</div>
			</div>

			<?php if ($image_url) : ?>
				<div class="rm-entity-profile__media">
					<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ? $image_alt : $term->name); ?>" loading="lazy" />
				</div>
			<?php endif; ?>
		</div>

		<?php if ($body_html) : ?>
			<div class="rm-entity-profile__body">
				<?php echo wp_kses_post($body_html); ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($related_entities)) : ?>
			<div class="rm-entity-profile__related">
				<h2>Entità correlate</h2>
				<div class="rm-entity-profile__related-list">
					<?php foreach ($related_entities as $related_entity) : ?>
						<?php if (!empty($related_entity['url']) && !is_wp_error($related_entity['url'])) : ?>
							<a class="rm-entity-profile__related-pill" href="<?php echo esc_url($related_entity['url']); ?>"><?php echo esc_html($related_entity['label']); ?></a>
						<?php else : ?>
							<span class="rm-entity-profile__related-pill"><?php echo esc_html($related_entity['label']); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php echo managerallnews_render_gallery($gallery_urls); ?>

		<div class="rm-entity-profile__tool-cta">
			<div>
				<p class="rm-entity-profile__tool-label">Tool reputazionale integrato</p>
				<h2>Valuta segnali reputazionali e contenuti collegati a <?php echo esc_html($term->name); ?></h2>
			</div>
			<a class="rm-entity-profile__button rm-entity-profile__button--secondary" href="<?php echo esc_url($tool_url); ?>">Apri il tool</a>
		</div>
	</section>
	<?php

	return ob_get_clean();
}
add_shortcode('rm_entity_profile', 'managerallnews_render_entity_profile_shortcode');
