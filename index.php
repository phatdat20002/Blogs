<?php
include "admindinhphatdat/database/database.php";
include "admindinhphatdat/database/settings.php";
include "admindinhphatdat/database/contact.php";
include "admindinhphatdat/database/blogcategories.php";
include "admindinhphatdat/database/sliders.php";
include "admindinhphatdat/database/socials.php";
include "admindinhphatdat/database/about.php";
include "admindinhphatdat/database/subscribers.php";
include "admindinhphatdat/database/blogs.php";
include "admindinhphatdat/database/users.php";

$database = new database();
$db = $database->connect();

$blogs = new blogs($db);
$stmt_blogs = $blogs->readAll();
$all_blogs = $stmt_blogs->fetchAll();
$total_blogs = count($all_blogs);
$blogs_per_page = 6;
$num_pages = ceil($total_blogs / $blogs_per_page);



$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_index = ($current_page - 1) * $blogs_per_page;
$current_blogs = array_slice($all_blogs, $start_index, $blogs_per_page);

function nextPage($current_page, $num_pages) {
    return ($current_page < $num_pages) ? $current_page + 1 : false;
}
function previousPage($current_page) {
    return ($current_page > 1) ? $current_page - 1 : false;
}

$next_page = nextPage($current_page, $num_pages);
$previous_page = previousPage($current_page);

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

if(!empty($_GET['verify'])){
    $subscribers = new subscribers($db);
    $subscribers->verified_token = $_GET['verify'];
    $stmt_subscribers = $subscribers->checkRequestVerified();
    if($stmt_subscribers->rowCount() > 0){
        $row = $stmt_subscribers->fetch();
        $subscribers->status = 1;
        $subscribers->verified_token = "verified";
        $subscribers->email = $row['email'];
        $subscribers->updated_at = date("Y-m-d h:i:s", time());
        $subscribers->id = $row['id'];
        $subscribers->update();
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $settings->site_name ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="./includes/css.css" />
    <link rel="icon" href="<?php echo "./images/".$settings->site_favicon ?>"  type="image/png">
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- Header -->
        <?php include "includes/header.php"; ?>
        <!-- Main -->
        <div id="main">
            <?php include "includes/sliders.php"; ?>
            <br>
            <br>
            <div class="inner">
                <?php include "includes/content.php"; ?>
            </div>
        </div>
        <!-- Footer -->
        <?php include "includes/footer.php"; ?>
    </div>
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
