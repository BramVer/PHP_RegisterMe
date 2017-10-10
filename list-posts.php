<?php

require_once 'lib/common.php';

session_start();

if(!isLoggedIn())
  redirectAndExit('index.php');

  $pdo = getPDO();
  $posts = getAllPosts($pdo);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>A blog application | Blog posts</title>
    <?php require 'templates/head.php' ?>
  </head>
  <body>
    <?php require 'templates/top-menu.php' ?>

    <h1>Post list</h1>

    <form method='POST'>
      <table id='post-list'>
        <tbody>

          <?php foreach($posts as $p): ?>
            <tr>
              <td><?php echo htmlEscape($p['title']) ?></td>
              <td><?php echo convertSqlDate($p['created_at']) ?></td>
              <td><a href='edit-post.php?post_id=<?php echo $p['id'] ?>'>Edit me</a></td>
              <td><input type='submit' name='delete-post[<?php echo $p["id"] ?>]' value='Delete'/></td>
            </tr>
          <?php endforeach ?>

        </tbody>
      </table>
    </form>
  </body>
</html>