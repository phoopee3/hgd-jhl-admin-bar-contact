<?php
/*
Plugin Name: Admin Bar Contact
Plugin URI: https://hgrantdesigns.com
Description: Adds a link in the admin bar to contact support
Version: 1.0.1
Author: H Grant Designs	/ Jason Lawton
Author URI: https://hgrantdesigns.com
License: MIT
GitHub Plugin URI: https://github.com/phoopee3/hgd-jhl-admin-bar-contact
*/

/*
namespace: hgd-abc or hgd_abc
*/

add_action( 'admin_menu', 'hgd_abc_add_admin_menu' );
add_action( 'admin_init', 'hgd_abc_settings_init' );

// add thickbox on the front end for the modal popup
// add_action( 'wp_enqueue_scripts', 'add_thickbox' );

// switch to micromodal.js - https://micromodal.now.sh/
add_action( 'admin_enqueue_scripts', 'hgd_abc_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'hgd_abc_enqueue_scripts' );
function hgd_abc_enqueue_scripts() {
    wp_enqueue_script( 'micromodal', 'https://unpkg.com/micromodal/dist/micromodal.min.js' );
}

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
        // add_thickbox();

        // get label text
        $hgd_abc_display_button_text = get_option( 'hgd_abc_display_button_text', 'Contact Support' );

        // add SitePoint menu item
        // $wp_adminbar->add_node( [
        //     'id'    => 'hgd-abc',
        //     'title' => '<span class="ab-icon dashicons dashicons-carrot"></span> ' . __( $hgd_abc_display_button_text ),
        //     'href'  => '#TB_inline?&width=600&height=550&inlineId=hgd_abc_contact_form',
        //     'meta'  => [
        //         'class' => 'thickbox'
        //     ],
        // ] );
        $wp_adminbar->add_node( [
            'id'    => 'hgd-abc',
            'title' => '<span class="ab-icon dashicons dashicons-carrot"></span> ' . __( $hgd_abc_display_button_text ),
            'href'  => '#',
            'meta'  => [
            ],
        ] );

        hgd_abc_render_modal_mm();

        hgd_abc_render_script_mm();
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

function hgd_abc_render_modal_mm() { ?>
    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
            <?php echo get_option( 'hgd_abc_display_button_text', 'Contact Support' ); ?>
          </h2>
          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <p>
            <?php
            $form_type = get_option( 'hgd_abc_form_type', 'default' );
            call_user_func( 'hgd_abc_' . $form_type . '_form' );
            ?>
          </p>
        </main>
        <footer class="modal__footer">
          <!-- <button class="modal__btn modal__btn-primary">Continue</button> -->
          <button class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
        </footer>
      </div>
    </div>
</div>
<?php }

function hgd_abc_render_script() { ?>
    <script>
        jQuery( document ).ready(function() {
            tb_init('li.thickbox a.ab-item');
        });
    </script>
<?php }

function hgd_abc_render_script_mm() { ?>
    <style>
        .modal {
            display: none;
        }

        .modal.is-open {
            display: block;
        }
        /**************************\
        Basic Modal Styles
        \**************************/

        .modal {
        font-family: -apple-system,BlinkMacSystemFont,avenir next,avenir,helvetica neue,helvetica,ubuntu,roboto,noto,segoe ui,arial,sans-serif;
        }

        .modal label {
            display:block;
        }
        .modal input, .modal textarea {
            width: 100%;
        }
        .modal #hgd-abc-form > div {
            margin-bottom: 10px;
        }
        .modal__overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999;
        }

        .modal__container {
        background-color: #fff;
        padding: 30px;
        max-width: 500px;
        max-height: 100vh;
        border-radius: 4px;
        overflow-y: auto;
        box-sizing: border-box;
        }

        .modal__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .modal__title {
        margin-top: 0;
        margin-bottom: 0;
        font-weight: 600;
        font-size: 1.25rem;
        line-height: 1.25;
        color: #00449e;
        box-sizing: border-box;
        }

        .modal__close {
        background: transparent;
        border: 0;
        }

        .modal__header .modal__close:before { content: "\2715"; }

        .modal__content {
        margin-top: 2rem;
        margin-bottom: 2rem;
        line-height: 1.5;
        color: rgba(0,0,0,.8);
        }

        .modal__btn {
        font-size: .875rem;
        padding-left: 1rem;
        padding-right: 1rem;
        padding-top: .5rem;
        padding-bottom: .5rem;
        background-color: #e6e6e6;
        color: rgba(0,0,0,.8);
        border-radius: .25rem;
        border-style: none;
        border-width: 0;
        cursor: pointer;
        -webkit-appearance: button;
        text-transform: none;
        overflow: visible;
        line-height: 1.15;
        margin: 0;
        will-change: transform;
        -moz-osx-font-smoothing: grayscale;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
        transition: -webkit-transform .25s ease-out;
        transition: transform .25s ease-out;
        transition: transform .25s ease-out,-webkit-transform .25s ease-out;
        }

        .modal__btn:focus, .modal__btn:hover {
        -webkit-transform: scale(1.05);
        transform: scale(1.05);
        }

        .modal__btn-primary {
        background-color: #00449e;
        color: #fff;
        }
    </style>
    <script>
        jQuery( document ).ready(function() {
            jQuery('#wp-admin-bar-hgd-abc a').attr('data-micromodal-trigger', 'modal-1');
            MicroModal.init({
                // onShow: modal => console.info(`${modal.id} is shown`), // [1]
                // onClose: modal => console.info(`${modal.id} is hidden`), // [2]
                // openTrigger: 'data-custom-open', // [3]
                // closeTrigger: 'data-custom-close', // [4]
                disableScroll: true, // [5]
                // disableFocus: false, // [6]
                // awaitCloseAnimation: false, // [7]
                debugMode: true // [8]
            });
        });
    </script>
