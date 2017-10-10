<?php

  function addPost(PDO $pdo, $title, $body, $userID)
  {
    $sql = 'INSERT INTO
            post
            (title, body, user_id, created_at)
            VALUES
            (:title, :body, :user_id, :created_at)';

    $stmt = $pdo -> prepare($sql);
    if($stmt === false)
      throw new Exception('Could not prepare post insert query.');

    $result = $stmt -> execute(
      array(
        'title' => $title,
        'body' => $body,
        'user_id' => $userID,
        'created_at' => getSQLDateForNow()
      )
    );
    if($result === false)
      throw new Exception('Could not run insert query for post.');

    // Built-in PHP function
    return $pdo -> lastInsertID();
  }

?>
