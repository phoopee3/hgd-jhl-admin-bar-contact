<?php
// api
// set up api endpoint
add_action( 'rest_api_init', function () {
    // post email parameter to /wp-json/hgd-abc/v1/test/
    register_rest_route( 'hgd-abc/v1', '/test/', array(
        'methods'  => 'get',
        'callback' => 'hgd_abc_test',
    ) );
} );

function hgd_abc_test() {
    return [
        'success' => 1,
        'message' => [ 'test successfull' ]
    ];
}

add_action( 'rest_api_init', function () {
    // post email parameter to /wp-json/hgd-abc/v1/send-email/
    register_rest_route( 'hgd-abc/v1', '/send-email/', array(
        'methods'  => 'post',
        'callback' => 'hgd_abc_send_email',
    ) );
} );
// api functions
function hgd_abc_send_email( $request ) {
    parse_str( $request->get_body(), $data );
    $data = $data['data'];

    // wp_die(var_dump($data));

    // get options
    $form_type = get_option( 'hgd_abc_form_type', 'default' );

    if ( 'default' == $form_type ) {
        $subject_prefix   = get_option( 'hgd_abc_default_form_subject_prefix', '' );
        $to               = get_option( 'hgd_abc_default_form_email', '' );
        $from_name        = $data['name'];
        $from_email       = $data['email'];
        $subject          = $data['subject'];
        $message          = $data['message'];
        $headers_from     = 'From:';
        $headers_reply_to = 'Reply-To:';
        $headers          = [];

        if ( $from_name ) {
            $header_string .= ' ' . $from_name;
        }
        if ( $from_email ) {
            $header_string .= ' <' . $from_email . '>';
        }
        $headers[] = $headers_from . $header_string;
        $headers[] = $headers_reply_to . $header_string;
        if ( $subject_prefix ) {
            $subject = $subject_prefix . ' ' . $subject;
        }
        // wp_mail( string|array $to, string $subject, string $message, string|array $headers = '', string|array $attachments = array() )
        wp_mail( $to, $subject, $message, $headers );
    }

    return [
        'success'  => 1,
        'messages' => [
            'email sent successfully'
        ],
        'data' => [
            'subject_prefix' => $subject_prefix,
            'to'             => $to,
            'from_name'      => $from_name,
            'from_email'     => $from_email,
            'subject'        => $subject,
            'message'        => $message,
        ],
    ];
}