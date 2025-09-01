(function ($) {
    "use strict";

    window.DirataUtils = {
        showLoader: function () {
            $("#preloader").fadeIn(200);
        },

        hideLoader: function () {
            $("#preloader").fadeOut(200);
        }
    };

})(jQuery);