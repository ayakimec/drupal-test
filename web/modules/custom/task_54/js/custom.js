(function (Drupal) {
  Drupal.behaviors.alertMessage = {
    attach: function (context, settings) {
      alert('My Message!');
    }
  }
})(Drupal);