
$(function(){
  $('form:not(.js-allow-double-submission)').preventDoubleSubmission();
  var active = $('.sidebar-menu').find('.active').find('i');
  active.removeClass('far');
  active.addClass('fa');

  $('.datepicker').datepicker({
    format: "dd-M-yyyy"
    });
  $('input').prop('autocomplete','off')
});






// jQuery plugin to prevent double submission of forms
jQuery.fn.preventDoubleSubmission = function() {
  $(this).on('submit',function(e){
    var $form = $(this);
    if ($form.data('submitted') === true) {
      // Previously submitted - don't submit again
      e.preventDefault();
    } else {
      // Mark it so that the next submit can be ignored
      $form.data('submitted', true);
    }
  });
  // Keep chainability
  return this;
};