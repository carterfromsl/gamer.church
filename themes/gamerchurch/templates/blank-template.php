<?php
/**
 * Template Name: Blank
 * Template Post Type: page
 *
 * @package gamerchurch
 */
?>
<!DOCTYPE html>
<html class="blank" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); // Enqueue styles, scripts, and other header elements ?>
</head>
<body id="top" <?php body_class(); ?>>

<div class="blank-page-content">
    <?php
    // Start the loop to display the content
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content(); // Output the page content
        endwhile;
    endif;
    ?>
</div>

<?php wp_footer(); // Enqueue footer scripts ?>
</body>
</html>
