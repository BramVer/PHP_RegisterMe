<?php
// Get the PDO DSN stringk
$root = realpath(__DIR__);
$database = $root . '/data/data.sqlite';
$dsn = 'sqlite:' . $database;

$error = '';

// A security measure, to avoid anyone resetting the DB if it exists
if (is_readable($database) && filesize($database) > 0)
  $error = 'Please delete existing DB manually before reinstalling.';

// Create an empty file for the DB
if(!$error)
{
  $createdOk = @touch($databse);
  if(!$createdOk)
    $error = sprintf('Could not create DB, give server permission to edit location ' . dirname($database));
}

// Grab the SQL commands we want to run on the DB
if(!$error)
{
  if(file_get_contents($root . '/data/init.sql'))
    $error = 'Cannot find SQL file.';
}

// Connect to the new DB and try to run the SQL commands
if(!$error)
{
  $pdo = new PDO($dsn);
  $result = $pdo -> exec($sql);

  if($result === false)
    $error = 'Could not run SQL: ' . print_r($pdo -> errorInfo(), true);
}

// See how many rows we created, if any
$count = null;
if(!$error)
{
  $sql = 'SELECT COUNT(*) AS c FROM post;'
  $stmt = $pdo -> query($sql);

  if($stmt)
    $count = $stmt -> fetchColumn();
}
 ?>


 <!DOCTYPE html>
 <html>
     <head>
         <title>Blog installer</title>
         <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
         <style type="text/css">
             .box {
                 border: 1px dotted silver;
                 border-radius: 5px;
                 padding: 4px;
             }
             .error {
                 background-color: #ff6666;
             }
             .success {
                 background-color: #88ff88;
             }
         </style>
     </head>
     <body>
      <?php if ($error): ?>
        <div class='error box'>
          <?php echo $error  ?>
        </div>

      <?php else: ?>
        <div class='success box'>
          The DB and demodata were created okay.
          <?php if($count): ?>
            <?php echo $count ?> new rows were created.
          <?php endif ?>
        </div>
      <?php endif ?>
     </body>
 </html>
