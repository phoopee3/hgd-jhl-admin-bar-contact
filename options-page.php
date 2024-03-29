<h1>Admin Bar Contact Options</h1>

<style>
.hgd-abc > div {
    margin-bottom: 20px;
}
.hgd-abc-show-custom {
    display: none;
}
#hgd_abc_display_button_icon {
    font-family: 'dashicons', 'arial';
}
.asIconPicker-list {
    font-size:30px;
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

    <?php
    $dashicons = jl_get_dashicons();
    ?>

    <!-- <div style="display:inline-block;"> -->
    <div>
        
        <div style="">
            <label for="hgd_abc_display_button_icon"><strong>Admin Bar Display Icon</strong></label>
        </div>
        <div style="">
            <?php
            $hgd_abc_display_button_icon = get_option( 'hgd_abc_display_button_icon', '' );
            ?>
            <select name="hgd_abc_display_button_icon" id="hgd_abc_display_button_icon">
                <option value="">No Icon</option>
                <?php foreach ($dashicons as $dashicon) { ?>
                    <option value="<?php echo $dashicon['class']; ?>" <?php if ( $dashicon['class'] == $hgd_abc_display_button_icon ) { echo "selected"; } ?>><?php echo $dashicon['content'] . ' ' . $dashicon['label']; ?></option>
                <?php } ?>
            </select>
            <!-- <input type="text" name="hgd_abc_display_button_text" id="hgd_abc_display_button_text" value="<?php echo esc_attr( $hgd_abc_display_button_text ); ?>"> -->
            <br>
            <small>This is the icon that is displayed in front of the Button Text. Default is <span class="dashicons dashicons-carrot"></span></small>

            <script>
            jQuery( document ).ready(function() {
                jQuery('#hgd_abc_display_button_icon').asIconPicker({
                    extraClass: 'dashicons',
                    iconPrefix: '',
                });
            });
            </script>
        </div>
    </div>

    <div>
        <div style="">
            <label for="hgd_abc_display_button_text"><strong>Admin Bar Display Text</strong></label>
        </div>
        <div style="">
            <?php
            $hgd_abc_display_button_text = get_option( 'hgd_abc_display_button_text', '' );
            ?>
            <input type="text" name="hgd_abc_display_button_text" id="hgd_abc_display_button_text" value="<?php echo esc_attr( $hgd_abc_display_button_text ); ?>">
            <br>
            <small>This is the text that will be displayed in the admin bar. Default is <i>Contact Support</i></small>
        </div>
    </div>

    <!-- For now we want the form type to always be `default` -->
    <input type="hidden" name="hgd_abc_form_type" id="hgd_abc_form_type_default" value="default">
    <?php /*
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
    */ ?>

    <div class="hgd-abc-show-default">
        <div><label for="hgd_abc_default_form_subject_prefix"><strong>Email subject:</strong></label></div>
        <div>
            <?php
            $hgd_abc_default_form_subject_prefix = get_option( 'hgd_abc_default_form_subject_prefix', null );
            ?>
            <input type="text" name="hgd_abc_default_form_subject_prefix" id="hgd_abc_default_form_subject_prefix" value="<?php echo esc_attr( $hgd_abc_default_form_subject_prefix ); ?>">
            <br>
            <small>Enter a prefix for the subject line</small>
        </div>
    </div>
    
    <div class="hgd-abc-show-default">
        <div><label for="hgd_abc_default_form_email"><strong>Send email to:</strong></label></div>
        <div>
            <?php
            $hgd_abc_default_form_email = get_option( 'hgd_abc_default_form_email', null );
            ?>
            <input type="text" name="hgd_abc_default_form_email" id="hgd_abc_default_form_email" value="<?php echo esc_attr( $hgd_abc_default_form_email ); ?>">
            <br>
            <small>Enter who should receive the email</small>
        </div>
    </div>
    
    <?php /*
    <div class="hgd-abc-show-custom">
        <div><label for="hgd_abc_custom_form"><strong>Form Shortcode</strong></label></div>
        <div>
            <?php
            $hgd_abc_custom_form = get_option( 'hgd_abc_custom_form', null );
            ?>
            <input type="text" name="hgd_abc_custom_form" id="hgd_abc_custom_form" value="<?php echo esc_attr( $hgd_abc_custom_form ); ?>">
            <br>
            <small>Enter the shortcode from your form builder</small>
        </div>
    </div>
    */ ?>

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