/**
 * InfinitePagination
 *
 * replaces the standard pagination links with 'infinite pagination', and calls the AJAX handler to load the posts
 * when the user scrolls to the bottom of the page.
 *
 */
(function ($) {

    $(document).ready(function ($) {

        if (typeof (infinite_pagination) !== 'undefined') {

            var $spinner = $('<div id="infinite-pagination-spinner" class="spinner"></div>');

            $('.page-numbers, .pagination').remove(); // remove the standard page links

            var fetched_all = false;
            var selector = '.infinite-container';

            // set access global
            infinite_pagination.page = 2;
            infinite_pagination.fetched_all = false;

            var $container = $(selector + ':last');

            $container.after($spinner);
            $spinner = $('#infinite-pagination-spinner');

            var animate_queue = [];
            var animate_interval = false;

            $(window).scroll(function () {

                if (($(window).scrollTop() >= $(document).height() - $(window).height() - $('#footer').outerHeight()) && !$.ajaxq.isRunning(['infinite-pagination-queue'])) {

                    if (!infinite_pagination.fetched_all) { // keep going until we have all results

                        $spinner.show();

                        var data = {};

                        // get any terms from the search query
                        var query = window.location.search.replace('?', '').split('&');
                        for (var i in query) {
                            data[query[i].split('=')[0]] = query[i].split('=')[1];
                        }

                        // get any terms from the search form if available
                        $($('#searchform').serializeArray()).each(function (index, obj) {
                            data[obj.name] = obj.value;
                        });

                        data['page_no'] = infinite_pagination.page;

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

                                infinite_pagination.fetched_all = !html;

                                if (!infinite_pagination.fetched_all) {

                                    var $html = $(selector, $.parseHTML(html));

                                    infinite_pagination.fetched_all = $html.children().length < infinite_pagination.posts_per_page;

                                    var $items = $html.children();

                                    $items.css('display', 'none');
                                    $items.appendTo($container);

                                    // if there are images fadein only after fully loaded
                                    if ($items.find('img').length) {
                                        $items.imagesLoaded(function () {
                                            $items.each(function (i) {
                                                var $item = $(this);
                                                animate_queue.push(function () {
                                                    $item.fadeIn(100);
                                                });
                                            });
                                        });
                                    } else {
                                        $items.each(function (i) {
                                            var $item = $(this);
                                            animate_queue.push(function () {
                                                $item.fadeIn(100);
                                            });
                                        });
                                    }

                                    if (!animate_interval) {
                                        animate_interval = setInterval(function () {
                                            if (animate_queue.length) {
                                                animate_queue.shift()();
                                            } else {
                                                clearInterval(animate_interval);
                                                animate_interval = false;
                                            }
                                        }, 150);
                                    }

                                } else {

                                    $.ajaxq.abort('infinite-pagination-queue');

                                }
                            },
                            complete: function () {
                                $spinner.hide();
                            }
                        });

                        infinite_pagination.page++;
                    }
                }
            });
        }
    });

})(jQuery);



