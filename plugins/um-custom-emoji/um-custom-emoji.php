<?php
/**
 * Plugin Name: UM Messaging Custom Emoji
 * Description: Extend the Ultimate Member Messaging plugin to add custom emojis and allow overriding default ones.
 * Version: 1.0
 * Author: Carter Lovelace
 */

// Prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Hook into the 'um_messaging_emoji' filter to add or override emojis.
add_filter( 'um_messaging_emoji', 'um_messaging_add_custom_emojis' );

/**
 * Add custom emojis and override default ones.
 *
 * @param array $emojis Existing emojis registered in Ultimate Member Messaging.
 * @return array Modified array of emojis.
 */
function um_messaging_add_custom_emojis( $emojis ) {
    // Add new custom emojis.
    $custom_emojis = array(
        ':star:' => '/wp-content/uploads/2024/12/star-spin.gif',
        ':fire:' => '/wp-content/uploads/2024/12/flame.gif',
		':heart:' => '/wp-content/uploads/2024/12/heart3d.gif',
		':book:' => '/wp-content/uploads/2024/12/book-spin.gif',
		':gift:' => '/wp-content/uploads/2024/12/gift-spin.gif',
		':money:' => '/wp-content/uploads/2024/12/dollar-spin.gif',
		':beer:' => '/wp-content/uploads/2024/12/beer_glass.gif',
		':home:' => '/wp-content/uploads/2024/12/Home3d.gif',
		':ghost:' => '/wp-content/uploads/2024/12/ghost-dance.gif',
		':sparkle:' => '/wp-content/uploads/2024/12/sparkles.gif',
		':globe:' => '/wp-content/uploads/2024/12/spinearth.gif',
		':ufo:' => '/wp-content/uploads/2024/12/ufo.gif',
		':rolldice:' => '/wp-content/uploads/2024/12/diceroll.gif',
		':new:' => '/wp-content/uploads/2024/12/new-blink.gif',
		':no:' => '/wp-content/uploads/2024/12/RedX.gif',
		':cd:' => '/wp-content/uploads/2024/12/cd-spin.gif',
		':x:' => '/wp-content/uploads/2024/12/animatedx.gif',
		':mod:' => '/wp-content/uploads/2024/12/mod-spin.gif',
		':!:' => '/wp-content/uploads/2024/12/warning.gif',
		':finger:' => '/wp-content/uploads/2024/12/finger-point.gif',
		':vhs:' => '/wp-content/uploads/2024/12/vhs-tape.gif',
		':spike:' => '/wp-content/uploads/2024/12/spike-spin.gif',
		':eye:' => '/wp-content/uploads/2024/12/eye-blink-icon.gif',
		':sword:' => '/wp-content/uploads/2024/12/sword-spin.gif',
		':candle:' => '/wp-content/uploads/2024/12/candle.gif',
		':gaming:' => '/wp-content/uploads/2024/12/game-kiosk.gif',
		':triangle:' => '/wp-content/uploads/2024/12/pyramid.gif',
		':church:' => '/wp-content/uploads/2024/12/church-pixel.gif',
    );

    // Overwrite existing emojis if needed.
    $overrides = array(
        ';)' => '/wp-content/uploads/2024/12/wink.gif', // Override smiley face.
    );

    // Merge new emojis with existing ones.
    $emojis = array_merge( $emojis, $custom_emojis );

    // Apply overrides.
    foreach ( $overrides as $code => $url ) {
        $emojis[ $code ] = $url;
    }

    return $emojis;
}
