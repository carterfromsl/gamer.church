<?php
/**
 * Template Name: Default
 * Template Post Type: page
 *
 * @package templeosonline
 */
?>

<?php get_header(); ?>

	<div class="page-loop">
		
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<article class="post-wrap">
				
				<h1 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
				
				<div class="post">
					
					<?php the_content( 'Continue...' ); ?>
							
					<?php wp_link_pages(); ?>
				</div>
						
			</article>

		<?php endwhile; ?>
				
	</div>

		<?php else : ?>
				
			<article class="post error">
				<h1 class="404">404: NULL ADDRESS</h1>
			</article>

		<?php endif; ?>

<?php get_footer(); ?>
