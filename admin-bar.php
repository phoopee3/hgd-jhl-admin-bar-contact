<?php
/*
Plugin Name: Admin Bar Contact
Plugin URI: https://hgrantdesigns.com
Description: Adds a link in the admin bar to contact support
Version: 1.0
Author: H Grant Designs	/ Jason Lawton
Author URI: https://hgrantdesigns.com
License: MIT
*/

/*
namespace: hgd-abc or hgd_abc
*/


add_action( 'admin_menu', 'hgd_abc_add_admin_menu' );
add_action( 'admin_init', 'hgd_abc_settings_init' );

// add thickbox on the front end for the modal popup
add_action( 'wp_enqueue_scripts', 'add_thickbox' );

include( 'api.php' );

function hgd_abc_add_admin_menu(  ) { 
    // $page_title, $menu_title, $capability, $menu_slug, $function
	add_options_page(
        'Admin Bar Contact',
        'Admin Bar Contact',
        'manage_options',
        'hgd-abc-options-page',
        'hgd_abc_options_page'
    );
}
function hgd_abc_settings_init(  ) { 
	register_setting( 'pluginPage', 'hgd_abc_settings' );
	add_settings_section(
		'hgd_abc_pluginPage_section', 
		__( 'Settings for HGD Admin Bar Contact', 'hgd_abc' ), 
		'hgd_abc_settings_section_callback', 
		'pluginPage'
	);
}

function hgd_abc_settings_section_callback(  ) { 
	echo __( 'Use this page to manage settings', 'hgd_abc' );
}

function hgd_abc_options_page(  ) { 
    global $wpdb;
    if (!current_user_can('manage_options')) {
        wp_die('Go away.');
    }
    // if (!wp_verify_nonce( '_wp_nonce', 'hgd_abc_option_page_update' )) {
    //     wp_die('Nonce verification failed');
    // }
    // "normal" fields
    if (isset($_POST['hgd_abc_enabled'])) {
        update_option('hgd_abc_enabled', $_POST['hgd_abc_enabled']);
    }
    if (isset($_POST['hgd_abc_display_button_text'])) {
        update_option( 'hgd_abc_display_button_text', $_POST['hgd_abc_display_button_text'] );
    }
    if (isset($_POST['hgd_abc_form_type'])) {
        update_option('hgd_abc_form_type', $_POST['hgd_abc_form_type']);
    }
    if (isset($_POST['hgd_abc_default_form_subject_prefix'])) {
        update_option( 'hgd_abc_default_form_subject_prefix', sanitize_text_field( $_POST['hgd_abc_default_form_subject_prefix'] ) );
    }
    if (isset($_POST['hgd_abc_default_form_email'])) {
        update_option( 'hgd_abc_default_form_email', sanitize_text_field( $_POST['hgd_abc_default_form_email'] ) );
    }
    if (isset($_POST['hgd_abc_custom_form'])) {
        update_option( 'hgd_abc_custom_form', sanitize_text_field( $_POST['hgd_abc_custom_form'] ) );
    }
    include 'options-page.php';
}

// update toolbar
function hgd_abc_update_adminbar( $wp_adminbar ) {

    $enabled = get_option( 'hgd_abc_enabled', false );
    // var_dump($enabled);
    if ( $enabled ) {

        // add thickbox
        add_thickbox();

        // get label text
        $hgd_abc_display_button_text = get_option( 'hgd_abc_display_button_text', 'Contact Support' );

        // add SitePoint menu item
        $wp_adminbar->add_node( [
            'id'    => 'hgd-abc',
            'title' => '<span class="ab-icon dashicons dashicons-carrot"></span> ' . __( $hgd_abc_display_button_text ),
            'href'  => '#TB_inline?&width=600&height=550&inlineId=hgd_abc_contact_form',
            'meta'  => [
                'class' => 'thickbox'
            ],
        ] );

        hgd_abc_render_modal();

        hgd_abc_render_script();
    }
}

// admin_bar_menu hook
add_action('admin_bar_menu', 'hgd_abc_update_adminbar', 999);

function hgd_abc_render_modal() { ?>
    <div id="hgd_abc_contact_form" style="display:none;">
        <p>
            <?php
            $form_type = get_option( 'hgd_abc_form_type', 'default' );
            call_user_func( 'hgd_abc_' . $form_type . '_form' );
            ?>
        </p>
    </div>
<?php }

function hgd_abc_render_script() { ?>
    <script>
        jQuery( document ).ready(function() {
            tb_init('li.thickbox a.ab-item');
        });
    </script>
<?php }

function hgd_abc_default_form() {
    // get logged in user
    $current_user = wp_get_current_user();
    ?>
    <div action="" id='hgd-abc-form'>
        <table>
            <tr>
                <td>Name</td>
                <td><input type="text" name="name" value="<?php echo $current_user->display_name; ?>"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" value="<?php echo $current_user->user_email; ?>"></td>
            </tr>
            <tr>
                <td>Subject</td>
                <td><input type="text" name="subject" placeholder="Enter a Subject"></td>
            </tr>
            <tr>
                <td>Message</td>
                <td><textarea name="message" id="message" cols="30" rows="10" placeholder="Please describe your problem here"></textarea></td>
            </tr>
        </table>
        <button id="hgd-abc-submit-form">Send Message</button>
    </div>

    <script>
    jQuery( document ).ready(function() {
        jQuery( '#hgd-abc-submit-form' ).click( function( e ) {
            e.preventDefault();
            // get values
            var formdata     = {};
            formdata.name    = jQuery( '#hgd-abc-form input[name=name]' ).val();
            formdata.email   = jQuery( '#hgd-abc-form input[name=email]').val();
            formdata.subject = jQuery( '#hgd-abc-form input[name=subject]').val();
            formdata.message = jQuery( '#hgd-abc-form textarea[name=message]').val();
            console.log(formdata);
            // make api call
            jQuery.post(
                '<?php echo get_site_url(); ?>/wp-json/hgd-abc/v1/send-email/',
                { data : formdata },
                function( data ) {
                    // console.log( data );
                    if ( data.success == 1 ) {
                        // console.log('hide modal');
                        tb_remove();
                        // jQuery( '#hgd-abc-form input[name=name]' ).val('');
                        // jQuery( '#hgd-abc-form input[name=email]').val('');
                        jQuery( '#hgd-abc-form input[name=subject]').val('');
                        jQuery( '#hgd-abc-form textarea[name=message]').val('');
                    } else {
                        alert('There was an error, try again later');
                    }
                }
            );
        })
    });
    </script>
    <?php
}

function hgd_abc_custom_form() {
    $hgd_abc_custom_form = get_option( 'hgd_abc_custom_form', null );
    $hgd_abc_custom_form = str_replace( '\\', '', $hgd_abc_custom_form );
    if ( $hgd_abc_custom_form ) {
        echo do_shortcode( $hgd_abc_custom_form );
    } else {
        echo 'No form defined';
    }
}