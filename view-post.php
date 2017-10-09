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
    $commentData = array(
      'name' => $_POST['comment-name'],
      'website' => $_POST['comment-website'],
      'text' => $_POST['comment-text'],
    );

    $errors = addCommentToPost($pdo, $postID, $commentData);

    if(!$errors)
      redirectAndExit('view-post.php?post_id=' . $postID);
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
       <div class="comment-list">
           <h3><?php echo countCommentsForPost($postId) ?> comments</h3>
           <?php foreach (getCommentsForPost($postId) as $comment): ?>
               <div class="comment">
                   <div class="comment-meta">
                       Comment from
                       <?php echo htmlEscape($comment['name']) ?>
                       on
                       <?php echo convertSqlDate($comment['created_at']) ?>
                   </div>
                   <div class="comment-body">
                       <?php // This is already escaped ?>
                       <?php echo convertNewlinesToParagraphs($comment['text']) ?>
                   </div>
               </div>
           <?php endforeach ?>
       </div>

        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
