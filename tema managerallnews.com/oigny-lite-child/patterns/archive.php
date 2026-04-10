<?php
/**
 * Title: archive
 * Slug: oigny-lite-child/archive
 * Inserter: no
 */
?>
<!-- wp:template-part {"slug":"header","area":"header"} /-->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/bg-hero-page.webp","dimRatio":0,"className":"oigny-lite-child-margin-top-n100","style":{"spacing":{"padding":{"right":"12vw","left":"12vw"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover oigny-lite-child-margin-top-n100" style="padding-right:12vw;padding-left:12vw"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span><img class="wp-block-cover__image-background " alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/bg-hero-page.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"8vh","bottom":"8vh","left":"6vw","right":"6vw"},"margin":{"top":"200px","bottom":"200px"}},"border":{"radius":"18px"}},"backgroundColor":"black"} -->
<div class="wp-block-columns has-black-background-color has-background" style="border-radius:18px;margin-top:200px;margin-bottom:200px;padding-top:8vh;padding-right:6vw;padding-bottom:8vh;padding-left:6vw"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:query-title {"type":"archive","showPrefix":false,"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","fontSize":"h2"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:columns {"style":{"spacing":{"padding":{"right":"12vw","left":"12vw","top":"0px","bottom":"0px"}}}} -->
<div class="wp-block-columns" style="padding-top:0px;padding-right:12vw;padding-bottom:0px;padding-left:12vw"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:shortcode -->
[rm_entity_profile]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:columns {"style":{"spacing":{"padding":{"right":"12vw","left":"12vw","top":"100px","bottom":"100px"}}}} -->
<div class="wp-block-columns" style="padding-top:100px;padding-right:12vw;padding-bottom:100px;padding-left:12vw"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:query {"queryId":53,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":true,"parents":[],"taxQuery":null},"tagName":"main"} -->
<main class="wp-block-query"><!-- wp:post-template {"style":{"typography":{"fontSize":"14px"},"spacing":{"blockGap":"60px"}},"layout":{"type":"grid","columnCount":2}} -->
<!-- wp:group {"layout":{"inherit":false}} -->
<div class="wp-block-group"><!-- wp:post-featured-image {"style":{"border":{"radius":{"topLeft":"15px","topRight":"15px"}},"spacing":{"margin":{"bottom":"25px"}}}} /-->

<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.25"},"spacing":{"margin":{"bottom":"10px"}}},"fontSize":"post-block"} /-->

<!-- wp:post-date {"style":{"elements":{"link":{"color":{"text":"#c7c7c7ad"}}},"color":{"text":"#c7c7c7ad"}}} /-->

<!-- wp:post-excerpt {"moreText":"Read More ","excerptLength":19,"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"300"},"elements":{"link":{"color":{"text":"var:preset|color|theme-2"},":hover":{"color":{"text":"var:preset|color|theme-5"}}}},"spacing":{"margin":{"top":"15px"}}},"textColor":"theme-3"} /--></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:spacer {"height":"80px"} -->
<div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query-pagination {"paginationArrow":"arrow","showLabel":false,"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"typography":{"fontSize":"14px","textDecoration":"none"}},"textColor":"white","fontFamily":"michroma","layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></main>
<!-- /wp:query --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:tag-cloud /--></div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","area":"footer"} /-->
