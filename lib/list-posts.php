<?php

/**
 * Tries to delete the specified post
 *
 * @param PDO $pdo
 * @param integer $postID
 * @return boolean Returns true on successful deletion
 * @throws Exception
 */
function deletePost(PDO $pdo, integer $postID, )
{
  $sql = 'DELETE FROM post WHERE id = :id';
  $stmt = $pdo -> prepare($sql);
  if($stmt === false)
    throw new Exception('There was a problem prepping the query.');

  $result = $stmt -> execute(
    array('id' => $postID, )
  );

  return $result !== false;
}
?>
