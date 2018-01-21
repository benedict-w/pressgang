<?php

namespace PressGang\Widget;

/**
 * Plugin Name: Pressgang Mailchimp Widget
 * Description: A simple widget that embeds a Mailchimp Signup form
 * Version: 0.1
 * Author: Ben Wallis
 * Author URI: http://www.benedict-wallis.com
 *
 */
class MailchimpSignup extends \Pressgang\Widget {

    protected $fields = array(
        'title' => array(
            'view' => 'admin.text.twig',
            'class' => 'widefat',
            'label' => "Title",
        ),
        'api_key' => array(
            'view' => 'admin.text.twig',
            'class' => 'widefat',
            'label' => "API Key",
        ),
        'list_id' => array(
            'view' => 'admin.text.twig',
            'class' => 'widefat',
            'label' => "List ID",
        ),
    );

    /**
     * __construct
     *
     * Constructor
     *
     */
    public function __construct() {

        \Pressgang\Scripts::$scripts['mailchimp'] = array(
            'src' => get_template_directory_uri() . '/js/src/custom/mailchimp.js',
            'deps' => array('jquery'),
            'ver' => '1.0',
            'hook' => 'render_widget_mailchimp_signup',
        );

        $this->description = __("A simple widget that embeds a Mailchimp signup form", THEMENAME);

        add_action('wp_ajax_mailchimp_signup', array($this, 'signup'));
        add_action('wp_ajax_nopriv_mailchimp_signup', array($this, 'signup'));

        parent::__construct();
    }


    /**
     * Signup
     *
     */
    function signup() {

        $opts = get_option($this->option_name);
        $opts = $opts[$this->number];

        $success = false;
        $message = '';

        $email = filter_input(INPUT_POST, 'email',FILTER_VALIDATE_EMAIL);

        try {

            $mailchimp = new MailChimp($opts['api_key']);

            $params = apply_filters('mailchimp_subscribe_params', array_merge(array(
                'email' => array('email' => $email),
                'id' => $opts['list_id'],
            ), $this->get_subscribe_params()));

            $response = $mailchimp->call('lists/subscribe', $params);

        } catch(Exception $ex) {
            $message = __("Sorry! There was an error connecting to Mailchimp'", THEMENAME);
        }

        if (isset($response['email'])) {
            $success = true;
        } else {
            $message = $response['error'];
        }

        if (!$success && empty($message)) {

            $message = __("Sorry! There was an error in the newsletter configuration, contact the website admin", THEMENAME);

        }

        echo json_encode(array('success' => $success, 'message' => $message));

        die();
    }

    /**
     * get_subscribe_params
     *
     * Override class to add custom params
     */
    protected function get_subscribe_params() {

        $params = array ();

        $firstname = filter_input(INPUT_POST, 'firstname',FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname',FILTER_SANITIZE_STRING);

        if ($firstname) {
            $params['merge_vars'] = array('FNAME' => $firstname);
        }

        if ($lastname) {
            if (isset($params['merge_vars'])) {
                $params['merge_vars']['LNAME'] = $lastname;
            }
            else {
                $params['merge_vars'] = array('LNAME' => $lastname);
            }
        }

        return $params;
    }
}

/**
 * Super-simple, minimum abstraction MailChimp API v2 wrapper
 *
 * Uses curl if available, falls back to file_get_contents and HTTP stream.
 * This probably has more comments than code.
 *
 * Contributors:
 * Michael Minor <me@pixelbacon.com>
 * Lorna Jane Mitchell, github.com/lornajane
 *
 * @author Drew McLellan <drew.mclellan@gmail.com>
 * @version 1.1.1
 */
class MailChimp
{
    private $api_key;
    private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0';
    private $verify_ssl   = false;

    /**
     * Create a new instance
     * @param string $api_key Your MailChimp API key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        list(, $datacentre) = explode('-', $this->api_key);
        $this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
    }

    /**
     * Call an API method. Every request needs the API key, so that is added automatically -- you don't need to pass it in.
     * @param  string $method The API method to call, e.g. 'lists/list'
     * @param  array  $args   An array of arguments to pass to the method. Will be json-encoded for you.
     * @return array          Associative array of json decoded API response.
     */
    public function call($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest($method, $args, $timeout);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting
     * @param  string $method The API method to be called
     * @param  array  $args   Assoc array of parameters to be passed
     * @return array          Assoc array of decoded result
     */
    private function makeRequest($method, $args = array(), $timeout = 10)
    {
        $args['apikey'] = $this->api_key;

        $url = $this->api_endpoint.'/'.$method.'.json';
        $json_data = json_encode($args);

        if (function_exists('curl_init') && function_exists('curl_setopt')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $result    = file_get_contents($url, null, stream_context_create(array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'user_agent'       => 'PHP-MCAPI/2.0',
                    'method'           => 'POST',
                    'header'           => "Content-type: application/json\r\n".
                        "Connection: close\r\n" .
                        "Content-length: " . strlen($json_data) . "\r\n",
                    'content'          => $json_data,
                ),
            )));
        }

        return $result ? json_decode($result, true) : false;
    }
}

register_widget('PressGang\Widget\MailchimpSignup');