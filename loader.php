<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Create and add loader to website</title>
  <link rel="stylesheet" href="./static/css/load.css">
</head>

<body>
  <div class="content">
    <img src="https://picsum.photos/300/300/?random" />
  </div>

  <div class="loader-wrapper">
    <span class="loader"><span class="loader-inner"></span></span>
  </div>

  <script>
    $(window).on("load", function() {
      $(".loader-wrapper").fadeOut("slow");
    });
  </script>
</body>

</html>