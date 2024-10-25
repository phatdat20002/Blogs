<?php
    include "admindinhphatdat/database/database.php";
    include "admindinhphatdat/database/settings.php";
    include "admindinhphatdat/database/contact.php";
    include "admindinhphatdat/database/blogcategories.php";
    include "admindinhphatdat/database/sliders.php";
    include "admindinhphatdat/database/socials.php";
    include "admindinhphatdat/database/about.php";
    $database = new database();
    $db = $database->connect();
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
	
    $contact = new contact($db);
    $contact->id = 1;
    $contact->read();

	if(!empty($_GET['verify'])){
		$subscribers = new subscribers($db);
		$subscribers->verified_token = $_GET['verify'];
		$stmt_subscribers = $subscribers->checkRequestVerified();
		/*Verification matched*/
		if($stmt_subscribers->rowCount()>0){
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
		<title><?php  echo $settings->site_name ?></title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./includes/css.css" />
		<link rel="icon" href="<?php echo "./images/".$settings->site_favicon ?>"  type="image/png">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
				<?php
					include "includes/header.php"
				?>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<h1>About Us</h1>

							<div class="image main">
								<img src="images/banner-image-1-1920x500.jpg" class="img-fluid" alt="" />
							</div>
								<?php
								   echo $about->content;
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