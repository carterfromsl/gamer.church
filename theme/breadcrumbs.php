<?php

/**
 * Breadcrumb Template for GAMER CHURCH
 */

function display_breadcrumbs() {
    global $post;

    // Check if breadcrumbs are disabled for this post/page
    $disable_breadcrumbs = get_post_meta($post->ID, 'disable_breadcrumbs', true);
    if ($disable_breadcrumbs) {
        return;
    }

    echo '<nav class="breadcrumbs">';
    
    $separator = ' &raquo; ';
    $breadcrumbs = array();

    // Home link
    $breadcrumbs[] = '<a href="/home/">Home</a>';

    if (function_exists('is_woocommerce') && is_woocommerce()) {
        // WooCommerce-specific breadcrumbs
        $breadcrumbs[] = '<a href="' . get_permalink(wc_get_page_id('shop')) . '">Shop</a>';

        if (is_product()) {
            $product_id = $post->ID;
            $primary_category = get_primary_category($product_id);

            if ($primary_category) {
                $breadcrumbs[] = '<a href="' . get_category_link($primary_category->term_id) . '">' . $primary_category->name . '</a>';
            }

            $breadcrumbs[] = get_the_title($product_id);
        } elseif (is_product_category() || is_product_tag()) {
            $current_term = get_queried_object();
            if ($current_term->parent) {
                $parent_term = get_term($current_term->parent);
                $breadcrumbs[] = '<a href="' . get_term_link($parent_term) . '">' . $parent_term->name . '</a>';
            }
            $breadcrumbs[] = $current_term->name;
        }
    } elseif (is_singular()) {
        if ('game' === get_post_type()) {
            // Special case for single posts of post type "game"
            $breadcrumbs[] = '<a href="/games/">Game Library</a>';
        } elseif ('post' === get_post_type()) {
            // Special case for single posts
            $blog_page_id = get_option('page_for_posts');
            if ($blog_page_id) {
                $breadcrumbs[] = '<a href="' . get_permalink($blog_page_id) . '">' . get_the_title($blog_page_id) . '</a>';
            }

            $primary_category = get_primary_category($post->ID);
            if ($primary_category) {
                $breadcrumbs[] = '<a href="' . get_category_link($primary_category->term_id) . '">' . $primary_category->name . '</a>';
            }
        }

        // Handle hierarchical parent pages
        if (is_page()) {
            $ancestors = array_reverse(get_post_ancestors($post));
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
            }
        }

        // Current post title
        $breadcrumbs[] = get_the_title($post->ID);
    } elseif (is_tax() || is_category() || is_tag()) {
        // For taxonomy archives
        $current_term = get_queried_object();

        if ($current_term->parent) {
            $parent_term = get_term($current_term->parent);
            $breadcrumbs[] = '<a href="' . get_term_link($parent_term) . '">' . $parent_term->name . '</a>';
        }

        $breadcrumbs[] = $current_term->name;
    } elseif (is_archive()) {
        // For general archives
        $breadcrumbs[] = post_type_archive_title('', false);
    } elseif (is_search()) {
        // For search results
        $breadcrumbs[] = 'Search results for: ' . get_search_query();
    } elseif (is_404()) {
        // For 404 pages
        $breadcrumbs[] = 'Page Not Found';
    }

    // Output the breadcrumbs
    echo implode($separator, $breadcrumbs);
    echo '</nav>';
}

/**
 * Helper function to get the primary category of a post.
 *
 * @param int $post_id
 * @return WP_Term|null
 */
function get_primary_category($post_id) {
    $primary_category_id = get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
    if ($primary_category_id) {
        return get_term($primary_category_id);
    }

    $categories = get_the_category($post_id);
    return $categories ? $categories[0] : null;
}

if (function_exists('display_breadcrumbs')) {
    display_breadcrumbs();
}

?>
