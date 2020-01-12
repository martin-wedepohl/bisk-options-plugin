jQuery(document).ready(function($) {
    $("body").on("click", ".click-child", function() {
        var href = $(this).find("a").attr('href');
        window.location = href;
    });
});
