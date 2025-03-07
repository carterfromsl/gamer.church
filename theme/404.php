<?php get_header(); ?>
<?php get_template_part( 'header-minimal' ); ?>
<article id="post-0" class="post not-found">
<header class="header">
<?php get_template_part('breadcrumbs'); ?>
<h1 class="entry-title" itemprop="name"><?php esc_html_e( 'Not Found', 'gamerchurch' ); ?></h1>
</header>
<div class="entry-content" itemprop="mainContentOfPage">
<p><?php esc_html_e( 'Error Code 404: Nothing found for the requested page. Try a search instead?', 'gamerchurch' ); ?></p>
<?php get_search_form(); ?>
</div>
</article>
<?php get_footer(); ?>
