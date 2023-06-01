(function ($, Drupal, once) {
  Drupal.behaviors.myBehavior = {
    attach: function (context, settings) {
      const el = document.querySelector('.show-class', context);
      const bodyClasses = document.body.classList;

      el.innerHTML = bodyClasses;
    }
  };
})(jQuery, Drupal, once);