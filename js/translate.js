$(document).ready(function gt_jquery_ready() {
  if (!window.jQuery || !jQuery.fn.click)
    return setTimeout(gt_jquery_ready, 20);
  jQuery(".switcher .selected").click(function () {
    jQuery(".switcher .option a img").each(function () {
      if (!jQuery(this)[0].hasAttribute("src"))
        jQuery(this).attr("src", jQuery(this).attr("data-gt-lazy-src"));
    });
    if (!jQuery(".switcher .option").is(":visible")) {
      jQuery(".switcher .option").stop(true, true).delay(100).slideDown(500);
      jQuery(".switcher .selected a").toggleClass("open");
    }
  });
  jQuery(".switcher .option").bind("mousewheel", function (e) {
    var options = jQuery(".switcher .option");
    if (options.is(":visible"))
      options.scrollTop(options.scrollTop() - e.originalEvent.wheelDelta / 10);
    return false;
  });
  jQuery("body")
    .not(".switcher")
    .click(function (e) {
      if (
        jQuery(".switcher .option").is(":visible") &&
        e.target != jQuery(".switcher .option").get(0)
      ) {
        jQuery(".switcher .option").stop(true, true).delay(100).slideUp(500);
        jQuery(".switcher .selected a").toggleClass("open");
      }
    });
});
