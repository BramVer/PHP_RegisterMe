<?php
  /**
  * Called to handle the comment form, redirects upon success.
  *
  * @param PDO $pdo
  * @param integer $postID
  * @param array $commentData
  */
  function handleAddComment(PDO $pdo, $postID, array $commentData)
  {
    $errors = addCommentToPost(
      $pdo,
      $postID,
      $commentData
    );

    if(!$errors)
      redirectAndExit('view-post.php?post_id=' . $postID);

    return $errors;
  }


  /**
   * Called to handle the delete comment form, redirects afterwards
   *
   * The $deleteResponse array is expected to be in the form:
   *
   *	Array ( [6] => Delete )
   *
   * which comes directly from input elements of this form:
   *
   *	name="delete-comment[6]"
   *
   * @param PDO $pdo
   * @param integer $postId
   * @param array $deleteResponse
   */
  function handleDeleteComment(PDO $pdo, $postID, array $deleteResponse)
  {
    if(isLoggedIn())
    {
      $deleteCommentID = array_keys($deleteResponse)[0];
      if($deleteCommentID)
        deleteComment($pdo, $postID, $deleteCommentID);

      redirectAndExit('view-post.php?post_id=' . $postID);
    }
  }

  /**
   * Delete the specified comment on the specified post
   *
   * @param PDO $pdo
   * @param integer $postId
   * @param integer $commentId
   * @return boolean True if the command executed without errors
   * @throws Exception
   */
  function deleteComment(PDO $pdo, $postID, $commentID)
  {
    $sql = 'DELETE FROM comment
            WHERE post_id = :post_id AND id = :comment_id';
    $stmt = $pdo -> prepare($sql);
    if($stmt === false)
      throw new Exception('There was a problem preparing this query.');

    $result = $stmt -> execute(
      array(
        'post_id' => $postID,
        'comment_id' => $commentID,
      )
    );

    return $result !== false;
  }


  /**
  * Retrieves a single post
  *
  * @param PDO $pdo
  * @param integer $postId
  * @throws Exception
  */
  function getPostRow(PDO $pdo, $postID)
  {
    $stmt = $pdo -> prepare(
      "SELECT title, created_at, body,
              (SELECT COUNT(*) FROM comment WHERE comment.post_id = post.id) comment_count
      FROM post
      WHERE id = :id"
    );

    if($stmt === false)
      throw new Exception('Problem preparing the query.');

    $result = $stmt -> execute(
      array( 'id' => $postID, )
    );

    if($result === false)
      throw new Exception('Problem running the query.');

    // Get and return a row
    return $stmt -> fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Writes a comment to a particular post
   *
   * @param PDO $pdo
   * @param integer $postID
   * @param array $commentData
   *
   * @return array
   */
  function addCommentToPost(PDO $pdo, $postID, array $commentData)
  {
    $errors = array();

    // Do some validation
    if(empty($commentData['name']))
      $errors['name'] = 'A name is required.';

    if(empty($commentData['text']))
      $errors['text'] = 'A comment is required.';

    if(!$errors)
    {
      $sql = "INSERT INTO comment
              (name, website, text, created_at, post_id)
              VALUES
              (:name, :website, :text, :created_at, :post_id)";

      $stmt = $pdo -> prepare($sql);
      if($stmt === false)
        throw new Exception('Cannot prepare insert statement.');

      $result = $stmt -> execute(
        array_merge($commentData, array('post_id' => $postID, 'created_at' => getSQLDateForNow(), ))
      );

      if($result === false)
      {
        $errorInfo = $pdo -> errorInfo();
        if($errorInfo)
          $errors[] = $errorInfo[2];
      }
    }

    return $errors;
  }
?>
