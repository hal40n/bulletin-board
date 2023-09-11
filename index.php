<?php

  date_default_timezone_set("Asia/Tokyo");

  $comment_array = array();
  $stmt = null;
  $error_messages = [];

// input form
if (!empty($_POST["submitButton"])) {
    if (empty($_POST["username"])) {
        echo "名前を入力してください";
        $error_messages["username"] = "名前を入力してください";
    }

    if (empty($_POST["comment"])) {
        echo "コメントを入力してください";
        $error_messages["comment"] = "コメントを入力してください";
    }

    if (empty($error_messages)) {
        $postDate = date("Y-m-d H:i:s");
        try {
            $stmt = $dbh->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate)");
            $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':comment', $_POST['comment'], PDO::PARAM_STR);
            $stmt->bindParam(':postDate', $postDate);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $stmt->execute();
    }
}

  // connection DB
try {
    $dbh = new PDO('mysql:host=localhost;dbname=bbs-table', "root", "root");
} catch (PDOException $e) {
    echo $e->getMessage();
}

  // GET comments FROM DB
  $sql = "SELECT `id`, `username`, `comment`, `postDate` FROM `bbs-table`;";
  $comment_array = $dbh->query($sql);

  // DB unconnected
  $pdo = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP掲示板</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1 class="title">掲示板</h1>
  <hr>
  <div class="boardWrapper">
    <section>
      <?php foreach ($comment_array as $comment) :?>
        <article>
          <div class="wrapper">
            <div class="nameArea">
              <span>名前：</span>
              <p class="username"><?php echo $comment["username"]; ?></p><!-- username -->
              <time>:<?php echo $comment["postDate"]; ?></time>
            </div><!-- nameArea -->
            <p class="comment"><?php echo $comment["comment"]; ?></p><!-- comment -->
          </div><!-- wrapper -->
        </article>
      <?php endforeach; ?>
    </section>
    <form class="formWrapper" method="post">
      <div>
        <input type="submit" value="書き込む" name="submitButton">
        <label for="">名前</label>
        <input type="text" name="username">
      </div>
      <div>
        <textarea class="commentTextArea" name="comment"></textarea><!-- commentTextArea -->
      </div>
    </form><!-- formWrapper -->
  </div><!-- boardWrapper -->
</body>
</html>