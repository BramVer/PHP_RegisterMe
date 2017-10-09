<?php
  require_once 'lib/common.php';
  require_once 'lib/view-post.php';

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
      redirectAndExit('view-post?post_id=' . $postID)
  }

  // Swap carriage returns for paragraph breaks
  $bodyText = htmlEscape($row['body']);
  $paraText = str_replace('\n', '<p><p/>', $bodyText);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application |
            <?php echo htmlEscape($row['title']) ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <h2>
            <?php echo htmlEscape($row['title']) ?>
        </h2>

        <div>
            <?php echo convertSQLDate($row['created_at']) ?>
        </div>

        <p>
          <?php echo $paraText ?>
        </p>

        <h3>
          <?php echo countCommentsForPost($postID) ?>
           comments
        </h3>

        <?php foreach(getCommentsForPost($postID) as $comment): ?>
          <hr/>
          <div class='comment'>
            <div class='comment-meta'>
              Comment from
              <?php echo htmlEscape($comment['name']) ?>
              on
              <?php echo convertSQLDate($comment['created_at']) ?>
            </div>

            <div class='comment-body'>
              </<?php echo htmlEscape($comment['text']) ?>
            </div>
          </div>
        <?php endforeach ?>

        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
