<?php
/**
 * Template Name: Single Game
 * Template Post Type: page
 *
 * @package templeosonline
 */
?>
<!DOCTYPE html>
<html class="blank" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); // Enqueue styles, scripts, and other header elements ?>
</head>

<body id="top" <?php body_class(); ?>><?php wp_body_open(); ?>

<div class="blank-page-content">
	<a class="anchor" href="#meta">?</a>
    <?php display_game_frame(get_the_ID()); ?>
</div>
	<div id="meta" class="blank-comments page-wrap">
		<a class="anchor" href="#top">↑</a>
		<div class="game-title">
			<?php if ( has_post_thumbnail() ) { the_post_thumbnail();} ?>
			<h1 class="blank-title"><?php the_title(); ?></h1>
		</div>
		<div class="blank-meta">
			<?php display_custom_fields(get_the_ID()); ?>
		</div>
		<div class="game-desc">
			<?php
    // Start the loop to display the content
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content(); // Output the page content
        endwhile;
    endif;
    ?>
		</div>
		
		<?php if ( comments_open() || '0' != get_comments_number() ) ?> 
		<h2 class="comments-title">Comment on: <strong><?php the_title(); ?></strong></h2>
				<? comments_template( '', true );
				?>
	</div>
	<div class="blank-footer page-wrap">
		<a class="blank-home" target="_blank" href="/"><img src="/wp-content/uploads/2025/01/church-pixel.gif" alt="GAMER.CHURCH" />GamerChurchOS</a>
	</div>

<?php wp_footer(); // Enqueue footer scripts ?>
</body>
</html>
