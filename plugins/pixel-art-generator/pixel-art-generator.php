<?php
/*
Plugin Name: Pixel Art Generator
Description: Creates a pixel art canvas generator via shortcode. Sample: <code>[pixel_canvas width="32" height="32" scale="10" palette="#FF0000,#00FF00,#0000FF,#FFFFFF,#000000"]</code>
Version: 1.0
Author: Carter Lovelace
*/

class PixelArtGenerator {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_shortcode('pixel_canvas', [$this, 'render_pixel_canvas']);
    }

    public function enqueue_scripts() {
        wp_enqueue_script(
            'pixel-art-generator-js', 
            plugin_dir_url(__FILE__) . 'js/pixel-art-generator.js', 
            ['jquery'], 
            '1.1', 
            true
        );
        wp_enqueue_style(
            'pixel-art-generator-css', 
            plugin_dir_url(__FILE__) . 'css/pixel-art-generator.css', 
            [], 
            '1.1'
        );
        
        // Enqueue html2canvas
        wp_enqueue_script(
            'html2canvas', 
            'https://html2canvas.hertzen.com/dist/html2canvas.min.js', 
            [], 
            '1.0.0', 
            true
        );
    }

    public function render_pixel_canvas($atts) {
        $atts = shortcode_atts([
            'width' => 16,
            'height' => 16,
            'scale' => 20,
            'palette' => '#FF0000,#00FF00,#0000FF,#FFFFFF,#000000'
        ], $atts, 'pixel_canvas');

        // Convert palette to array
        $palette = explode(',', $atts['palette']);

        ob_start();
        ?>
        <div class="pixel-art-generator" 
             data-width="<?php echo esc_attr($atts['width']); ?>" 
             data-height="<?php echo esc_attr($atts['height']); ?>" 
             data-scale="<?php echo esc_attr($atts['scale']); ?>">
            <div class="pixel-art-toolbar">
                <div class="color-palette">
                    <?php foreach ($palette as $color): ?>
                        <div class="palette-color" 
                             style="background-color: <?php echo esc_attr($color); ?>;" 
                             data-color="<?php echo esc_attr($color); ?>"></div>
                    <?php endforeach; ?>
                </div>
                <button class="undo-canvas" disabled>Undo</button>
                <button class="redo-canvas" disabled>Redo</button>
                <button class="clear-canvas">Clear Canvas</button>
                <button class="save-canvas">Save</button>
            </div>
            <div class="pixel-canvas"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new PixelArtGenerator();
