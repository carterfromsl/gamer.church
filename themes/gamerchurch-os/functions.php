<?php

/**
 * @package WordPress
 * @subpackage temple-os-online
 */

define( 'TOS_VERSION', 1.1 );

if ( ! function_exists( 'blank_setup' ) ) :

	function tos_setup() {
		load_theme_textdomain( 'temple-os-online' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 256,
				'width'       => 256,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => array( 'site-title', 'site-description' ),
			)
		);
		function tos_custom_logo() {
			if ( function_exists( 'the_custom_logo' ) ) {
				return get_custom_logo();
			}else{
				return '';
			}
		}
	}
endif;

add_action( 'after_setup_theme', 'tos_setup' );


function tos_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar',
		'name' => 'Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="side-title">',
		'after_title' => '</h2>',
		'empty_title'=> '',
	));
} 

add_action( 'widgets_init', 'tos_register_sidebars' );

function tos_scripts()  { 

	wp_enqueue_style('style.css', get_stylesheet_directory_uri() . '/style.css?v=2025.2');
	wp_enqueue_style('animations.css', get_stylesheet_directory_uri() . '/styles/animations.css');
	wp_enqueue_style('forms.css', get_stylesheet_directory_uri() . '/styles/forms.css');
	
	wp_enqueue_script( 'tos-godword', get_template_directory_uri() . '/js/godword.js', array(), TOS_VERSION, true );
	wp_enqueue_script( 'tos-query', get_template_directory_uri() . '/js/jquery.min.js', array( 'jquery' ), TOS_VERSION, true );
	wp_enqueue_script( 'tos', get_template_directory_uri() . '/js/theme.min.js', array(), TOS_VERSION, true );
	
}

add_action( 'wp_enqueue_scripts', 'tos_scripts' );

include('shortcodes.php');

/** 
 * remove empty paragraph tags from shortcodes in WordPress.
 */ 

function remove_empty_shortcode_paragraphs( $content ) {
    $toFix = array( 
    	'<p>['    => '[', 
    	']</p>'   => ']', 
    	']<br />' => ']' 
    ); 
    return strtr( $content, $toFix );
}
add_filter( 'the_content', 'remove_empty_shortcode_paragraphs' );

function check_cookie_shortcode($atts, $content = null) {
    // Extract shortcode attributes
    $attributes = shortcode_atts(array(
        'name' => '',
        'value' => null
    ), $atts);
    
    // If no cookie name provided, return empty
    if (empty($attributes['name'])) {
        return '';
    }
    
    // Check if cookie exists
    if (!isset($_COOKIE[$attributes['name']])) {
        return '';
    }
    
    // If value attribute is set, check if cookie matches the value
    if ($attributes['value'] !== null) {
        if ($_COOKIE[$attributes['name']] !== $attributes['value']) {
            return '';
        }
    }
    
    // Cookie exists and value matches (if specified), so return the content
    return do_shortcode($content);
}
add_shortcode('hascookie', 'check_cookie_shortcode');


/**
 * System Dashboard Shortcode
 * 
 * Creates a [system_dashboard] shortcode that displays comprehensive system information
 * about the user's device, session, and server environment.
 */

class SystemDashboard {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('system_dashboard', array($this, 'render_dashboard'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    public function enqueue_assets() {
        wp_enqueue_script(
            'system-dashboard-script',
            get_template_directory_uri() . '/js/system-dashboard.js', array(), TOS_VERSION, true 
        );
        
        wp_localize_script('system-dashboard-script', 'systemDashboardData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('system_dashboard_nonce'),
            'wp_version' => get_bloginfo('version'),
            'theme' => wp_get_theme()->get('Name'),
            'php_version' => phpversion()
        ));
    }
    
    public function render_dashboard($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'show_server_info' => 'true',
            'show_wp_info' => 'true',
            'show_client_info' => 'true'
        ), $atts);
        
        // Start output buffering
        ob_start();
        
        // Get server information
        $server_info = $this->get_server_info();
        $wp_info = $this->get_wordpress_info();
        
