jQuery(document).ready(function () {
    jQuery(".mb_elegantModal").delay(5000).fadeIn("slow", function () {
        jQuery("div", this).fadeIn("slow");
        jQuery("div", this).addClass("swing");

        var txt = "Enter Your Email ID Here..";
        var timeOut;
        var txtLen = txt.length;
        var char = 0;
        jQuery("[name='email']").attr("placeholder", "|");
        setTimeout(function () {
            (function typeIt() {
                var humanize = Math.round(Math.random() * (200 - 30)) + 30;
                timeOut = setTimeout(function () {
                    char++;
                    var type = txt.substring(0, char);
                    jQuery("[name='email']").attr("placeholder", type + "|");
                    typeIt();

                    if (char == txtLen) {
                        jQuery("[name='email']").attr(
                            "placeholder",
                            jQuery("[name='email']").attr("placeholder").slice(0, -1)
                        ); // remove the '|'
                        clearTimeout(timeOut);
                    }
                }, humanize);
            })();
        }, 1200);
    });
});


jQuery(document).ready(function ($) {
    jQuery(".mb_elegantModalclose").bind("click", function () {
        jQuery(".mb_elegantModal div").addClass("zoomOutDown");
        setTimeout(function () {
            jQuery(".mb_elegantModal").fadeOut("1000", function () {
                jQuery(this).css({"display": "none"});
            })
        }, 1000);
    });
    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (emailReg.test($email)) {
            return true;
        } else {
            return false;
        }
    }
});