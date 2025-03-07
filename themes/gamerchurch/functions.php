<?php
add_action( 'after_setup_theme', 'gamerchurch_setup' );
function gamerchurch_setup() {
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
add_theme_support( 'appearance-tools' );
add_theme_support( 'woocommerce' );
add_theme_support( 'custom-logo', array(
        'height'      => 250,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
) );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'gamerchurch' ) ) );
register_nav_menus( array( 'shop-menu' => esc_html__( 'Shop Menu', 'gamerchurch' ) ) );
}

add_action( 'wp_enqueue_scripts', 'gamerchurch_enqueue' );
function gamerchurch_enqueue() {
wp_enqueue_style( 'gamerchurch-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}

// Add "Site Logo" support in the customizer
add_action( 'customize_register', 'gamerchurch_customize_register' );
function gamerchurch_customize_register( $wp_customize ) {
    $wp_customize->add_setting( 'site_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo', array(
        'label'       => __( 'Site Logo', 'gamerchurch' ),
        'description' => __( 'Upload a logo for your site', 'gamerchurch' ),
        'section'     => 'title_tagline',
        'settings'    => 'site_logo',
    ) ) );
}

add_action( 'wp_footer', 'gamerchurch_footer' );
function gamerchurch_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'gamerchurch_document_title_separator' );
function gamerchurch_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'gamerchurch_title' );
function gamerchurch_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function gamerchurch_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'gamerchurch_schema_url', 10 );
function gamerchurch_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'gamerchurch_wp_body_open' ) ) {
function gamerchurch_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'gamerchurch_skip_link', 5 );
function gamerchurch_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'gamerchurch' ) . '</a>';
}
add_filter( 'the_content_more_link', 'gamerchurch_read_more_link' );
function gamerchurch_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'gamerchurch' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'gamerchurch_excerpt_read_more_link' );
function gamerchurch_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( 'Read more...', 'gamerchurch' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'gamerchurch_image_insert_override' );
function gamerchurch_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
return $sizes;
}

// Enable shortcodes in the Custom HTML widget
add_filter('widget_text', 'do_shortcode');
add_filter('widget_text_content', 'do_shortcode');

add_action( 'widgets_init', 'gamerchurch_register_sidebars' );

