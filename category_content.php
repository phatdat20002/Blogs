<?php
if (isset($_GET['cate'])) {
    $blogs->id_category = $_GET['cate'];
    $stmt_blogs = $blogs->showAllCategories();
    $blogcategories->id = $_GET['cate'];
    $blogcategories->read();
    $category_title = $blogcategories->title;

    // Display category title
    echo "<header id='inner'>";
    echo "<h1>{$category_title}</h1>";
    echo "<p>Etiam quis viverra lorem, in semper lorem. Sed nisl arcu euismod sit amet nisi euismod sed cursus arcu elementum ipsum arcu vivamus quis venenatis orci lorem ipsum et magna feugiat veroeros aliquam. Lorem ipsum dolor sit amet nullam dolore.</p>";
    echo "</header>";
    echo "<br>";
    echo "<h2 class='h2'>Latest blog posts</h2>";
    echo "<div class='row'>";

    while ($row = $stmt_blogs->fetch()) {
        extract($row);
        echo "<div class='col-sm-4 text-center'>";
        echo "<div class='overlay-content'>";
        $users = new users($db);
        $users->id = $row['id_user'];
        $users->read();
        echo "<strong title='Author'><i class='fa fa-user' style='margin-right:4px;'></i>" . htmlspecialchars($users->name) . " </strong> &nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<strong title='Posted on'><i class='fa fa-calendar'></i> " . htmlspecialchars($row['created_at']) . "</strong> &nbsp;&nbsp;&nbsp;&nbsp;";
        echo "</div>";
        echo "<img style='width:320px;height:220px' src='./images/blogs/{$image}' class='img-fluid' alt='' />";
        echo "<h2 class='m-n'><a href='detail.php?id={$id}'>{$title}</a></h2>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>Vui lòng chọn một chủ đề để xem các bài viết.</p>";
}
?>
