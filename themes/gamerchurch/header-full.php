<div id="wrapper" class="hfeed">
<header id="header" role="banner">
<div id="branding">
	
	<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { ?>
		<div id="site-logo">
			<?php the_custom_logo(); ?>
		</div>
	<?php } ?>
	
	<div class="header-text">
		<div id="site-title" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		<?php
		if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '<h1>'; }
		echo '<a href="' . esc_url( home_url( '/home/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" itemprop="url"><span itemprop="name">' . esc_html( get_bloginfo( 'name' ) ) . '</span></a>';
		if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '</h1>'; }
		?>
		</div>
		<div id="site-description"<?php if ( !is_single() ) { echo ' itemprop="description"'; } ?>><?php bloginfo( 'description' ); ?></div>
		<div id="search"><?php get_search_form(); ?></div>
		</div>
	
	<?php if ( is_active_sidebar( 'header-widget-area' ) ) : ?>
    <div id="header-widget-area" class="widget-area">
        <?php dynamic_sidebar( 'header-widget-area' ); ?>
    </div>
<?php endif; ?>
</div>
	<hr />
<nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
</nav>
</header>
<div id="container">
<main id="content" role="main">