<?php }

function hgd_abc_default_form() {
    // get logged in user
    $current_user = wp_get_current_user();
    ?>
    <form action="javascript:submitFormAjax()" id='hgd-abc-form'>
        <div>
            <label for="hgd_abc_form_name">Name</label>
            <input id="hgd_abc_form_name" type="text" name="name" value="<?php echo $current_user->display_name; ?>" required>
        </div>
        <div>
            <label for="hgd_abc_form_email">Email</label>
            <input id="hgd_abc_form_email" type="text" name="email" value="<?php echo $current_user->user_email; ?>" required>
        </div>
        <div>
            <label for="hgd_abc_form_subject">Subject</label>
            <input id="hgd_abc_form_subject" type="text" name="subject" placeholder="Enter a Subject" required>
        </div>
        <div>
            <label for="hgd_abc_form_message">Message</label>
            <textarea id="hgd_abc_form_message" name="message" id="message" cols="30" rows="10" placeholder="Please describe your problem here" required></textarea>
        </div>
        <button class="modal__btn" id="hgd-abc-submit-form" type="submit">Send Message</button>
    </form>

    <div id="hgd_abc_form_submit_message">

    </div>

    <script>
        function submitFormAjax() {
            var formdata     = {};
            formdata.name    = jQuery( '#hgd-abc-form input[name=name]' ).val();
            formdata.email   = jQuery( '#hgd-abc-form input[name=email]').val();
            formdata.subject = jQuery( '#hgd-abc-form input[name=subject]').val();
            formdata.message = jQuery( '#hgd-abc-form textarea[name=message]').val();
            // console.log(formdata);
            // make api call
            jQuery.post(
                '<?php echo get_site_url(); ?>/wp-json/hgd-abc/v1/send-email/',
                { data : formdata },
                function( data ) {
                    // console.log( data );
                    if ( data.success == 1 ) {
                        // console.log('hide modal');
                        jQuery('#hgd_abc_form_submit_message').html('<p>The message has been submitted successfully, thank you.</p><p><small>This dialog will close in 5 seconds.</small></p>');
                        setTimeout(function() {
                            // MicroModal.close('modal-1');
                            jQuery( '.modal__footer button' ).click();
                            jQuery( '#hgd-abc-form input[name=subject]' ).val('');
                            jQuery( '#hgd-abc-form textarea[name=message]' ).val('');
                            jQuery( '#hgd_abc_form_submit_message' ).html('');
                        }, 5000);
                        // jQuery( '#hgd-abc-form input[name=name]' ).val('');
                        // jQuery( '#hgd-abc-form input[name=email]').val('');
                    } else {
                        jQuery('#hgd_abc_form_submit_message').html('<p>The was an issue sending your message, please try again later.</p>');
                    }
                }
            );
        }
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