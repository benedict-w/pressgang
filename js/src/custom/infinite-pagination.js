/**
 * InfinitePagination
 *
 * replaces the standard pagination links with 'infinite pagination', and calls the AJAX handler to load the posts
 * when the user scrolls to the bottom of the page.
 *
 */
(function($) {

    $(document).ready(function($) {

        $('.page-numbers').remove(); // remove the standard page links

        var page = 2;
        var fetched_all = false;
        var selector = '.infinite-container';

        var $spinner = $('<div id="infinite-pagination-spinner" class="spinner"></div>');

        $('.infinite-container:last-of-type').after($spinner);

        $(window).scroll(function() {

            $spinner = $('#infinite-pagination-spinner');

            if  ($(window).scrollTop() >= $(document).height() - $(window).height() - $('#footer').outerHeight()) {

                if (!fetched_all) { // keep going until we have all results

                    $spinner.fadeIn(250);

                    var data = {};

                    // get any terms from the search query
                    var query = window.location.search.replace('?', '').split('&');
                    for(var i in query){
                        data[query[i].split('=')[0]] = query[i].split('=')[1];
                    }

                    // get any terms from the search form if available
                    $($('#searchform').serializeArray()).each(function(index, obj){
                        data[obj.name] = obj.value;
                    });

                    data['page_no'] = page;

                    // from wp_localize_script
                    data['action'] = infinite_pagination.action;
                    data['_ajax_nonce'] = infinite_pagination._ajax_nonce;
                    data['template'] = infinite_pagination.template;
                    data['post_type'] = infinite_pagination.post_type;

                    $.ajaxq('infinite-pagination-queue', {
                        url: "/wp-admin/admin-ajax.php",
                        type: 'POST',
                        data: $.param(data),
                        success: function (html) {

                            $spinner.hide();

                            fetched_all = !html;

                            if (!fetched_all) {

                                $html = $(selector, $.parseHTML(html));
                                $(selector + ':last').after($html);

                            } else {

                                $.ajaxq.abort('infinite-pagination-queue');

                            }
                        }
                    });

                    page++;
                }
            }
        });
    });

})(jQuery);



