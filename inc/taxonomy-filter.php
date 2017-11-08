<?php

namespace Pressgang;

/**
 * Class TaxonomyFilter
 *
 * Using the default Wordpress Taxonomy filtering, this class provides twig addons for building the search form
 *
 * Further reading: https://thereforei.am/2011/10/28/advanced-taxonomy-queries-with-pretty-urls/
 *
 * @package Pressgang
 */
class TaxonomyFilter {

    protected $inputs = array();
    protected $taxonomies = array();

    /**
     * __get
     *
     * magic get the TimberTerms
     *
     * @param $taxonomy
     */
    public function __get($taxonomy) {
        if (in_array($taxonomy, $this->taxonomies)) {

            if (empty($this->$taxonomy)) {
                $this->$taxonomy = \Timber::get_terms(array(
                    'taxonomy' => array($taxonomy),
                ));
            }

            return $this->$taxonomy;
        }
    }

    /**
     * __constructor
     *
     */
    public function __construct() {

        // get the taxonomies to filter on
        $this->taxonomies = Config::get('filter-taxonomies');

        // ... if none then default to all registered taxonomies
        if (empty($this->taxonomies)) {
            $this->taxonomies = array_keys(Config::get('custom-taxonomies'));
            $this->taxonomies = array_merge($this->taxonomies, array('tag', 'category'));
        }

        foreach ($this->taxonomies as &$taxonomy) {
            // get the input terms to filter on
            $slugs = filter_input(INPUT_GET, $taxonomy, FILTER_DEFAULT);

            // store the inputs so the front-end form can remember the values
            $this->inputs[$taxonomy] = $slugs;
        }

        if (!empty($this->inputs)) {
            add_filter('timber_context', array($this, 'add_filter_inputs_to_context'));
        }

        // allow twig to lazy load lookups
        add_filter('get_twig', array($this, 'add_taxonomy_lookups_to_twig'), 100);


    }

    /**
     * add_filter_inputs_to_context
     *
     * The twig context will need to know the inputs so that the form can "remember" which values were selected.
     *
     */
    public function add_filter_inputs_to_context($context) {

        $context['inputs'] = $this->inputs;

        return $context;

    }

    /**
     * add_taxonomy_lookups_to_twig
     *
     * This allows the view to lazy load the taxonomies where needed without having to know before hand
     * which context to add them to.
     *
     */
    public function add_taxonomy_lookups_to_twig($twig) {

        $twig->addFunction('taxonomy_lookup', new \Twig_SimpleFunction('taxonomy_lookup', array($this, 'taxonomy_lookup')));

        return $twig;
    }

    /**
     * taxonomy_lookup
     *
     * @param $taxonomy
     */
    public function taxonomy_lookup($taxonomy) {
        return $this->$taxonomy;
    }

}

new TaxonomyFilter();