<?php

  /**
   * Blog installer function
   *
   * @return array(count array, error string)
   */
  function installBlog(PDO $pdo)
  {
    // Get a couple of useful project paths
    $root = getRootPath();
    $database = getDatabasePath();

    $error = '';

    // A security measure, to avoid anyone resetting the DB if it exists
    if (is_readable($database) && filesize($database) > 0)
      $error = 'Please delete existing DB manually before reinstalling.';

    // Create an empty file for the DB
    if(!$error)
    {
      $createdOk = @touch($database);

      if(!$createdOk)
        $error = sprintf('Could not create DB, give server permission to edit location ' . dirname($database));
    }

    // Grab the SQL commands we want to run on the DB
    if(!$error)
    {
      $sql = file_get_contents($root . '/data/init.sql');

      if($sql === false)
        $error = 'Cannot find SQL file.';
    }

    // Connect to the new DB and try to run the SQL commands
    if(!$error)
    {
      $result = $pdo -> exec($sql);

      if($result === false)
        $error = 'Could not run SQL: ' . print_r($pdo -> errorInfo(), true);
    }

    // See how many rows we created, if any
    $count = array();

    foreach(array('post', 'comment') as $tableName)
    {
      if(!$error)
      {
        $sql = "SELECT COUNT(*) AS c FROM " . $tableName;
        $stmt = $pdo -> query($sql);

        if($stmt)
          $count[$tableName] = $stmt -> fetchColumn();
      }
    }

    return array($count, $error);
  }
?>
