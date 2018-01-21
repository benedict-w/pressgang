<?php

namespace PressGang;

require_once 'base-controller.php';

/**
 * Class CommentsController
 *
 * @package PressGang
 */
class CommentsController extends BaseController {

    protected $post;

    /**
     * __construct
     *
     * PageController constructor
     *
     * @param string $template
     */
    public function __construct($template = 'comments.twig')
    {
        parent::__construct($template);

        // allow comment replies, see https://codex.wordpress.org/Function_Reference/comment_reply_link
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * get_post
     *
     * @return mixed
     */
    protected function get_post()
    {
        if (empty($this->post)) {
            $this->post = \Timber::get_post();
        }

        return $this->post;
    }

    /**
     * get_context
     *
     * @return mixed
     */
    protected function get_context()
    {
        $this->context['post'] = $this->get_post();

        return $this->context;
    }
}