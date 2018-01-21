<?php

namespace Pressgang;

class AdminTaxonomyFilter
{

    /**
     * AdminTaxonomyFilter constructor.
     */
    public function __construct() {
        add_action('restrict_manage_posts', array($this, 'admin_taxonomy_filter'));
    }

    /**
     * admin_taxonomy_filters
     */
    public function admin_taxonomy_filter() {

        global $typenow;

        $taxonomies = apply_filters('admin_taxonomy_filters', get_taxonomies(array(
            'public'   => true,
            // 'object' => $typenow,
            '_builtin' => false,
        ), 'objects'));

        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms($taxonomy->name);
            if (count($terms) > 0) : ?>
                <select name="<?php echo $taxonomy->name; ?>" id="<?php echo $taxonomy->name; ?>" class="postform">
                    <option value=""><?php echo __(sprintf("Show All %s", $taxonomy->labels->name)); ?></option>
                    <?php foreach ($terms as $term) : ?>
                        <option value="<?php echo $term->slug; ?>" <?php echo(filter_input(INPUT_GET, $taxonomy->name, FILTER_SANITIZE_STRING) === $term->slug ? "selected=\"selected\"" : ''); ?>>
                            <?php echo $term->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif;
        }
    }
}

new AdminTaxonomyFilter();
