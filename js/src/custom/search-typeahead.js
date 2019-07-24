/**
 * Search Typeahead via AJAX using typeahead.js
 *
 * See - http://twitter.github.io/typeahead.js/examples/
 */
(function($) {

    $(function() {

        var engine = new Bloodhound({
            datumTokenizer: function (datum) {
                return Bloodhound.tokenizers.whitespace(datum.id);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: search_typeahead.url + '?s=%QUERY&action=' + search_typeahead.action,
                wildcard: '%QUERY'
            }
        });

        engine.initialize();

        $('#s').typeahead({
            hint: true,
            highlight: true,
            minLength: 2
        }, {
            name: 'engine',
            displayKey: 'title',
            source: engine.ttAdapter(),
            templates: {
                suggestion: function(data){
                    return '<a href="' + data.link + '">' + data.title + '</a>';
                }
            }
        });

        // fix google search console duplicate crawl error
        $('.tt-hint').removeAttr("itemprop");

    });

})(jQuery);