<?php
include "admindinhphatdat/database/database.php";
include "admindinhphatdat/database/settings.php";
include "admindinhphatdat/database/contact.php";
include "admindinhphatdat/database/blogcategories.php";
include "admindinhphatdat/database/sliders.php";
include "admindinhphatdat/database/socials.php";
include "admindinhphatdat/database/about.php";
include "admindinhphatdat/database/blogs.php";
include "admindinhphatdat/database/comments.php";
include "admindinhphatdat/helper/help_function.php";
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

$contact = new contact($db);
$contact->id = 1;
$contact->read();

$about = new about($db);
$about->id = 1;
$about->read();
if (isset($_GET['id'])) {
    $blogs->id = $_GET['id'];
    $blogs->read();

    if ($blogs->title != null) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $comment = new comments($db);
            $comment->id_parent_comment = $_POST['id_parent_comment'] == '0' ? NULL : $_POST['id_parent_comment'];
            $comment->comment = $_POST['comment'];
            $comment->id_blog = $_POST['id_blog'];
            $comment->name = $_POST['name'];
            $comment->email = $_POST['email'];
            $comment->created_at = date('Y-m-d H:i:s');
            $comment->updated_at = date('Y-m-d H:i:s');
            if ($comment->create()) {
                $message = "Comment added successfully.";
            } else {
                echo "Failed to add comment.";
            }
         
        }
        $users = new users($db);
        $users->id = $blogs->id_user;
        $users->read();
        $next_blog = $blogs->getNextBlogByUser($blogs->id, $blogs->id_user);
        $previous_blog = $blogs->getPreviousBlogByUser($blogs->id, $blogs->id_user);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blogs->title); ?></title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="./includes/css.css" />
    <link rel="icon" href="<?php echo "./images/".$settings->site_favicon ?>" type="image/png">
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
    <style>
        .comment {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 40px;
            background-color: #f0f2f5;
            border-radius: 24px;
            padding:14px;
        }
        .comment-content {
            flex: 1;
        }

        .comment .reply-button {
            margin-left: 40px;
            margin-top: 0; 
        }

        .nav-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include "includes/header.php"; ?>
        <div class="container">
            <h1 class="blog-title"><?php echo htmlspecialchars($blogs->title); ?></h1>
            <img style="width:100%" src="./images/blogs/<?php echo htmlspecialchars($blogs->image); ?>" class="blog-image" alt="<?php echo htmlspecialchars($blogs->title); ?>" />
            <?php echo $blogs->content; ?>
            <p class="blog-date">Ngày đăng: <?php echo htmlspecialchars($blogs->created_at); ?></p>
            <p class="blog-author">Người đăng: <span style="color:#f2849e !important;font-weight:700;"><?php echo htmlspecialchars($users->name); ?></span></p>
    </div>
        <div class="nav-buttons">
            <?php if ($previous_blog) { ?>
                <a style="position: fixed;top: 50%;" href="detail.php?id=<?php echo htmlspecialchars($previous_blog['id']); ?>" class="btn btn-secondary">Previous</a>
            <?php } ?>
            <?php if ($next_blog) { ?>
                <a style="position: fixed;right:0%;top: 50%;" href="detail.php?id=<?php echo htmlspecialchars($next_blog['id']); ?>" class="btn btn-secondary">Next</a>
            <?php } ?>
        </div>
        <div class="container mt-5">
            <h2>Bình luận</h2>
            <?php
            $comments = new comments($db);
            $comments->id_blog = $_GET['id'];
            $stmt_comments = $comments->readCommentByBlogId();
            $all_comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt_comments->rowCount() > 0) {
                function display_comments($comments, $parent_id = 0, $level = 0) {
                    foreach ($comments as $comment) {
                        if ($comment['id_parent_comment'] == $parent_id) {
                            echo '<div class="comment" style="margin-left: ' . ($level * 40) . 'px;">';
                            echo '<div class="comment-content">';
                            echo '<p><strong>' . htmlspecialchars($comment['name']) . '</strong> - ' . htmlspecialchars($comment['created_at']) . '</p>';
                            echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
                            echo '</div>';
                            echo '<button class="reply-button" data-id="' . $comment['id'] . '">Reply</button>';
                            echo '</div>';
                            display_comments($comments, $comment['id'], $level + 1);
                        }
                    }
                }
                display_comments($all_comments);
            } else {
                echo "<p>Chưa có bình luận nào cho bài viết này.</p>";
            }
            ?>
            <h3>Thêm bình luận mới</h3>
            <?php if(isset($message)){ ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $message ?>
                </div>
            <?php } ?>
            <form method="post" action="">
                <input type="hidden" name="id_blog" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <input type="hidden" id="id_parent_comment" name="id_parent_comment" value="0">
                <div class="form-group">
                    <label for="name">Tên của bạn</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email của bạn</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="comment">Nội dung bình luận</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn">Gửi bình luận</button>
            </form>
        </div>
        <?php include "includes/footer.php"; ?>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.reply-button').forEach(function(button) {
            button.addEventListener('click', function() {
                var parentId = this.getAttribute('data-id');
                document.getElementById('id_parent_comment').value = parentId;
                document.getElementById('comment').focus();
            });
        });
    });
    </script>
</body>
</html>
<?php
    } else {
        echo "<p>Bài viết không tồn tại.</p>";
    }
} else {
    echo "<p>Không có ID bài viết được cung cấp.</p>";
}
?>
