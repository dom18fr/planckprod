Drupal.behaviors.planck_sticky = {
    attach: function (context, settings) {
        var sticky = new Sticky('[data-sticky]', {stickyClass: 'is-sticky'});
    }
};
