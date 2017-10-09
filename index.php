<?php
  // Work out path to DB for connection
  $root = __DIR__;
  $db = $root . '/data/data.sqlite';
  $dsn = 'sqlite:' . $db;

  // Connect to DB
  $pdo = new PDO($dsn);

  $stmt = $pdo -> query(
    "SELECT id, title, created_at, body
    FROM post
    ORDER BY created_at DESC"
  );

  if($stmt === false)
    throw new Exception('Problem running the query.')
?>

<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
      <?php require 'templates/title.php' ?>

      <?php while($row = $stmt -> fetch(PDO::FETCH_ASSOC)): ?>

        <h2>
          <?php echo htmlspecialchars($row['title'], ENT_HTML5, 'UTF-8') ?>
        </h2>

        <div>
          <?php echo $row['created_at'] ?>
        </div>

        <p>
          <?php echo htmlspecialchars($row['body'], ENT_HTML5, 'UTF-8') ?>
        </p>
        <p><a href="view-post.php?post_id=<?php echo $row['id'] ?>">Read more....</a></p>

      <?php endwhile ?>
    </body>
</html>
