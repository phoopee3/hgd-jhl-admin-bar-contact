<h1>Admin Bar Contact Options</h1>

<style>
.hgd-abc > div {
    margin-bottom: 20px;
}
.hgd-abc-show-custom {
    display: none;
}
</style>

<form class="hgd-abc" method="POST">
    <?php echo wp_nonce_field( 'hgd_abc_option_page_update' ); ?>

    <div>
        <label for="hgd_abc_enabled"><strong>Enable Admin Bar Contact</strong></label>
        <?php $enabled = get_option( 'hgd_abc_enabled', 0 ); ?>
        <label style="width:auto;"><input type="radio" name="hgd_abc_enabled" id="hgd_abc_enabled" value="1" <?php echo ( $enabled == 1 ) ? 'checked="checked"' : ''; ?>> Yes</label>
        &nbsp;&nbsp;&nbsp;
        <label style="width:auto;"><input type="radio" name="hgd_abc_enabled" id="hgd_abc_enabled" value="0" <?php echo ( $enabled == 0 ) ? 'checked="checked"' : ''; ?>> No</label>
    </div>

    <!-- <div style="display:inline-block;"> -->
    <div>
        <div style="">
            <label for="hgd_abc_display_button_text"><strong>Admin Bar Display Text</strong></label>
        </div>
        <div style="">
            <?php
            $hgd_abc_display_button_text = get_option( 'hgd_abc_display_button_text', '' );
            ?>
            <input type="text" name="hgd_abc_display_button_text" id="hgd_abc_display_button_text" value="<?php echo sanitize_text_field( $hgd_abc_display_button_text ); ?>">
            <br>
            <small>This is the text that will be displayed in the admin bar. Default is <i>Contact Support</i></small>
        </div>
    </div>

    <div>
        <div style="">
            <label for="hgd_abc_form_type"><strong>Form Type</strong></label>
        </div>
        <div style="">
            <?php
            $hgd_abc_form_type = get_option( 'hgd_abc_form_type', null );
            ?>
            <label for="hgd_abc_form_type_default"><input type="radio" name="hgd_abc_form_type" id="hgd_abc_form_type_default" value="default" <?php if ( 'default' == $hgd_abc_form_type ) { echo 'checked'; } ?>> Default</label>
            <br>
            <small>This is a basic form with the user name, email, subject, and message field</small>
            <br>
            <br>
            <label for="hgd_abc_form_type_custom"><input type="radio" name="hgd_abc_form_type" id="hgd_abc_form_type_custom" value="custom" <?php if ( 'custom' == $hgd_abc_form_type ) { echo 'checked'; } ?>> Custom</label>
            <br>
            <small>This will allow you to user a form builder for your poopup form. Enter the shortcode in the field below</small>
        </div>
    </div>

    <div class="hgd-abc-show-custom">
        <div><label for="hgd_abc_custom_form"><strong>Form Shortcode</strong></label></div>
        <div>
            <?php
            $hgd_abc_custom_form = get_option( 'hgd_abc_custom_form', null );
            ?>
            <input type="text" name="hgd_abc_custom_form" id="hgd_abc_custom_form" value="<?php sanitize_text_field( $hgd_abc_custom_form ); ?>">
            <br>
            <small>Enter the shortcode from your form builder</small>
        </div>
    </div>

    <div style="clear:both;"></div>

    <input type="submit" value="Save" class="button button-primary button-large">

    <script>
    jQuery( document ).ready(function() {
        <?php if ( 'custom' == $hgd_abc_form_type ) { ?>
            jQuery('.hgd-abc-show-custom').show();
        <?php } ?>
        
        jQuery('input[name=hgd_abc_form_type]').on('change', function() {
            if ( jQuery( this ).val() == 'custom' ) {
                jQuery('.hgd-abc-show-custom').show();
            } else {
                jQuery('.hgd-abc-show-custom').hide();
            }
        })
    });
    </script>