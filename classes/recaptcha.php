<?php

namespace PressGang;

trait Recaptcha {

    /**
     * verify_recaptcha
     *
     */
    protected function verify_recaptcha () {

        $google_recaptcha_secret = filter_var(get_theme_mod('google-recaptcha-secret', FILTER_SANITIZE_STRING));

        $post_data = http_build_query(
            array(
                'secret' => $google_recaptcha_secret,
                'response' => filter_var($_POST['recaptcha'], FILTER_SANITIZE_STRING),
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );

        $context = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);

        return $result && $result->success && $result->score > 0.8;
    }

}
