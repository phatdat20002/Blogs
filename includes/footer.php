<?php
    $contact = new contact($db);
    $contact->id = 1;
    $contact->read();
?>
<footer id="footer">
    <div class="inner">
        <section class="column">
            <h2>About Us</h2>
            <p style="color:#000000;"><?php echo $about->footer ?></p>
        </section>
        <section class="column">
            <h2>Contact Us</h2>
            <?php echo $contact->content; ?>
        </section>
        <section class="column">
            <h2>Follow Us</h2>
            <ul class="icons">
                <?php
                    while($rows = $stmt_socials->fetch()){
                ?>
                    <li><a href="<?php echo $rows['url'] ?>" class="<?php echo $rows['icon'] ?>"><span class="label"><?php echo $rows['title'] ?></span></a></li>
                <?php  
                    }
                ?>
            </ul>
        </section>
        <section class="column large-3 medium-6 tab-12 s-footer__subscribe">
            <h2>Sign Up for Newsletter</h2>
            <p>Signup to get updates on articles, interviews and events.</p>
            <div class="subscribe-form">
                <form id="mc-form" class="group" novalidate="true" method="post" style="color:rgb(79, 79, 79)">
                    <input style="color:rgb(79, 79, 79)" type="email" name="email" class="email" id="email" placeholder="Your Email Address" required="">
                    <input style="color:rgb(79, 79, 79)" type="button" style="--btn-height: calc(var(--vspace-btn) - .8rem); margin-right: 0; width: 100%; color: white;" name="subscribe" value="subscribe" onclick="btn_subscribe()">
                    <label for="mc-email" class="subscribe-message"></label>
                </form>
            </div>
            <div id="msg" style="font-weight:bold; text-align:center;color:rgb(79, 79, 79)"></div>
        </section>
    </div>
    <ul class="copyright">
        <li style="font-size:18px"><?php echo $settings->site_footer ?></li>
    </ul>
</footer>
<script>
    function btn_subscribe() {
    var email = document.getElementById("email").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            switch (this.responseText.trim()) {
                case 'success':
                    document.getElementById('email').value = "";
                    document.getElementById('msg').innerHTML = "Please check your email to verify your account.";
                    break;
                case 'resend_mail':
                    document.getElementById('email').value = "";
                    document.getElementById('msg').innerHTML = "Verification link has been sent to your email.";
                    break;
                case 'already_subscriber':
                    document.getElementById('email').value = "";
                    document.getElementById('msg').innerHTML = "You are already subscribed with this email.";
                    break;
                default:
                    document.getElementById('msg').innerHTML = "Something went wrong. Please try again later.";
            }
        }
    };
    xhttp.open("POST", "subscriber.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('email=' + email);
}

</script>
