<?php /* Simpler header used for the minimal page template */ ?>
<div id="wrapper" class="hfeed">
<header id="header" class="minimal" role="banner">
<div id="branding">
	
	<div class="header-text">
		<div id="site-title" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
		<?php
		if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '<h1>'; }
		echo '<a href="' . esc_url( home_url( '/home/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" itemprop="url"><span itemprop="name">' . esc_html( get_bloginfo( 'name' ) ) . '</span></a>';
		if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '</h1>'; }
		?>
		</div>
		<div id="site-description"<?php if ( !is_single() ) { echo ' itemprop="description"'; } ?>><?php bloginfo( 'description' ); ?></div>
		</div>
	
	<?php if ( is_active_sidebar( 'header-min-widget-area' ) ) : ?>
    <div id="header-min-widget-area" class="widget-area">
        <?php dynamic_sidebar( 'header-min-widget-area' ); ?>
    </div>
<?php endif; ?>
</div>
	<hr />
<nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
</nav>
</header>
	<?php
	if ( is_page_template( 'templates/shop-template.php' ) ) {
		get_template_part( 'shop-header' );
	}
	?>
<div id="container">
<main id="content" role="main">
