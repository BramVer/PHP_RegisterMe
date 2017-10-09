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
      throw new Exception('Problem preparing the query.')

    $result = $stmt -> execute(
      array( 'id' => $postID, )
    );

    if($result === false)
      throw new Exception('Problem running the query.')

    // Get and return a row
    return $stmt -> fetch(PDO::FETCH_ASSOC);
  }
?>
