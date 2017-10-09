<?php
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
      "SELECT title, created_at, body
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

      $createdTimestamp = date('Y-m-d H:m:s');

      $result = $stmt -> execute(
        array_merge($commentData, array('post_id' => $postID, 'created_at' => $createdTimestamp))
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
