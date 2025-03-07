<?php
/**
 * Plugin Name: Cookie Treasure Hunt!
 * Description: A cookie-based treasure hunt game framework for WordPress.
 * Version: 1.0
 * Author: Carter Lovelace
 */

// Prevent direct script access
defined('ABSPATH') or die('No script kiddies please!');

// Enqueue necessary JavaScript
function treasure_hunt_scripts() {
    wp_enqueue_script('treasure-hunt-js', plugin_dir_url(__FILE__) . 'treasure-hunt.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'treasure_hunt_scripts');

// Shortcode for the introduction clue with dynamic content and a close button
function intro_clue_shortcode($atts, $content = null) {
    ob_start();
    ?>
    <div class="clue-wrapper" id="introClue" style="display:none;">
        <div class="clue-content">
            <?php 
            if ($content) {
                echo do_shortcode($content); // Render the inner content of the shortcode
            } else {
                echo '<p>Welcome to the Treasure Hunt! Your journey starts here.</p>';
            }
            ?>
			<button class="clue-close-btn">Close</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('intro_clue', 'intro_clue_shortcode');


// Shortcode for treasure clues with a close button
function treasure_clue_shortcode($atts = [], $content = null) {
    if (!isset($atts['number']) || !isset($atts['label'])) {
        return 'Treasure Clue Shortcode Error: "number" or "label" attribute not set.';
    }

    $clue_number = intval($atts['number']);
    $label = sanitize_text_field($atts['label']);

    ob_start();
    ?>
    <div class="clue-wrapper" id="clue<?php echo $clue_number; ?>" class="treasure-clue" data-clue-number="<?php echo $clue_number; ?>" style="display:none;">
        <div class="clue-content">
            <?php echo esc_html($label); ?>
            <?php echo $content; ?>
			<button class="clue-close-btn">Close</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('treasure_clue', 'treasure_clue_shortcode');


// EOF
?>