        // Generate unique ID for this instance
        $dashboard_id = 'system-dashboard-' . uniqid();
        ?>
        <div class="system-dashboard" id="<?php echo esc_attr($dashboard_id); ?>">
            <ul class="dashboard-sections">
                <?php if ($atts['show_client_info'] === 'true'): ?>
                <li class="client-info section">
                    <h3>Client Information</h3>
                    <ul class="client-details">
                        <li class="browser-info">Loading browser information...</li>
                        <li class="screen-info">Loading screen information...</li>
                        <li class="performance-info">Loading performance metrics...</li>
                        <li class="language-info">Loading language settings...</li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if ($atts['show_server_info'] === 'true'): ?>
                <li class="server-info section">
                    <h3>Server Information</h3>
                    <ul class="server-details">
                        <?php foreach ($server_info as $key => $value): ?>
                        <li>
                            <strong><?php echo esc_html($key); ?>:</strong>
                            <?php echo esc_html($value); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endif; ?>
                
                <?php if ($atts['show_wp_info'] === 'true'): ?>
                <li class="wordpress-info section">
                    <h3>WordPress Information</h3>
                    <ul class="wordpress-details">
                        <?php foreach ($wp_info as $key => $value): ?>
                        <li>
                            <strong><?php echo esc_html($key); ?>:</strong>
                            <?php echo esc_html($value); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function get_server_info() {
        return array(
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'PHP Version' => phpversion(),
            'Server Protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
            'Server Time' => current_time('mysql'),
            'Server Load' => function_exists('sys_getloadavg') ? implode(', ', sys_getloadavg()) : 'Unavailable',
            'Memory Limit' => ini_get('memory_limit'),
            'Max Upload Size' => size_format(wp_max_upload_size()),
            'Max Execution Time' => ini_get('max_execution_time') . ' seconds',
            'IP Protocol' => $this->is_ssl() ? 'HTTPS' : 'HTTP'
        );
    }
    
    private function get_wordpress_info() {
        global $wpdb;
        
        return array(
            'WordPress Version' => get_bloginfo('version'),
            'Theme' => wp_get_theme()->get('Name'),
            'Active Plugins' => count(get_option('active_plugins')),
            'PHP Memory Limit' => WP_MEMORY_LIMIT,
            'Database Size' => size_format($this->get_database_size()),
            'Upload Directory Size' => size_format($this->get_upload_directory_size()),
            'Debug Mode' => WP_DEBUG ? 'Enabled' : 'Disabled'
        );
    }
    
    private function get_database_size() {
        global $wpdb;
        $size = 0;
        $rows = $wpdb->get_results("SHOW TABLE STATUS");
        
        foreach ($rows as $row) {
            $size += $row->Data_length + $row->Index_length;
        }
        
        return $size;
    }
    
    private function get_upload_directory_size() {
        $upload_dir = wp_upload_dir();
        return $this->get_directory_size($upload_dir['basedir']);
    }
    
    private function get_directory_size($path) {
        $size = 0;
        $files = scandir($path);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                if (is_dir($path . '/' . $file)) {
                    $size += $this->get_directory_size($path . '/' . $file);
                } else {
                    $size += filesize($path . '/' . $file);
                }
            }
        }
        
        return $size;
    }
    
    private function is_ssl() {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }
            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }
        }
        
        if (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        
        return false;
    }
}

// Initialize the shortcode
SystemDashboard::get_instance();

