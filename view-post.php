<?php
  // Work out path to DB for connection
  $root = __DIR__;
  $db = $root . '/data/data.sqlite';
  $dsn = 'sqlite:' . $db;

  // Get post ID
  $postID = 0;

  if(isset($_GET['post_id']))
    $postID = $_GET['post_id'];

  // Connect to DB
  $pdo = new PDO($dsn);
  $stmt = $pdo -> prepare(
    "SELECT title, created_at, body
    FROM post
    WHERE id = :id"
  );

  if($stmt === false)
    throw new Exception('There was a problem preparing the query.');

  $result = $stmt -> execute(
    array('id' => $postID, )
  );

  if($result === false)
    throw new Exception('There was a problem executing the query.');

  // Get a row
  $row = $stmt -> fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application |
            <?php echo htmlspecialchars($row['title'], ENT_HTML5, 'UTF-8') ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <h2>
            <?php echo htmlspecialchars($row['title'], ENT_HTML5, 'UTF-8') ?>
        </h2>

        <div>
            <?php echo $row['created_at'] ?>
        </div>

        <p>
            <?php echo htmlspecialchars($row['body'], ENT_HTML5, 'UTF-8') ?>
        </p>
    </body>
</html>
