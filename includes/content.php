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
<header id="inner">
    <h1>Write your first blog post now!</h1>
    <p>Etiam quis viverra lorem, in semper lorem. Sed nisl arcu euismod sit amet nisi euismod sed cursus arcu elementum ipsum arcu vivamus quis venenatis orci lorem ipsum et magna feugiat veroeros aliquam. Lorem ipsum dolor sit amet nullam dolore.</p>
</header>

<br>
<h2 class="h2">Latest blog posts</h2>
<div class="row">
    <?php
    foreach ($current_blogs as $rows) {
        ?>
        <div class="col-sm-4 text-center" style="margin-bottom:12px">
            <div class="overlay-content" style="">
                <?php
                        $users = new users($db);
                        $users->id = $rows['id_user'];
                        $users->read();
                        ?>
                <strong title="Author"><i class="fa fa-user" style="margin-right:4px;"></i><?php echo htmlspecialchars($users->name); ?> </strong> &nbsp;&nbsp;&nbsp;&nbsp;
                <strong title="Posted on"><i class="fa fa-calendar"></i> <?php echo htmlspecialchars($rows['created_at']); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <img style="width:320px;height:220px" src="./images/blogs/<?php echo htmlspecialchars($rows['image']); ?>" class="img-fluid" alt="" />
            <h2 class="m-n"><a href="detail.php?id=<?php echo htmlspecialchars($rows['id']); ?>"><?php echo htmlspecialchars($rows['title']); ?></a></h2>
        </div>
        <?php
    }
    ?>
</div>
<div class="pagination text-center justify-content-center">
    <?php if ($previous_page) { ?>
        <a href="?page=<?php echo $previous_page; ?>" class="btn btn-info">Previous</a>
    <?php } ?>
    
    <?php for ($i = 1; $i <= $num_pages; $i++) { ?>
        <a style="margin:0 6px" href="?page=<?php echo $i; ?>" class="btn <?php echo $i == $current_page ? 'btn-secondary' : 'btn-light'; ?>"><?php echo $i; ?></a>
    <?php } ?>

    <?php if ($next_page) { ?>
        <a href="?page=<?php echo $next_page; ?>" class="btn btn-info">Next</a>
    <?php } ?>
</div>
