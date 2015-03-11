

(function(){
    var lib = {};
    lib.loading = function (selector, options) {

        var $button = $(options.button);
        var $selector = $(selector);

        $.each(options.bind, function (evt, callback) {
            $selector.bind(evt, function (e) {

                var dtd = $.Deferred();

                if ($selector.hasClass('ajax-loading')) {
                    e.preventDefault();
                    return false;
                }
                $selector.addClass('ajax-loading');
                $button.prop('disabled', true)
                    .append('<span class="loading-gif"></span>');

                dtd.done(function () {
                    $selector.removeClass('ajax-loading');
                    $button.prop('disabled', false)
                        .find('.loading-gif')
                            .remove();
                });

                callback.apply(this, [e, dtd]);
                
            });

        });
    };
    // };
    // lib.loading = function (options) {

    //     var $button = $(options.button);
    //     $button.bind('click', function () {

    //         if ($button.find('.ajax-loading').length === 0) {
    //             $button.prop('disabled', true)
    //                 .append('<span class="ajax-loading"> &nbsp; </span>');
    //             $.ajax({
    //                     url: options.url,
    //                     type: options.type || 'get',
    //                     data: typeof options.data === 'function' ? options.data() : options.data,
    //                     dataType: options.dataType || 'json'
    //                 })
    //                 .always(function () {
    //                     $button.prop('disabled', false)
    //                         .find('.ajax-loading')
    //                             .remove();
    //                 }) 
    //                 .done(options.done)
    //                 .fail(options.fail);
    //         }
    //     });

    // };


    window.lib = lib;

})(jQuery);
