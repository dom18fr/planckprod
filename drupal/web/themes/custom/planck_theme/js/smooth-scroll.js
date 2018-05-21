Drupal.behaviors.planck_smooth_scroll = {
    attach: function (context, settings) {
        var scroll = new SmoothScroll('a[href*="#"]');
    }
};