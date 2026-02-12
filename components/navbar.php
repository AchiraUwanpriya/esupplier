<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link rel="shortcut icon" href="./static/img/9.png" />

    <title>eSupplier-CDPLC</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" crossorigin="anonymous" /> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

    <link href="./static/css/app.css" rel="stylesheet" />
    <link href="./static/css/main.css" rel="stylesheet" />
    <link href="./static/css/translate.css" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand navbar-light navbar-bg">
        <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
        </a>
        <a style="color: blue; font-weight: bolder; text-decoration: none">
            <b>HELLO
                <?php echo $_SESSION['sup_name'] ?>! WELCOME TO eSupplier-CDPLC!</b>
        </a>

        <!-- timer -->
        <div id="timer" hidden></div>


        <div id="google_translate_element2"></div>

        <div class="navbar-collapse collapse">

            <ul class="navbar-nav navbar-align">
                <div class="nav-link d-none d-sm-inline-block">
                    <div class="switcher notranslate">
                        <div class="selected">
                            <a href="#" onclick="return false;"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_United_Kingdom.png/800px-Flag_of_the_United_Kingdom.png?20080216232030" height="24" width="24" alt="en" />
                                English</a>
                        </div>
                        <div class="option">
                            <a href="#" onclick="doGTranslate('en|en');jQuery('div.switcher div.selected a').html(jQuery(this).html());return false;" title="English" class="nturl selected"><img data-gt-lazy-src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Flag_of_the_United_Kingdom.png/800px-Flag_of_the_United_Kingdom.png?20080216232030" height="24" width="24" alt="en" />
                                English</a>
                            <a href="#" onclick="doGTranslate('en|si');jQuery('div.switcher div.selected a').html(jQuery(this).html());return false;" title="සිංහල" class="nturl"><img data-gt-lazy-src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Flag_of_Sri_Lanka.svg/800px-Flag_of_Sri_Lanka.svg.png?20221128224909" height="24" width="24" alt="si" />
                                සිංහල</a>
                            <a href="#" onclick="doGTranslate('en|ta');jQuery('div.switcher div.selected a').html(jQuery(this).html());return false;" title="தமிழ்" class="nturl"><img data-gt-lazy-src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Flag_of_India.png/1024px-Flag_of_India.png" height="24" width="16" alt="ta" />
                                தமிழ்</a>
                        </div>
                    </div>
                </div>
                <li class="nav-item dropdown">




                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img src="./static/img/avatars/avatar1.jpg" class="avatar img-fluid rounded me-1" alt="" />
                        <span class="text-dark"><?php echo $_SESSION['sup_name'] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="profile.php"><i class="align-middle me-1" data-feather="user"></i>
                            Profile</a>
                        <!-- <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" onclick="logoutfunction()">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>


    <!-- Logout js -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script>
        function googleTranslateElementInit2() {
            new google.translate.TranslateElement({
                    pageLanguage: "en",
                    autoDisplay: false,
                },
                "google_translate_element2"
            );
        }
        if (!window.gt_translate_script) {
            window.gt_translate_script = document.createElement("script");
            gt_translate_script.src =
                "https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2";
            document.body.appendChild(gt_translate_script);
        }
    </script>
    <script>
        function logoutfunction() {
            let text = "Please Confirm To Logout!!";
            if (confirm(text) == true) {
                window.location = "logout.php";
            }
        }
    </script>
    <style>
        div.skiptranslate,
        #google_translate_element2 {
            display: none !important;
        }

        body {
            top: 0 !important;
        }
    </style>
    <script>
        function GTranslateGetCurrentLang() {
            var keyValue = document["cookie"].match(
                "(^|;) ?googtrans=([^;]*)(;|$)"
            );
            return keyValue ? keyValue[2].split("/")[2] : null;
        }

        function GTranslateFireEvent(element, event) {
            try {
                if (document.createEventObject) {
                    var evt = document.createEventObject();
                    element.fireEvent("on" + event, evt);
                } else {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent(event, true, true);
                    element.dispatchEvent(evt);
                }
            } catch (e) {}
        }

        function doGTranslate(lang_pair) {
            if (lang_pair.value) lang_pair = lang_pair.value;
            if (lang_pair == "") return;
            var lang = lang_pair.split("|")[1];
            if (
                GTranslateGetCurrentLang() == null &&
                lang == lang_pair.split("|")[0]
            )
                return;
            if (typeof ga == "function") {
                ga(
                    "send",
                    "event",
                    "GTranslate",
                    lang,
                    location.hostname + location.pathname + location.search
                );
            }
            var teCombo;
            var sel = document.getElementsByTagName("select");
            for (var i = 0; i < sel.length; i++)
                if (sel[i].className.indexOf("goog-te-combo") != -1) {
                    teCombo = sel[i];
                    break;
                }
            if (
                document.getElementById("google_translate_element2") == null ||
                document.getElementById("google_translate_element2").innerHTML
                .length == 0 ||
                teCombo.length == 0 ||
                teCombo.innerHTML.length == 0
            ) {
                setTimeout(function() {
                    doGTranslate(lang_pair);
                }, 500);
            } else {
                teCombo.value = lang;
                GTranslateFireEvent(teCombo, "change");
                GTranslateFireEvent(teCombo, "change");
            }
        }
        (function gt_jquery_ready() {
            if (!window.jQuery || !jQuery.fn.click)
                return setTimeout(gt_jquery_ready, 20);
            if (GTranslateGetCurrentLang() != null)
                jQuery(document).ready(function() {
                    var lang_html = jQuery("div.switcher div.option")
                        .find('img[alt="' + GTranslateGetCurrentLang() + '"]')
                        .parent()
                        .html();
                    if (typeof lang_html != "undefined")
                        jQuery("div.switcher div.selected a").html(
                            lang_html.replace("data-gt-lazy-", "")
                        );
                });
        })();

        function GTranslateGetCurrentLang() {
            var keyValue = document["cookie"].match(
                "(^|;) ?googtrans=([^;]*)(;|$)"
            );
            return keyValue ? keyValue[2].split("/")[2] : null;
        }

        function GTranslateFireEvent(element, event) {
            try {
                if (document.createEventObject) {
                    var evt = document.createEventObject();
                    element.fireEvent("on" + event, evt);
                } else {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent(event, true, true);
                    element.dispatchEvent(evt);
                }
            } catch (e) {}
        }

        function doGTranslate(lang_pair) {
            if (lang_pair.value) lang_pair = lang_pair.value;
            if (lang_pair == "") return;
            var lang = lang_pair.split("|")[1];
            if (
                GTranslateGetCurrentLang() == null &&
                lang == lang_pair.split("|")[0]
            )
                return;
            if (typeof ga == "function") {
                ga(
                    "send",
                    "event",
                    "GTranslate",
                    lang,
                    location.hostname + location.pathname + location.search
                );
            }
            var teCombo;
            var sel = document.getElementsByTagName("select");
            for (var i = 0; i < sel.length; i++)
                if (sel[i].className.indexOf("goog-te-combo") != -1) {
                    teCombo = sel[i];
                    break;
                }
            if (
                document.getElementById("google_translate_element2") == null ||
                document.getElementById("google_translate_element2").innerHTML
                .length == 0 ||
                teCombo.length == 0 ||
                teCombo.innerHTML.length == 0
            ) {
                setTimeout(function() {
                    doGTranslate(lang_pair);
                }, 500);
            } else {
                teCombo.value = lang;
                GTranslateFireEvent(teCombo, "change");
                GTranslateFireEvent(teCombo, "change");
            }
        }
        (function gt_jquery_ready() {
            if (!window.jQuery || !jQuery.fn.click)
                return setTimeout(gt_jquery_ready, 20);
            if (GTranslateGetCurrentLang() != null)
                jQuery(document).ready(function() {
                    var lang_html = jQuery("div.switcher div.option")
                        .find('img[alt="' + GTranslateGetCurrentLang() + '"]')
                        .parent()
                        .html();
                    if (typeof lang_html != "undefined")
                        jQuery("div.switcher div.selected a").html(
                            lang_html.replace("data-gt-lazy-", "")
                        );
                });
        })();

        function GTranslateGetCurrentLang() {
            var keyValue = document["cookie"].match(
                "(^|;) ?googtrans=([^;]*)(;|$)"
            );
            return keyValue ? keyValue[2].split("/")[2] : null;
        }

        function GTranslateFireEvent(element, event) {
            try {
                if (document.createEventObject) {
                    var evt = document.createEventObject();
                    element.fireEvent("on" + event, evt);
                } else {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent(event, true, true);
                    element.dispatchEvent(evt);
                }
            } catch (e) {}
        }

        function doGTranslate(lang_pair) {
            if (lang_pair.value) lang_pair = lang_pair.value;
            if (lang_pair == "") return;
            var lang = lang_pair.split("|")[1];
            if (
                GTranslateGetCurrentLang() == null &&
                lang == lang_pair.split("|")[0]
            )
                return;
            if (typeof ga == "function") {
                ga(
                    "send",
                    "event",
                    "GTranslate",
                    lang,
                    location.hostname + location.pathname + location.search
                );
            }
            var teCombo;
            var sel = document.getElementsByTagName("select");
            for (var i = 0; i < sel.length; i++)
                if (sel[i].className.indexOf("goog-te-combo") != -1) {
                    teCombo = sel[i];
                    break;
                }
            if (
                document.getElementById("google_translate_element2") == null ||
                document.getElementById("google_translate_element2").innerHTML
                .length == 0 ||
                teCombo.length == 0 ||
                teCombo.innerHTML.length == 0
            ) {
                setTimeout(function() {
                    doGTranslate(lang_pair);
                }, 500);
            } else {
                teCombo.value = lang;
                GTranslateFireEvent(teCombo, "change");
                GTranslateFireEvent(teCombo, "change");
            }
        }
        (function gt_jquery_ready() {
            if (!window.jQuery || !jQuery.fn.click)
                return setTimeout(gt_jquery_ready, 20);
            if (GTranslateGetCurrentLang() != null)
                jQuery(document).ready(function() {
                    var lang_html = jQuery("div.switcher div.option")
                        .find('img[alt="' + GTranslateGetCurrentLang() + '"]')
                        .parent()
                        .html();
                    if (typeof lang_html != "undefined")
                        jQuery("div.switcher div.selected a").html(
                            lang_html.replace("data-gt-lazy-", "")
                        );
                });
        })();
    </script>
</body>

</html>