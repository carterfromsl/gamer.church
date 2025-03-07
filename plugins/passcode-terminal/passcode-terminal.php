<?php
/*
Plugin Name: Passcode Terminal
Description: Creates a terminal-style password system for unlocking content
Version: 1.0.1
Author: Carter Lovelace
*/

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Add menu item to WordPress admin
add_action('admin_menu', 'pt_add_admin_menu');
function pt_add_admin_menu() {
    add_menu_page('Passcode Terminal Settings', 'Passcode Terminal', 'manage_options', 'passcode-terminal', 'pt_admin_page');
}

// Register settings
add_action('admin_init', 'pt_register_settings');
function pt_register_settings() {
    register_setting('pt_settings_group', 'pt_passcodes');
}

// Admin page HTML
function pt_admin_page() {
    $passcodes = get_option('pt_passcodes', array());
    ?>
    <div class="wrap">
        <h1>Passcode Terminal Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pt_settings_group'); ?>
            <table class="form-table" id="passcodes-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>ID</th>
                        <th>Passcode</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($passcodes)) {
                        foreach ($passcodes as $index => $passcode) : ?>
                            <tr>
                                <td><input type="text" name="pt_passcodes[<?php echo $index; ?>][title]" value="<?php echo esc_attr($passcode['title']); ?>" required></td>
                                <td><input type="text" name="pt_passcodes[<?php echo $index; ?>][id]" value="<?php echo esc_attr($passcode['id']); ?>" required></td>
                                <td><input type="text" name="pt_passcodes[<?php echo $index; ?>][code]" value="<?php echo esc_attr($passcode['code']); ?>" required></td>
                                <td><button type="button" class="button remove-row">Remove</button></td>
                            </tr>
                        <?php endforeach;
                    } ?>
                </tbody>
            </table>
            <button type="button" class="button" id="add-passcode">Add Passcode</button>
            <?php submit_button(); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Keep track of the highest index used
        let maxIndex = -1;
        $('#passcodes-table tbody tr').each(function() {
            const inputs = $(this).find('input');
            const match = inputs.first().attr('name').match(/\[(\d+)\]/);
            if (match) {
                maxIndex = Math.max(maxIndex, parseInt(match[1]));
            }
        });
        
        $('#add-passcode').on('click', function() {
            maxIndex++;
            const newRow = `
                <tr>
                    <td><input type="text" name="pt_passcodes[${maxIndex}][title]" required></td>
                    <td><input type="text" name="pt_passcodes[${maxIndex}][id]" required></td>
                    <td><input type="text" name="pt_passcodes[${maxIndex}][code]" required></td>
                    <td><button type="button" class="button remove-row">Remove</button></td>
                </tr>
            `;
            $('#passcodes-table tbody').append(newRow);
        });
        
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // If the table is empty, add one row to start
        if ($('#passcodes-table tbody tr').length === 0) {
            $('#add-passcode').click();
        }
    });
    </script>
    <?php
}

// Terminal shortcode
add_shortcode('passcode_terminal', 'pt_terminal_shortcode');
function pt_terminal_shortcode() {
    $passcodes = get_option('pt_passcodes', array());
    $unlocked = isset($_COOKIE['passcodeSession']) ? json_decode(stripslashes($_COOKIE['passcodeSession']), true) : array();
    
    wp_enqueue_script('jquery');
    
    ob_start();
    ?>
    <div class="passcode-terminal">
        <div class="terminal-input">
            <input type="text" id="passcode-input" placeholder="Enter passcode...">
            <button class="button t-button" onclick="submitPasscode()" id="submit-passcode">Submit</button>
        </div>
        
        <div class="unlocked-modules">
            <h3>Unlocked Modules:</h3>
            <ul id="unlocked-list">
                <?php
                if (is_array($unlocked)) {
                    foreach ($passcodes as $passcode) {
                        if (in_array($passcode['id'], $unlocked)) {
                            echo '<li>' . esc_html($passcode['title']) . '</li>';
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
    function submitPasscode() {
    var terminal = document.querySelector('.passcode-terminal');
    var input = document.getElementById('passcode-input');
    var submitButton = document.getElementById('submit-passcode');
    var passcode = input.value;

    // Check if the passcode is a 4 or 5 digit number with no non-numerical characters
    if (/^\d{4,5}$/.test(passcode)) {
        // Redirect to the appropriate URL
        window.location.href = `/docs/post-${passcode}`;
        return;
    }

    // Add processing state
    terminal.classList.add('processing');
    input.disabled = true;
    submitButton.disabled = true;

    jQuery.post(
        '<?php echo admin_url('admin-ajax.php'); ?>',
        {
            action: 'check_passcode',
            passcode: passcode
        },
        function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Invalid passcode');
                // Remove processing state on error
                terminal.classList.remove('processing');
                input.disabled = false;
                submitButton.disabled = false;
            }
            input.value = '';
        }
    ).fail(function() {
        // Remove processing state on failure
        terminal.classList.remove('processing');
        input.disabled = false;
        submitButton.disabled = false;
        alert('Error processing request');
        input.value = '';
    });
}

document.getElementById('passcode-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        submitPasscode();
    }
});
    </script>

    <?php
    return ob_get_clean();
}

// Content restriction shortcode
add_shortcode('passcode', 'pt_content_restriction_shortcode');
function pt_content_restriction_shortcode($atts, $content = null) {
    if (!isset($atts['id'])) return '';
    
    $unlocked = isset($_COOKIE['passcodeSession']) ? json_decode(stripslashes($_COOKIE['passcodeSession']), true) : array();
    
    if (is_array($unlocked) && in_array($atts['id'], $unlocked)) {
        return do_shortcode($content);
    }
    
    return '';
}

// AJAX handler
add_action('wp_ajax_check_passcode', 'pt_check_passcode');
add_action('wp_ajax_nopriv_check_passcode', 'pt_check_passcode');
function pt_check_passcode() {
    $submitted_code = $_POST['passcode'];
    $passcodes = get_option('pt_passcodes', array());
    
    foreach ($passcodes as $passcode) {
        if ($passcode['code'] === $submitted_code) {
            $unlocked = isset($_COOKIE['passcodeSession']) ? json_decode(stripslashes($_COOKIE['passcodeSession']), true) : array();
            if (!is_array($unlocked)) $unlocked = array();
            
            if (!in_array($passcode['id'], $unlocked)) {
                $unlocked[] = $passcode['id'];
                setcookie('passcodeSession', json_encode($unlocked), time() + (365 * 24 * 60 * 60), '/');
            }
            
            wp_send_json_success();
            exit;
        }
    }
    
    wp_send_json_error();
    exit;
}
