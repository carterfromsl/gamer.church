<aside id="sidebar" role="complementary">
    <?php 
    // Display the top widget area if active
    if ( is_active_sidebar( 'sidebar-top-widget-area' ) ) : ?>
        <div id="sidebar-top" class="widget-area sidebar-top-widget">
            <ul class="inner-sidebar">
                <?php dynamic_sidebar( 'sidebar-top-widget-area' ); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php 
    // Display the main widget area based on context
    if ( is_singular( 'game' ) && is_active_sidebar( 'game-sidebar' ) ) : ?>
        <div id="game-sidebar" class="widget-area">
            <ul class="inner-sidebar">
                <?php dynamic_sidebar( 'game-sidebar' ); ?>
            </ul>
        </div>
    <?php elseif ( is_singular() && get_post_meta( get_the_ID(), 'member_sidebar', true ) && is_active_sidebar( 'member-sidebar' ) ) : ?>
        <div id="member-sidebar" class="widget-area">
            <ul class="inner-sidebar">
                <?php dynamic_sidebar( 'member-sidebar' ); ?>
            </ul>
        </div>
	<?php elseif ( 
    ( is_shop() || is_product() || is_product_taxonomy() ) || 
    ( is_singular() && get_post_meta( get_the_ID(), 'shop_sidebar', true ) ) && 
    is_active_sidebar( 'shop-sidebar' ) ) : ?>
    <div id="shop-sidebar" class="widget-area">
        <ul class="inner-sidebar">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </ul>
    </div>
    <?php 
    // Otherwise, display the primary widget area if active
    elseif ( is_active_sidebar( 'primary-widget-area' ) ) : ?>
        <div id="primary" class="widget-area">
            <ul class="inner-sidebar">
                <?php dynamic_sidebar( 'primary-widget-area' ); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php 
    // Display the bottom widget area if active
    if ( is_active_sidebar( 'sidebar-bottom-widget-area' ) ) : ?>
        <div id="sidebar-bottom" class="widget-area sidebar-bottom-widget">
            <ul class="inner-sidebar">
                <?php dynamic_sidebar( 'sidebar-bottom-widget-area' ); ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>
