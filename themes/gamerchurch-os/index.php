<?php get_header(); ?>
	<div class="page-loop">
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
		
		<?php the_content(); ?>

		<?php endwhile; ?>

		<?php else : ?>
				
			<article class="post error">
				<h1 class="404">404: NULL ADDRESS</h1>
				<p>
					<em>// God says: This file is corrupt or not readable.</em>
				</p>
			</article>

		<?php endif; ?>
	</div>

<?php get_footer(); ?>
