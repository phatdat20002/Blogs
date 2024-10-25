<header id="header">
    <div class="inner">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <img src="./images/<?php echo $settings->site_logo; ?>" alt="Logo" style="height: 40px; margin-right: 10px;"> <span class="title">Blog Website</span>
        </a>
        <!-- Nav -->
        <nav>
            <ul>
                <li><a href="#menu">Menu</a></li>
            </ul>
        </nav>
    </div>
</header>
<nav id="menu">
    <h2>Menu</h2>
    <ul>
        <li><a href="index.php" <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
        <li class="dropdown">
            <a href="#" <?php if(basename($_SERVER['PHP_SELF']) == 'category.php') echo 'class="active"'; ?>>Categories</a>
            <ul class="dropdown-menu">
                <?php  
                while($rows = $stmt_blogcategories->fetch()){
                ?>
                <li><a href="category.php?cate=<?php echo $rows['id'] ?>"><?php echo $rows['title'] ?></a></li>
                <?php  
                }
                ?>
            </ul>
        </li>
        <li><a href="about.php" <?php if(basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li>
        <li><a href="terms.php" <?php if(basename($_SERVER['PHP_SELF']) == 'terms.php') echo 'class="active"'; ?>>Terms</a></li>
        <li><a href="contact.php" <?php if(basename($_SERVER['PHP_SELF']) == 'contact.php') echo 'class="active"'; ?>>Contact Us</a></li>
    </ul>
</nav>

