(function ($) {
  "use strict";

  $(document).on("click", ".flipbook-open-btn", function (e) {
    e.preventDefault();
    const targetSelector = $(this).data("target");
    const $modal = $(targetSelector);

    if (!$modal.length) {
      return;
    }

    if ($modal.parent().length && !$modal.parent().is("body")) {
      $modal.detach().appendTo("body");
    }

    $modal.attr("aria-hidden", "false").addClass("is-active");
  });

  $(document).on("click", ".flipbook-modal [data-close]", function () {
    $(this)
      .closest(".flipbook-modal")
      .attr("aria-hidden", "true")
      .removeClass("is-active");
  });

  $(function () {
    const canvasWidth = $(".stf__canvas").attr("width");

    if (canvasWidth) {
      $(".flipbook-wrap").css("max-width", canvasWidth + "px");
    }
  });
})(jQuery);
