<?php

  require_once 'lib/common.php';
  require_once 'lib/edit-post.php';
  require_once 'lib/view-post.php';

  session_start();

  // Redirect non-auth users
  if(!isLoggedIn())
    redirectAndExit('index.php');

  $title = $body = '';
  $pdo = getPDO();

  $postID = null;
  if(isset($_GET['post_id']))
  {
    $post = getPostRow($pdo, $_GET['post_id']);
    if($post)
    {
      $postID = $_GET['post_id'];
      $title = $post['title'];
      $body = $post['body'];
    }
  }

  // Handle POST operation
  $errors = array();
  if($_POST)
  {
    // Validate
    $title = $_POST['post-title'];
    if(!$title)
      $errors[] = 'The post must have a title.';

    $body = $_POST['post-body'];
    if(!$body)
      $errors[] = 'The post must have a body.';

    if(!$errors)
    {
      if($postID)
        editPost($pdo, $title, $postID);
      else
      {
        $userID = getAuthUserID($pdo);
        $postID = addPost($pdo, $title, $body, $userID);

        if($postID === false)
          $errors[] = 'Post operation failed.';
      }
    }

    if(!$errors)
      redirectAndExit('edit-post.php?post_id=' . $postID);
  }
  elseif (isset($_GET['post_id']))
  {
    $post = getPostRow($pdo, $_GET['post_id']);
    if($post)
    {
      $title = $post['title'];
      $body = $post['body'];
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>A blog application | New post</title>
    <?php require 'templates/head.php' ?>
  </head>
  <body>
    <?php require 'templates/title.php' ?>

    <?php if ($errors): ?>
      <div class='error box'>
        <ul>
          <?php foreah($errors as $e): ?>
            <li><?php echo $e ?></li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <form method='POST' class='post-form user-form'>

      <div>
        <label for='post-title'>Title: </label>
        <input id='post-title' name='post-title' type='text' value='<?php echo htmlEscape($title) ?>'/>
      </div>

      <div>
        <label for='post-body'>Body: </label>
        <textarea id='post-body' name='post-body' rows='12' cols='70'><?php echo htmlEscape($body) ?></textarea>
      </div>

      <div>
        <input type='submit' value='Save post'/>
      </div>
    </form>
  </body>
</html>
