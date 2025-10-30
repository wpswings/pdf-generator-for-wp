(function ($) {
  "use strict";

  /**
   * All of the code for your common JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  jQuery(document).ready(function ($) {
    // user email submittion from modal on creating single PDF.
    $("#pgfw-submit-email-user").click(function (e) {
      e.preventDefault();
      var post_id = $("#pgfw_current_post_id").data("post-id");
      var email = $("#pgfw-user-email-input").val();
      var use_account_email = $("#pgfw-user-email-from-account").is(":checked");
      $("#pgfw-user-email-submittion-message").html(
        pgfw_common_param.processing_html
      );
      $.ajax({
        url: pgfw_common_param.ajaxurl,
        method: "post",
        data: {
          action: "wps_pgfw_ajax_for_single_pdf_mail",
          nonce: pgfw_common_param.nonce,
          name: "single_pdf_mail",
          email: use_account_email ? "use_account_email" : email,
          post_id: post_id,
        },
        success: function (msg) {
          $("#pgfw-user-email-submittion-message").html(msg);
          setTimeout(function () {
            location.reload();
          }, 5000);
        },
        error: function () {
          $("#pgfw-user-email-submittion-message").html(
            pgfw_common_param.email_submit_error
          );
        },
      });
    });
  });
  $(document).on("click", ".flipbook-open-btn", function (e) {
    var modalItem = $(".flipbook-modal");

    if ($(".page>.flipbook-modal").length === 0) {
      var modalItemDetach = modalItem.detach();
      $(".page").prepend(modalItemDetach);
    }
  });

  $(document).ready(function() {
    // Get the width attribute value of the canvas
    const canvasWidth = $('.stf__canvas').attr('width');

    if (canvasWidth) {
        // Apply it as max-width (with px unit)
        $('.flipbook-wrap').css('max-width', canvasWidth + 'px');
        console.log("Max width set to:", canvasWidth + "px");
    } else {
        console.warn("Canvas width not found!");
    }
});

})(jQuery);