// Add custom meta fields for views and author credit
function add_custom_meta_fields() {
    add_meta_box(
        'custom_meta_box',
        __('Custom Post Options', 'textdomain'),
        'render_custom_meta_box',
        ['post', 'page'], // Add to posts and pages; you can include custom post types here
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_fields');

function render_custom_meta_box($post) {
    $views = get_post_meta($post->ID, '_post_views_count', true) ?: '0';
    $author_credit = get_post_meta($post->ID, '_author_credit', true) ?: '';
	$iframe_source = get_post_meta($post->ID, '_iframe_source', true) ?: '';
	$iframe_classes = get_post_meta($post->ID, '_iframe_classes', true) ?: '';

    echo '<p><label for="post_views_count">' . __('View Count:', 'textdomain') . '</label></p>';
    echo '<input type="number" id="post_views_count" name="post_views_count" value="' . esc_attr($views) . '" style="width:100%;" />';
    
    echo '<p><label for="author_credit">' . __('Author Credit:', 'textdomain') . '</label></p>';
    echo '<input type="text" id="author_credit" name="author_credit" value="' . esc_attr($author_credit) . '" style="width:100%;" />';
	
	echo '<p><label for="iframe_source">' . __('Iframe Source:', 'textdomain') . '</label></p>';
    echo '<input type="text" id="iframe_source" name="iframe_source" value="' . esc_attr($iframe_source) . '" style="width:100%;" />';
	
	echo '<p><label for="iframe_classes">' . __('Iframe Classes:', 'textdomain') . '</label></p>';
    echo '<input type="text" id="iframe_classes" name="iframe_classes" value="' . esc_attr($iframe_classes) . '" style="width:100%;" />';
}

// Save the custom meta fields
function save_custom_meta_fields($post_id) {
    // Verify nonce and permissions
    if (!isset($_POST['post_views_count']) || !current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save or update view count
    $views = sanitize_text_field($_POST['post_views_count']);
    update_post_meta($post_id, '_post_views_count', $views);

    // Save or update author credit
    $author_credit = sanitize_text_field($_POST['author_credit']);
    update_post_meta($post_id, '_author_credit', $author_credit);
	
	// Save or update iframe source
    $iframe_source = sanitize_text_field($_POST['iframe_source']);
    update_post_meta($post_id, '_iframe_source', $iframe_source);
	
	// Save or update iframe classes
    $iframe_classes = sanitize_text_field($_POST['iframe_classes']);
    update_post_meta($post_id, '_iframe_classes', $iframe_classes);
}
add_action('save_post', 'save_custom_meta_fields');

function increment_post_view_count_once() {
    if (is_admin()) {
        // Do not count views in the admin area
        return;
    }

    if (is_singular()) {
        global $post;

        if (isset($post->ID)) {
            $post_id = $post->ID;

            // Check if the post ID is in the user's session
            if (!isset($_SESSION)) {
                session_start(); // Start PHP session if not already started
            }

            // Use session to store viewed posts
            if (empty($_SESSION['viewed_posts']) || !in_array($post_id, $_SESSION['viewed_posts'])) {
                // Add post ID to session if not already viewed
                $_SESSION['viewed_posts'][] = $post_id;

                // Increment the view count
                $views = (int) get_post_meta($post_id, '_post_views_count', true);
                $views++;
                update_post_meta($post_id, '_post_views_count', $views);

            } else {
                ;
            }
        } else {
            ;
        }
    } else {
        ;
    }
}
add_action('wp', 'increment_post_view_count_once');


// Function to display view count and author credit in templates
function display_custom_fields($post_id) {
    $views = get_post_meta($post_id, '_post_views_count', true) ?: '0';
    $author_credit = get_post_meta($post_id, '_author_credit', true) ?: '';

    echo '<p>' . __('Plays:', 'textdomain') . ' <b>' . esc_html($views) . '</b></p>';
    if (!empty($author_credit)) {
        echo '<p>' . __('Credit:', 'textdomain') . ' <b>' . esc_html($author_credit) . '</b></p>';
    }
}

function display_game_frame($post_id) {
    $frame = get_post_meta($post_id, '_iframe_source', true) ?: '';
    $classes = get_post_meta($post_id, '_iframe_classes', true) ?: '';

    echo '<iframe class="' . esc_html($classes) .'" src="' . esc_html($frame) . '"></iframe>';
}


function get_post_view_count($post_id) {
    $views = get_post_meta($post_id, '_post_views_count', true) ?: '0';
    return esc_html($views);
}

// Pixel art redirect
add_filter( 'gform_confirmation_4', 'custom_confirmation_redirect', 10, 4 );
function custom_confirmation_redirect( $confirmation, $form, $entry, $ajax ) {
    // Force a redirect to your thank-you page
    $confirmation = array( 'redirect' => 'https://console.gamer.church/artwork-submitted/' );
    return $confirmation;
}

// Register the shortcode and output the div with an encoded data-codes attribute.
function console_overflow_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'codes' => '',
    ), $atts, 'console_overflow' );

    // Encode the codes in Base64.
    $encoded_codes = base64_encode( $atts['codes'] );

    // The div now carries the obfuscated codes.
    return '<div id="consoleOverflow" data-codes="' . esc_attr( $encoded_codes ) . '"></div>';
}
add_shortcode( 'console_overflow', 'console_overflow_shortcode' );

// Enqueue the JavaScript file.
function enqueue_console_overflow_script() {
    wp_enqueue_script( 'console-overflow', get_template_directory_uri() . '/js/console-overflow.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_console_overflow_script' );



?>
