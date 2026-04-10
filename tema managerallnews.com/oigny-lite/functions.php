<?php
/**
 * Theme Functions
 *
 * @author Jegstudio
 * @package oigny-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

defined( 'OIGNY_LITE_VERSION' ) || define( 'OIGNY_LITE_VERSION', '1.0.5' );
defined( 'OIGNY_LITE_DIR' ) || define( 'OIGNY_LITE_DIR', trailingslashit( get_template_directory() ) );
defined( 'OIGNY_LITE_URI' ) || define( 'OIGNY_LITE_URI', trailingslashit( get_template_directory_uri() ) );

require get_parent_theme_file_path( 'inc/autoload.php' );

Oigny_Lite\Init::instance();


function get_best_matching_post_image($post_id) {
    $tags = wp_get_post_tags($post_id, array('fields' => 'ids'));

    if (!empty($tags)) {
        // Ottieni tutti i post con almeno un tag in comune
        $args = array(
            'tag__in'       => $tags,
            'post__not_in'  => array($post_id),
            'posts_per_page'=> -1, // Ottieni tutti i post corrispondenti
            'meta_query'    => array(
                array(
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ),
            ),
        );
        $matching_posts = get_posts($args);

        if (!empty($matching_posts)) {
            $best_match = null;
            $max_common_tags = 0;

            // Trova il post con il maggior numero di tag in comune
            foreach ($matching_posts as $post) {
                $post_tags = wp_get_post_tags($post->ID, array('fields' => 'ids'));
                $common_tags = array_intersect($tags, $post_tags);
                $common_tags_count = count($common_tags);

                if ($common_tags_count > $max_common_tags) {
                    $max_common_tags = $common_tags_count;
                    $best_match = $post;
                }
            }

            if ($best_match) {
                return get_the_post_thumbnail_url($best_match->ID);
            }
        }
    }

    // Se nessun post ha tag in comune, usa un'immagine di fallback
        return get_template_directory_uri() . '/images/ai.webp';
}



add_action('init', function() {
    remove_filter('the_content', 'set_default_featured_image');
});


function set_default_featured_image($content) {
    if (is_single() && !has_post_thumbnail()) {
        $image_url = get_best_matching_post_image(get_the_ID());
        if ($image_url) {
            $content = '<img src="' . esc_url($image_url) . '" alt="AI">' . $content;
        }
    }
    return $content;
}
add_filter('the_content', 'set_default_featured_image');








function custom_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Se il post non ha un'immagine in evidenza, $html sarà vuoto
    if (empty($html)) {
        $image_url = get_best_matching_post_image($post_id);
        if ($image_url) {
            // Qui puoi personalizzare l'HTML e le dimensioni in base alle tue esigenze
            $html = '<img src="' . esc_url($image_url) . '" alt="" />';
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'custom_post_thumbnail_html', 10, 5);


/**
 * getPostCategories(postId, token)
 * - Recuopera le categorie di un post

function getPostCategories(postId, token) {
    Logger.log('Inizio getPostCategories for post ' + postId);
    var url = siteUrl + '/wp-json/wp/v2/posts/' + postId;
    var options = {
        method: 'GET',
        contentType: 'application/json',
        headers: { 'Authorization': 'Bearer ' + token },
        muteHttpExceptions: true
    };

    try {
        var response = fetchWithRetry(url, options, 3, 2000);
        if (response.getResponseCode() === 200) {
            var postData = JSON.parse(response.getContentText());
            Logger.log('✅ getPostCategories successful: ' + JSON.stringify(postData.categories));
            return postData.categories;
        } else {
            Logger.log('❌ Errore getPostCategories: ' + response.getContentText());
            return null;
        }
    } catch (err) {
        Logger.log('❌ Errore getPostCategories: ' + err);
        return null;
    }
}
 */