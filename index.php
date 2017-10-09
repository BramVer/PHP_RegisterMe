<?php
  require_once 'lib/common.php';

  session_start();

  // Connect to DB
  $pdo = getPDO();

  $stmt = $pdo -> query(
    "SELECT id, title, created_at, body
    FROM post
    ORDER BY created_at DESC"
  );

  if($stmt === false)
    throw new Exception('Problem running the query.');

  $notFound = isset($_GET['not-found']);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
      <?php require 'templates/title.php' ?>

      <?php if ($notFound): ?>
        <div class='error box'>
          Error: cannot find requested blog post :'(
        </div>
      <?php endif ?>

      <?php while($row = $stmt -> fetch(PDO::FETCH_ASSOC)): ?>

        <h2>
          <?php echo htmlEscape($row['title']) ?>
        </h2>

        <div>
          <?php echo convertSQLDate($row['created_at']) ?>
          (<?php echo countCommentsForPost($row['id']) ?> comments)
        </div>

        <p>
          <?php echo htmlEscape($row['body']) ?>
        </p>
        <p><a href="view-post.php?post_id=<?php echo $row['id'] ?>">Read more....</a></p>

      <?php endwhile ?>
    </body>
</html>
