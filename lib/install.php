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

  /**
   * Creates a new user in the database
   *
   * @param PDO $pdo
   * @param string $username
   * @param integer $length
   * @return array Duple of (password, error)
   */
  function createUser(PDO $pdo, $username, $length = 10)
  {
    // Creates random pwd
    $alphabet = range(ord('A'), ord('Z'));
    $alphabetLength = count($alphabet);

    $password = '';
    for($i = 0; $i < $length; $i++)
    {
      $lettercode = $alphabet[rand(0, $alphabetLength - 1)];
      $password .= chr($lettercode);
    }

    $error = '';

    // Insert credentials into db
    $sql = "INSERT INTO
            user
            (username, password, created_at)
            VALUES
            (:username, :password, :created_at)";

    $stmt = $pdo -> prepare($sql);
    if($stmt === false)
      $error = 'Could not prepare the user creation.';

    //Password encryption
    if(!$error)
    {
      // Create hash of pwd
      $hash = password_hash($password, PASSWORD_DEFAULT);

      if($hash === false)
        $error = 'Password hashing failed.';
    }

    // Insert user details
    if(!$error)
    {
      $result = $stmt -> execute(
        array(
          'username' => $username,
          'password' => $hash,
          'created_at' => getSQLDateForNow(),
        )
      );

      if($result === false)
        $error = 'Could not run the user creation.';
    }

    if($error)
      $password = '';

    return array($password, $error, );
  }
?>
