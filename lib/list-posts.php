<?php

/**
 * Tries to delete the specified post
 *
 * We first delete the attached comments, then the post
 * @param PDO $pdo
 * @param integer $postID
 * @return boolean Returns true on successful deletion
 * @throws Exception
 */
function deletePost(PDO $pdo, $postID)
{
  $sqls = array(
    'DELETE FROM comment WHERE post_id = :id',  // Deletes comments
    'DELETE FROM post WHERE id = :id',   // Deletes post
  );
  foreach($sqls as $sql)
  {
    $stmt = $pdo -> prepare($sql);
    if($stmt === false)
      throw new Exception('There was a problem prepping the query.');

    $result = $stmt -> execute(
      array('id' => $postID, )
    );

    if($result === false)
      break;
  }

  return $result !== false;
}
?>
