<?php
  require_once 'lib/common.php';
  require_once 'lib/view-post.php';

  session_start();

  // Get post ID
  $postID = (isset($_GET['post_id'])) ? $_GET['post_id'] : 0;

  // Connect to DB
  $pdo = getPDO();
  $row = getPostRow($pdo, $postID);

  // If no post exists, redirectAndExit()
  if(!$row)
    redirectAndExit('index.php?not-found=1');

  $errors = null;
  if($_POST)
  {
    switch($_GET['action'])
    {
      case 'add-comment':
        $commentData = array(
          'name' => $_POST['comment-name'],
          'website' => $_POST['comment-website'],
          'text' => $_POST['comment-text'],
        );
        $errors = handleAddComment($pdo, $postID, $commentData);
        break;

      case 'delete-comment':
        $deleteResponse = $_POST['delete-comment'];
        handleDeleteComment($pdo, $postID, $deleteResponse);
        break;
    }
  }
  else
  {
    $commentData = array(
      'name' => '',
      'website' => '',
      'text' => '',
    );
  }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application |
            <?php echo htmlEscape($row['title']) ?>
        </title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <div class="post">
           <h2>
               <?php echo htmlEscape($row['title']) ?>
           </h2>
           <div class="date">
               <?php echo convertSqlDate($row['created_at']) ?>
           </div>
           <?php // This is already escaped, so doesn't need further escaping ?>
           <?php echo convertNewlinesToParagraphs($row['body']) ?>
       </div>
        <?php require 'templates/comment-list.php' ?>
        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
