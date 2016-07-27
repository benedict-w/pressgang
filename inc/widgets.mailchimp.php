<?php

namespace PressGang;

/**
 * Plugin Name: Mailchimp Embed
 * Description: A simple widget that embeds a Mailchimp Signup form
 * Version: 0.1
 * Author: Ben Wallis
 * Author URI: http://www.benedict-wallis.com
 *
 */
class MailchimpSignupWidget extends \WP_Widget {

    /**
     * Constructor
     *
     */
    public function __construct() {

        $widget_ops = array(
            'classname' => 'mailchimp-signup-widget',
            'description' => __('A simple widget that embeds a Mailchimp signup form', THEMENAME)
        );

        $control_ops = array(
            'id_base' => 'mailchimp-signup-widget'
        );

        add_action('wp_ajax_mailchimp_signup', array('Enpact\MailchimpSignupWidget', 'signup'));

        parent::__construct('mailchimp-signup-widget', __("Mailchimp Signup", THEMENAME), $widget_ops, $control_ops);
    }

    /**
     * widget
     *
     * @param array $args
     * @param array $instance
     */
    function widget($args, $instance) {

        extract( $args );

        // widget heading
        $title = apply_filters('widget_title', $instance['title'] );

        ?>

        <?php echo $before_widget; ?>
        <div id="mailchimp">
            <?php if ($title) : ?>
                <h4><?php echo $before_title . $title . $after_title; ?></h4>
            <?php endif; ?>
            <form id="mailchimp-signup">
                <div class="form-group">
                    <label for="mailchimp-email" class="sr-only"><?php echo __("E-Mail", THEMENAME); ?></label>
                    <input id="mailchimp-email" name="email" type="email" class="form-control" placeholder="<?php echo __("Newsletter Signup", THEMENAME); ?>" required>
                    <button type="submit" class="btn"><?php echo __("Signup", THEMENAME); ?></button>
                </div>
                <p class="alert alert-success" style="display: none;"><?php echo __("Thanks! You are now subscribed to the newsletter.", THEMENAME); ?></p>
                <p class="alert alert-danger" style="display: none;"><?php echo __("Sorry! There was a problem subscribing.", THEMENAME); ?></p>
            </form>
        </div>
        <?php echo $after_widget;
    }

    /**
     * Update Widget
     *
     * Saves the widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        // strip tags from title and name to remove HTML
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['api_key'] = strip_tags($new_instance['api_key']);
        $instance['list_id'] = strip_tags($new_instance['list_id']);

        return $instance;
    }

    /**
     * Form
     *
     * @param array $instance
     * @return string|void
     */
    function form( $instance ) {

        // default widget settings
        $defaults = array(
            'title' => __("Newsletter", THEMENAME),
            'api_key' => '',
            'list_id' => '',
        );

        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <!-- Widget Title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', THEMENAME); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <!-- API Key -->
        <p>
            <label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e('API Key:', THEMENAME); ?></label>
            <input id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" value="<?php echo $instance['api_key']; ?>" style="width:100%;" />
        </p>

        <!-- List ID -->
        <p>
            <label for="<?php echo $this->get_field_id('list_id'); ?>"><?php _e('List ID:', THEMENAME); ?></label>
            <input id="<?php echo $this->get_field_id('list_id'); ?>" name="<?php echo $this->get_field_name('list_id'); ?>" value="<?php echo $instance['list_id']; ?>" style="width:100%;" />
        </p>

    <?php
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

            $response = $mailchimp->call('lists/subscribe', array(
                'email' => array('email' => $email),
                'id' => $opts['list_id'],
            ));

        } catch(Exception $ex) {
            $message = __("Sorry! There was an error connecting to Mailchimp'", THEMENAME);
        }

        if (isset($response['email'])) {
            $success = true;
        } else {
            $message = $response['error'];
        }

        echo json_encode(array('success' => $success, 'message' => $message));

        die();
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

register_widget('PressGang\MailchimpSignupWidget');