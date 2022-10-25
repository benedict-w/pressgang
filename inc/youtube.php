<?php

namespace Pressgang;

class YouTube {

    /**
     * constructor
     */
    public function __construct() {

        add_filter('timber/twig', function(\Twig_Environment $twig) {
            $twig->addFunction( new \Timber\Twig_Function('get_youtube_id_from_url', array($this, 'get_youtube_id_from_url')) );
            $twig->addFunction( new \Timber\Twig_Function('get_youtube_list_id_from_url', array($this, 'get_youtube_list_id_from_url')) );
            return $twig;
        });
    }

    /**
     * get_youtube_id_from_url
     *
     * add helper function to twig for getting youtube id
     *
     * @param $url
     * @return mixed
     */
    public function get_youtube_id_from_url($url) {
        $query_params = array();
        parse_str( parse_url($url, PHP_URL_QUERY),$query_params);
        return isset($query_params['v']) ? $query_params['v'] : null;
    }

    /**
     * get_youtube_list_id_from_url
     *
     * add helper function to twig for getting youtube id
     *
     * @param $url
     * @return mixed
     */
    public function get_youtube_list_id_from_url($url) {
        $query_params = array();
        parse_str( parse_url($url, PHP_URL_QUERY),$query_params);
        return isset($query_params['list']) ? $query_params['list'] : null;
    }

}

new YouTube();