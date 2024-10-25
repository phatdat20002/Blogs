<?php
    include "admindinhphatdat/database/database.php";
    include "admindinhphatdat/database/settings.php";
    include "admindinhphatdat/database/contact.php";
    include "admindinhphatdat/database/blogcategories.php";
    include "admindinhphatdat/database/sliders.php";
    include "admindinhphatdat/database/socials.php";
    include "admindinhphatdat/database/subscribers.php";
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
		<link rel="stylesheet" href="./includes/css.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
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
						<div class="inner">
							<h1>Contact INFO</h1>
							<p><div style="width:100%;"><?php echo $settings->site_map ?></div></p>
							<?php echo $contact->content; ?>
							<h1>Contact US</h1>
							<form name="cForm" id="cForm" class="s-content__form" onsubmit="event.preventDefault(); mailContact();">
								<fieldset>
									<div class="form-field">
										<input name="name" type="text" id="name" class="h-full-width h-remove-bottom" placeholder="Your Name" value="">
									</div>
									<div class="form-field">
										<input name="email" type="email" id="email" class="h-full-width h-remove-bottom" placeholder="Your Email" value="">
									</div>
									<div class="message form-field">
										<textarea name="message" id="message" class="h-full-width" placeholder="Your Message"></textarea>
									</div>
									<br>
									<button type="submit" class="submit btn btn--primary h-full-width">Submit</button>
								</fieldset>
							</form>
							<div id="contact_send"></div>
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
			<script>
       function mailContact() {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var message = document.getElementById("message").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "success") {
                // Clear input fields
                document.getElementById('name').value = "";
                document.getElementById('email').value = "";
                document.getElementById('message').value = "";

                // Display success message in green color
                var contactSend = document.getElementById('contact_send');
                contactSend.innerHTML = "Your message has been sent to Admin!";
                contactSend.style.color = "rgba(0, 184, 148,1.0)";

                // Clear message after 5 seconds
                setTimeout(function() {
                    contactSend.innerHTML = "";
                }, 5000); // 5000 milliseconds = 5 seconds
            } else {
                alert("Failed to send message. Please try again later.");
            }
        }
    };
    xhttp.open("POST", "sendmailcontact.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('name=' + name + '&email=' + email + '&message=' + message);
}

    </script>
	</body>
</html>