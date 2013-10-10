$(document).ready(function() {

    //////////////////////////////////////////
    ///// HOME PAGE - POINTS TAB SECTION /////
    //////////////////////////////////////////

    var initTab = window.location.hash;

    /*** Bind click event to all tab links - Open target tab ***/
    $('.point-tabs a').click(function() {
        $(this).tab('show');
    });

    /*** Bind click event to sidebar tab links - Open target tab ***/
    $('.tab-link').click(function(e) {
        var hash    = $(this).attr('href').replace('/','');
        var $target = $('.point-tabs a[href='+hash+']');

        if($target.length) {
            e.preventDefault();
        }

        $target.tab('show');
    });

    /*** Clear the location hash if any after opening tab ***/
    $('a[data-toggle="tab"]').on('shown', function (e) {
        window.location.hash = '';
    });

    /*** Open initial tab ***/
    if(!initTab) {
        $('.point-tabs a:first').tab('show');
    } else {a = initTab;
        $('.point-tabs a[href='+initTab+']').tab('show');
    }

    //////////////////////////////////////////////
    ///// VIEW POINT PAGE - COMMENTS SECTION /////
    //////////////////////////////////////////////

    /*** Bind click event to all like links - Load response via AJAX ***/
    $('.comments-wrap').on('click', '.likes a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        addSpinner($('.comments'));

        $.get(url, function(data) {
            var html = $(data).html();

            $('.comments-wrap').html(html);
            removeSpinner()
        });
    });

    /*** Bind click event to comment form submit - Load response via AJAX ***/
    $('.comments-wrap').on('submit', '#commentsform', function(e) {
        e.preventDefault();

        var url  = $(this).attr('action');
        var data = $(this).serialize();

        addSpinner($('.comments'));

        $.post(url, data, function(data) {
            var html = $(data).html();

            $('.comments-wrap').html(html);
            removeSpinner()
        });
    });

    /////////////////////////////////////////////
    ///// VIEW USER PAGE - COMMENTS SECTION /////
    /////////////////////////////////////////////

    /*** Bind click event to all tab links - Open target tab ***/
    $('.point-filters a').click(function() {
        var filter = $(this).attr('filter');

        $(this).tab('show');
        $('.point.hidden').removeClass('hidden');

        if(filter !== 'all') {
            $('.point:not(.' + filter + ')').addClass('hidden');
        }
    });

    $('.point-filters a:first').tab('show');


    $('#event-date').datepicker({ dateFormat: "yy-mm-dd" });
});

/**
 * Add the loading class & spinner to target element.
 *
 * @param elem HTMLElement
 * @return void
 */
function addSpinner(elem) {
    $(elem).addClass('loading');
    $(elem).parent().prepend('<div class="spinner"></div>');
}

/**
 * Remove all spinners from the page
 *
 * @return void
 */
function removeSpinner() {
    $('.spinner').remove();
}