function gamerchurch_register_sidebars() {
    // Define all sidebars in an array
    $sidebars = array(
        array(
            'name'          => esc_html__( 'Sidebar Widget Area', 'gamerchurch' ),
            'id'            => 'primary-widget-area',
            'description'   => esc_html__( 'Main sidebar displayed on the right.', 'gamerchurch' ),
            'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
            'after_widget'  => '</li>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
        array(
            'name'          => esc_html__( 'Sidebar Top Widget Area', 'gamerchurch' ),
            'id'            => 'sidebar-top-widget-area',
            'description'   => esc_html__( 'Widgets that appear at the top of all sidebars.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-top-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
        array(
            'name'          => esc_html__( 'Sidebar Bottom Widget Area', 'gamerchurch' ),
            'id'            => 'sidebar-bottom-widget-area',
            'description'   => esc_html__( 'Widgets that appear at the bottom of all sidebars.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s sidebar-bottom-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
        array(
            'name'          => esc_html__( 'Header Widget Area', 'gamerchurch' ),
            'id'            => 'header-widget-area',
            'description'   => esc_html__( 'Widgets for the default header.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s header-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
        array(
            'name'          => esc_html__( 'Header Minimal Widget Area', 'gamerchurch' ),
            'id'            => 'header-min-widget-area',
            'description'   => esc_html__( 'Widgets for the minimal header template.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s header-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
        array(
            'name'          => esc_html__( 'Footer Widget Left', 'gamerchurch' ),
            'id'            => 'footer-widget-left',
            'description'   => esc_html__( 'Left column of the footer.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s footer-widget footer-widget-left">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ),
        array(
            'name'          => esc_html__( 'Footer Widget Middle', 'gamerchurch' ),
            'id'            => 'footer-widget-mid',
            'description'   => esc_html__( 'Middle column of the footer.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s footer-widget footer-widget-mid">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ),
        array(
            'name'          => esc_html__( 'Footer Widget Right', 'gamerchurch' ),
            'id'            => 'footer-widget-right',
            'description'   => esc_html__( 'Right column of the footer.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s footer-widget footer-widget-right">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ),
        array(
            'name'          => esc_html__( 'Post & Archive Sidebar', 'gamerchurch' ),
            'id'            => 'post-sidebar',
            'description'   => esc_html__( 'Widgets for single posts.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s post-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
		array(
            'name'          => esc_html__( 'Games Sidebar', 'gamerchurch' ),
            'id'            => 'game-sidebar',
            'description'   => esc_html__( 'Widgets for single games.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s game-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
		array(
            'name'          => esc_html__( 'Member Sidebar', 'gamerchurch' ),
            'id'            => 'member-sidebar',
            'description'   => esc_html__( 'Widgets for Ultimate Member pages.', 'gamerchurch' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s member-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ),
		array(
			'name'          => __( 'Shop Sidebar', 'gamerchurch' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Widgets in this area will be shown on pages where the Shop Sidebar is enabled.', 'gamerchurch' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s shop-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
    );

    // Loop through and register each sidebar
    foreach ( $sidebars as $sidebar ) {
        register_sidebar( $sidebar );
    }
}

add_action( 'wp_head', 'gamerchurch_pingback_header' );
function gamerchurch_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'gamerchurch_enqueue_comment_reply_script' );
function gamerchurch_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function gamerchurch_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'gamerchurch_comment_count', 0 );
function gamerchurch_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

/**
 * Add Layout Settings Options to Post Editor
 */

function add_layout_settings_meta_box() {
    add_meta_box(
        'layout_settings_meta_box',
        'Layout Settings',
        'render_layout_settings_meta_box',
        ['post', 'page'],
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_layout_settings_meta_box');

function render_layout_settings_meta_box($post) {
    $disable_breadcrumbs = get_post_meta($post->ID, 'disable_breadcrumbs', true);
    $disable_sidebar = get_post_meta($post->ID, 'disable_sidebar', true);
	$member_sidebar = get_post_meta($post->ID, 'member_sidebar', true);
	$shop_sidebar = get_post_meta($post->ID, 'shop_sidebar', true);
	
    wp_nonce_field('save_layout_settings_meta_box', 'layout_settings_meta_box_nonce');
    ?>
    <p>
        <label>
            <input type="checkbox" name="disable_breadcrumbs" value="1" <?php checked($disable_breadcrumbs, '1'); ?>>
            Disable Breadcrumbs
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="disable_sidebar" value="1" <?php checked($disable_sidebar, '1'); ?>>
            Disable Sidebar
        </label>
    </p>
	<p>
        <label>
            <input type="checkbox" name="member_sidebar" value="1" <?php checked($member_sidebar, '1'); ?>>
            Enable Member Sidebar
        </label>
    </p>
	<p>
        <label>
            <input type="checkbox" name="shop_sidebar" value="1" <?php checked($shop_sidebar, '1'); ?>>
            Enable Shop Sidebar
        </label>
    </p>
    <?php
}

function save_layout_settings_meta_box($post_id) {
    // Verify nonce
    if (!isset($_POST['layout_settings_meta_box_nonce']) || !wp_verify_nonce($_POST['layout_settings_meta_box_nonce'], 'save_layout_settings_meta_box')) {
        return;
    }

    // Prevent autosave from saving data
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save or clear the disable_breadcrumbs value
    $disable_breadcrumbs = isset($_POST['disable_breadcrumbs']) ? '1' : '';
    update_post_meta($post_id, 'disable_breadcrumbs', $disable_breadcrumbs);

    // Save or clear the disable_sidebar value
    $disable_sidebar = isset($_POST['disable_sidebar']) ? '1' : '';
    update_post_meta($post_id, 'disable_sidebar', $disable_sidebar);
	
	// Save or clear the member_sidebar value
    $member_sidebar = isset($_POST['member_sidebar']) ? '1' : '';
    update_post_meta($post_id, 'member_sidebar', $member_sidebar);
	
	// Save or clear the shop_sidebar value
    $shop_sidebar = isset($_POST['shop_sidebar']) ? '1' : '';
    update_post_meta($post_id, 'shop_sidebar', $shop_sidebar);
}
add_action('save_post', 'save_layout_settings_meta_box');

function add_fullwidth_body_class($classes) {
        global $post;
        $disable_sidebar = get_post_meta($post->ID, 'disable_sidebar', true);
        if ($disable_sidebar === '1') {
            $classes[] = 'fullwidth';
        }
    return $classes;
}
add_filter('body_class', 'add_fullwidth_body_class');

// Modify the text in the comments section for "game" post type
function modify_comments_text_for_game_post_type( $translated_text, $text, $domain ) {
    if ( is_singular( 'game' ) ) {
        switch ( $text ) {
            case 'Comment':
                $translated_text = 'Review';
                break;
            case 'Comments':
                $translated_text = 'Reviews';
                break;
            case 'Leave a Reply':
                $translated_text = 'Leave a Review';
                break;
            case 'Post Comment':
                $translated_text = 'Post Review';
                break;
        }
    }

    return $translated_text;
}
add_filter( 'gettext', 'modify_comments_text_for_game_post_type', 10, 3 );

// Adjust the title displayed above the comment list
function modify_comments_title( $title ) {
    if ( is_singular( 'game' ) ) {
        global $post;
        $comments_count = get_comments_number( $post->ID );
        if ( $comments_count === 1 ) {
            $title = '1 Review';
        } else {
            $title = $comments_count . ' Reviews';
        }
    }
    return $title;
}
add_filter( 'get_comments_number_text', 'modify_comments_title' );

// Change the comment field label "Comment *"
function modify_comment_form_fields_for_game_post_type( $fields ) {
    if ( is_singular( 'game' ) ) {
        if ( isset( $fields['comment_field'] ) ) {
            $fields['comment_field'] = str_replace( 'Comment', 'Review', $fields['comment_field'] );
        }
    }

    return $fields;
}
add_filter( 'comment_form_defaults', 'modify_comment_form_fields_for_game_post_type' );

// WOOCOMMERCE SUPPORT

add_action('woocommerce_before_main_content', function () {
	get_template_part('header-full');
	get_template_part('shop-header');
}, 5);

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

add_action('woocommerce_before_main_content', function () {
    get_template_part('breadcrumbs'); // Load your theme's breadcrumbs.php
}, 15);

function register_ptb_shortcode_with_donor() {
    add_shortcode('dynamic_donor', 'dynamic_ptb_shortcode_handler');
}

function dynamic_ptb_shortcode_handler($atts) {
    global $wp;

    // Get the current page's request URI
    $request_uri = $_SERVER['REQUEST_URI'];

    // Parse the last slug
    $url_path = parse_url($request_uri, PHP_URL_PATH); // Remove query string
    $url_parts = explode('/', trim($url_path, '/')); // Trim and split
    $last_slug = end($url_parts); // Get the last part of the path

    // If last_slug is empty (e.g., trailing slash), set a default value
    if (empty($last_slug)) {
        $last_slug = 'none'; // Replace with your preferred default
    }

    // Replace {DONOR} in the shortcode
    $shortcode = '[ptb type="game" template="Donor List" orderby="title" order="asc" pagination="0" masonry="0" offset="0" posts_per_page="200" style="grid3" not_found="This user hasn\'t donated any games." logic="or" ptb_tax_donor="' . esc_attr($last_slug) . '"][hfcm id="7"]';
	
	$postcount = '[postcount tax="game" custom-tax="donor" custom-value="' . esc_attr($last_slug) . '"]';

    // Return the result of the modified shortcode
    $output = '<div id="donorStats"><h3>Donated Game Stats</h3><ul><li><i>Games Donated:</i>' . do_shortcode($postcount) .  '</li></ul><p>Streaming stats for games donated by this user:</p></div><div id="donorGames">' . do_shortcode($shortcode) . '</div>';
	
	return $output;
}

// Hook to init to register the shortcode
add_action('init', 'register_ptb_shortcode_with_donor');


// Custom post type counter widget with digit wrapping
function display_post_count($atts) {
    $atts = shortcode_atts([
        'tax' => 'post',
        'author' => '',
        'cat' => '',
        'tag' => '',
        'year' => '',
        'custom-tax' => '', // Custom taxonomy or meta key
        'custom-value' => '' // Value for the custom taxonomy or meta key
    ], $atts, 'postcount');

    $post_count = 0;

    if ($atts['tax'] === 'user') {
        // Prepare user query args
        $args = [
            'fields' => 'ID' // Only fetch user IDs to optimize the query
        ];

        // Filter users by custom meta (if applicable)
        if (!empty($atts['custom-tax']) && !empty($atts['custom-value'])) {
            $args['meta_query'] = [
                [
                    'key' => $atts['custom-tax'],
                    'value' => $atts['custom-value'],
                    'compare' => '='
                ]
            ];
        }

        // Execute user query
        $user_query = new WP_User_Query($args);

        // Get the total number of users
        $post_count = $user_query->get_total();
		
    } else {
        // Handle post queries
        $args = [
            'post_type' => $atts['tax'],
            'posts_per_page' => -1, // Get all posts
        ];

        // Filter by author
        if (!empty($atts['author'])) {
            $args['author_name'] = $atts['author'];
        }

        // Filter by category
        if (!empty($atts['cat'])) {
            $args['category_name'] = $atts['cat'];
        }

        // Filter by tag
        if (!empty($atts['tag'])) {
            $args['tag'] = $atts['tag'];
        }

        // Filter by year
        if (!empty($atts['year'])) {
            $args['year'] = $atts['year'];
        }

        // Filter by custom taxonomy and value
        if (!empty($atts['custom-tax']) && !empty($atts['custom-value'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => $atts['custom-tax'],
                    'field' => 'slug',
                    'terms' => $atts['custom-value'],
                ]
            ];
        }

        $query = new WP_Query($args);
        $post_count = $query->found_posts;
    }

    // Convert the count to a string and wrap each digit in a span
    $post_count = (string) $post_count;
    $wrapped_digits = '';
    for ($i = 0; $i < strlen($post_count); $i++) {
        $digit = $post_count[$i];
        $wrapped_digits .= '<span class="pc' . $digit . '">' . $digit . '</span>';
    }

    // Return the styled output
    return '<span class="postcount">' . $wrapped_digits . '</span>';
}

// Hook to init to register the shortcode
add_shortcode('postcount', 'display_post_count');

function mostliked_post_with_likes( $atts = array() ) {
    extract(shortcode_atts(array(
        'field' => 'slug',
        'terms' => '',
        'number' => '10',
        'title' => '',
        'type' => 'post', // Default to 'post'
        'tax' => '' // Make taxonomy optional
    ), $atts));

    global $wpdb;

    // Fetch valid term IDs if taxonomy is provided
    if ( !empty( $tax ) && empty( $terms ) ) {
        $terms_query = $wpdb->prepare("SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s", $tax);
        $term_ids = $wpdb->get_col($terms_query);
        $terms = implode(',', array_map('intval', $term_ids));
    }

    $widget_id = uniqid('mostliked_');

    ob_start();
    ?>
    <div id="<?php echo esc_attr($widget_id); ?>" class="mostliked-widget" data-active-period="all" data-post-type="<?php echo esc_attr($type); ?>">
        <div class="mostliked-filter">
            <button class="filter-btn" data-widget-id="<?php echo esc_attr($widget_id); ?>" data-period="week">This Week</button>
            <button class="filter-btn" data-widget-id="<?php echo esc_attr($widget_id); ?>" data-period="month">This Month</button>
            <button class="filter-btn" data-widget-id="<?php echo esc_attr($widget_id); ?>" data-period="all">All Time</button>
        </div>
        <div class="mostliked-results">
            <?php echo get_most_liked_posts( 'all', $terms, $tax, $type, $number, $field ); ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.mostliked-widget').each(function() {
            var widget = $(this);
            var postType = widget.attr('data-post-type'); // Capture post type
            widget.find('.filter-btn').on('click', function() {
                var period = $(this).data('period');
                var widgetId = $(this).data('widget-id');
                var resultsContainer = $('#' + widgetId + ' .mostliked-results');
                var activePeriod = widget.attr('data-active-period');

                if (activePeriod === period) {
                    return; // Prevent unnecessary reload
                }
                widget.attr('data-active-period', period);
                
                $.ajax({
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    type: 'POST',
                    data: {
                        action: 'filter_mostliked_posts',
                        period: period,
                        terms: '<?php echo esc_js($terms); ?>',
                        tax: '<?php echo esc_js($tax); ?>',
                        type: postType, // Ensure correct post type is sent
                        number: '<?php echo esc_js($number); ?>',
                        field: '<?php echo esc_js($field); ?>',
                        widget_id: widgetId
                    },
                    success: function(response) {
                        resultsContainer.html(response);
                    }
                });
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

function get_most_liked_posts( $period, $terms, $tax, $type, $number, $field ) {
    global $wpdb;

    $term_condition = '';
    if (!empty($terms) && !empty($tax)) {
        $term_ids = array_filter(array_map('intval', explode(',', $terms)));
        if (!empty($term_ids)) {
            $term_condition = "AND tt.term_id IN (" . implode(',', $term_ids) . ")";
        }
    }

    $date_filter = '';
    if ($period === 'week') {
        $date_filter = "AND u.date_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    } elseif ($period === 'month') {
        $date_filter = "AND u.date_time >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    }

    $query = $wpdb->prepare(
        "SELECT p.ID, p.post_title, p.post_date, COALESCE(COUNT(DISTINCT u.id), 0) AS like_count 
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->prefix}ulike u ON p.ID = u.post_id 
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id 
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
        WHERE p.post_type = %s 
        $term_condition
        $date_filter
        GROUP BY p.ID
        ORDER BY like_count DESC, post_date DESC 
        LIMIT %d",
        $type, $number
    );

    $posts = $wpdb->get_results($query);
    ob_start();
    ?> <ul class="mostliked-simple"> <?php
    if (!empty($posts)) {
        foreach ($posts as $post) {
            ?>
            <li>
                <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo esc_attr($post->post_title); ?>">
                    <?php if (has_post_thumbnail($post->ID)) : ?>
                        <span class="likes-thumb"><?php echo get_the_post_thumbnail($post->ID, 'thumbnail'); ?></span>
                    <?php endif; ?>
                    <span class="likes-amount">+<?php echo esc_html($post->like_count); ?></span>
                    <span class="likes-title"><?php echo esc_html($post->post_title); ?></span>
                </a>
            </li>
            <?php
        }
    } else {
        echo '<li><em>No posts found.</em></li>';
    }
    ?> </ul> <?php
    return ob_get_clean();
}

add_action('wp_ajax_filter_mostliked_posts', 'filter_mostliked_posts');
add_action('wp_ajax_nopriv_filter_mostliked_posts', 'filter_mostliked_posts');

function filter_mostliked_posts() {
    $period = $_POST['period'] ?? 'all';
    $terms = $_POST['terms'] ?? '';
    $tax = $_POST['tax'] ?? '';
    $type = $_POST['type'] ?? 'post';
    $number = $_POST['number'] ?? '10';
    $field = $_POST['field'] ?? 'slug';
    $widget_id = $_POST['widget_id'] ?? '';

    echo get_most_liked_posts($period, $terms, $tax, $type, $number, $field);
    wp_die();
}

add_shortcode('mostliked-post-simple', 'mostliked_post_with_likes');

// Random quotes widget shortcode
function randomquote_shortcode($atts) {
    // Parse shortcode attributes with defaults
    $args = shortcode_atts([
        'post_type' => 'post',
        'taxonomy' => '',
        'terms' => '',
        'excerpt' => 25,
    ], $atts);

    // Setup query arguments
    $query_args = [
        'post_type' => $args['post_type'],
        'posts_per_page' => 1,
        'orderby' => 'rand',
        'post_status' => 'publish',
    ];

    // Add taxonomy filtering if provided
    if (!empty($args['taxonomy']) && !empty($args['terms'])) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => $args['taxonomy'],
                'field' => 'slug',
                'terms' => explode(',', $args['terms']),
            ]
        ];
    }

    // Perform the query
    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        $query->the_post();

        // Get post data
        $post_id = get_the_ID();
        $post_title = get_the_title();
        $post_permalink = get_permalink();
        $post_author = get_the_author();
        $author_url = get_author_posts_url(get_the_author_meta('ID'));
        $post_date = get_the_date();

        // Cleanly get the post excerpt
        $raw_excerpt = get_post_field('post_excerpt', $post_id);
        $content_excerpt = $raw_excerpt ? $raw_excerpt : wp_strip_all_tags(get_the_content(), true);
        $trimmed_excerpt = wp_trim_words($content_excerpt, $args['excerpt'], '');

        // Build the output
        $output = '<article class="randomquote">';
        $output .= '<q>' . esc_html($trimmed_excerpt) . '<span class="ellipsis">...</span></q>';
        $output .= '<p class="random-meta">';
        $output .= 'From <a class="random-title" href="' . esc_url($post_permalink) . '" title="Read: ' . esc_attr($post_title) . '">' . esc_html($post_title) . '</a>, ';
        $output .= 'by <a class="random-author" href="' . esc_url($author_url) . '">' . esc_html($post_author) . '</a> ';
        $output .= 'on <time>' . esc_html($post_date) . '</time>';
        $output .= '</p>';
        $output .= '</article>';

        // Reset post data
        wp_reset_postdata();

        return $output;
    } else {
        return '<p>No posts found.</p>';
    }
}
add_shortcode('randomquote', 'randomquote_shortcode');

// Add the shortcode
function in_cart_shortcode() {
    // Check if WooCommerce is active
    if (class_exists('WooCommerce')) {
        // Get the cart object
        $cart_count = WC()->cart->get_cart_contents_count();
        // Output the count wrapped in the <span> element
        return '<span class="incart">' . esc_html($cart_count) . '</span>';
    }
    // Return 0 if WooCommerce is not active
    return '<span class="incart empty">0</span>';
}
add_shortcode('in-cart', 'in_cart_shortcode');

// Add filter to process shortcodes in menu item text
function process_shortcodes_in_menu_items($menu) {
    // Process shortcodes in the menu
    return do_shortcode($menu);
}
add_filter('wp_nav_menu_items', 'process_shortcodes_in_menu_items', 10, 2);

function shortcode_randompic($atts ) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'src' => '', // Comma-separated list of URLs with weights
            'canvas' => '', // Canvas dimensions
        ),
        $atts,
        'randompic'
    );

    // Ensure src is not empty
    if (empty($atts['src'])) {
    	return 'Error: No image sources provided in the shortcode.';
	}

    // Parse the src attribute into an array
    $src_list = array_map('trim', explode(',', $atts['src']));
    $images = array();

    foreach ($src_list as $item) {
		// Find the last colon to separate the URL and weight
		$last_colon_pos = strrpos($item, ':');
		if ($last_colon_pos !== false) {
			$url = substr($item, 0, $last_colon_pos); // Extract URL
			$weight = substr($item, $last_colon_pos + 1); // Extract weight
			$url = trim($url);
			$weight = intval(trim($weight));
		} else {
			$url = '';
			$weight = 0;
		}

		// Validate and sanitize
		if (filter_var($url, FILTER_VALIDATE_URL) && $weight > 0) {
			$url = esc_url($url); // Sanitize now
			$images[$url] = $weight;
		} else {
			error_log("Invalid entry. URL: $url, Weight: $weight");
		}
	}
	if (empty($images)) {
		return 'No valid images were found. Please check the shortcode parameters.';
	}

	// Create weighted pool
	$weighted_pool = [];
	foreach ($images as $url => $weight) {
		for ($i = 0; $i < $weight; $i++) {
			$weighted_pool[] = $url;
		}
	}

	if (empty($weighted_pool)) {
		return 'Error: Weighted pool is empty. Ensure weights are set correctly.';
	}

    // Select a random image
    $random_image = $weighted_pool[array_rand($weighted_pool)];
	if (!$random_image) {
		return 'Error: No image could be selected. Please verify inputs.';
	}

    // Handle canvas dimensions
    $canvas_style = '';
	if (!empty($atts['canvas'])) {
		$canvas_parts = explode(':', $atts['canvas']);
		if (is_array($canvas_parts) && count($canvas_parts) === 2) {
			$canvas_width = intval($canvas_parts[0]);
			$canvas_height = intval($canvas_parts[1]);

			if ($canvas_width > 0 && $canvas_height > 0) {
				$canvas_style = sprintf(
					'width: 100%%; height: auto; aspect-ratio: %d / %d;',
					$canvas_width,
					$canvas_height
				);
			} else {
				error_log("Invalid canvas dimensions: " . $atts['canvas']);
			}
		} else {
			error_log("Canvas format is invalid: " . $atts['canvas']);
		}
	} else {
		error_log("Canvas attribute is empty or missing.");
	}
	
    // Output the image with optional canvas styling
    return sprintf(
        '<img class="randompic" src="%s" alt="GAMER.CHURCH" title="GAMER.CHURCH" style="%s">',
        esc_url($random_image),
        esc_attr($canvas_style)
    );
}

// Register the shortcode
add_shortcode('randompic', 'shortcode_randompic');

// Countdown timer shortcode

function countdown_shortcode($atts) {
    // Extract attributes with defaults
    $attributes = shortcode_atts(array(
        'minute' => '0',
        'hour' => '0',
        'day' => '1',
        'month' => '1',
        'year' => date('Y'),
    ), $atts);
    
    // Get WordPress timezone
    $wp_timezone = get_option('timezone_string');
    if (empty($wp_timezone)) {
        $wp_timezone = 'UTC' . get_option('gmt_offset');
    }
    
    // Create target date in WordPress timezone
    $target_date = new DateTime(
        sprintf(
            '%d-%02d-%02d %02d:%02d:00',
            $attributes['year'],
            $attributes['month'],
            $attributes['day'],
            $attributes['hour'],
            $attributes['minute']
        ),
        new DateTimeZone($wp_timezone)
    );
    
    // Convert to UTC timestamp for JavaScript
    $target_timestamp = $target_date->getTimestamp();
    
    // Enqueue JavaScript
    wp_enqueue_script('countdown-script', get_template_directory_uri() . '/js/countdown.js', array('jquery'), '1.3', true);
    wp_localize_script('countdown-script', 'countdownVars', array(
        'targetTime' => $target_timestamp * 1000, // Convert to milliseconds for JS
        'wpTimezone' => $wp_timezone
    ));
    
    // Return HTML template
    return '
    <div class="countdown">
        <div class="counter count-days">
            <span class="digit">9</span><span class="digit">9</span>
			<span class="count-label">Days</span>
        </div>
        <div class="counter count-hours">
            <span class="digit">9</span><span class="digit">9</span>
			<span class="count-label">Hours</span>
        </div>
        <div class="counter count-minutes">
            <span class="digit">9</span><span class="digit">9</span>
			<span class="count-label">Minutes</span>
        </div>
        <div class="counter count-seconds">
            <span class="digit">9</span><span class="digit">9</span>
			<span class="count-label">Seconds</span>
        </div>
    </div>';
}
add_shortcode('countdown', 'countdown_shortcode');

add_filter(
  'comment_notification_text',
  function($notify_message) {
    $notify_message = explode("\n",$notify_message);
    foreach ($notify_message as $k => $line) {
      $header = trim(substr($line,0,strpos($line,':')));
      switch ($header) {
        case 'Email':
        case 'URL' :
        case 'Whois':
          unset($notify_message[$k]);
        break;
        case 'Author' :
          $pat = '([^(]+)\(.*$';
          $notify_message[$k] = trim(preg_replace('|'.$pat.'|','$1',$line));
        break;
      }
    }
    $notify_message = implode("\n",$notify_message);
    return $notify_message;
  }
);

add_image_size( 'widget-image', 64, 64, true );

/**
 * Custom Recent Posts Widget that adds category classes to each list item.
 */
class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @param array $args     Display arguments including before_title, after_title, etc.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		// Ensure we have a widget ID.
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Posts' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;

		// Query the recent posts.
		$recent_posts = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		) ) );

		if ( $recent_posts->have_posts() ) {

			echo $args['before_widget'];

			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo '<ul>';

			while ( $recent_posts->have_posts() ) {
				$recent_posts->the_post();

				// Get the categories for the current post.
				$categories = get_the_category();
				$classes    = array();

				if ( ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						// Build a class like "category-news" from the category slug.
						$classes[] = 'category-' . esc_attr( $category->slug );
					}
				}

				// Create the class attribute string if there are classes.
				$class_attr = ! empty( $classes ) ? ' class="' . implode( ' ', $classes ) . '"' : '';

				// Output the list item.
				echo '<li' . $class_attr . '>';
				echo '<a href="' . esc_url( get_permalink() ) . '">';
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( get_the_ID(), 'widget-image' );
				}
				// Use the title if available, otherwise fallback to the post ID.
				if ( get_the_title() ) {
					the_title();
				} else {
					echo get_the_ID();
				}
				echo '</a>';
				echo '<em>' . get_the_date() . '</em>';
				echo '</li>';
			}

			echo '</ul>';
			echo $args['after_widget'];

			// Reset post data.
			wp_reset_postdata();
		}
	}
}

/**
 * Unregister the default Recent Posts widget and register the custom one.
 */
function replace_recent_posts_widget() {
	unregister_widget( 'WP_Widget_Recent_Posts' );
	register_widget( 'My_Recent_Posts_Widget' );
}
add_action( 'widgets_init', 'replace_recent_posts_widget' );
