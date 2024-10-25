<?php
    include "admindinhphatdat/database/database.php";
    include "admindinhphatdat/database/settings.php";
    include "admindinhphatdat/database/contact.php";
    include "admindinhphatdat/database/blogcategories.php";
    include "admindinhphatdat/database/sliders.php";
    include "admindinhphatdat/database/socials.php";
    include "admindinhphatdat/database/about.php";
    include "admindinhphatdat/database/blogs.php";
    include "admindinhphatdat/database/users.php";
    $database = new database();
    $db = $database->connect();

    $blogs = new blogs($db);
    $stmt_blogs = $blogs->readAll();
    $blogcategories = new blogcategories($db);
    $stmt_blogcategories = $blogcategories->readAll();
    $settings = new settings($db);
    $settings->id = 1;
    $settings->read();
    $socials = new socials($db);
    $stmt_socials = $socials->readAll();

    $about = new about($db);
    $about->id = 1;
    $about->read();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php  echo $settings->site_name ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="./includes/css.css" />
    <link rel="icon" href="<?php echo "./images/".$settings->site_favicon ?>"  type="image/png">
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    
    <style>
    .overlay-content{
    position: absolute;
    color:#f2849e !important;
    font-weight: 700;
    font-size: 12px;
    width: 100%;
    display: flex; 
    justify-content: space-around;
    margin-top: 8px;
}
    </style>
</head>
<body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header -->
      <?php
          include "includes/header.php";
      ?>
        <!-- Main -->
        <div id="main">
           <?php
               include "includes/sliders.php";
           ?>
            <br>
            <br>
            <div class="inner">
                <?php
               include "category_content.php";
           ?>
            </div>
        </div>

        <!-- Footer -->
        <?php
            include "includes/footer.php";
        ?>
    </div>
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
