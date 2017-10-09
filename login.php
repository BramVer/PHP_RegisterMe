<?php
  require_once 'lib/common.php';
  require_once 'vendor/password_compat/lib/password.php';

  // Test for min version PHP
  if(version_compare(PHP_VERSION, '5.3.7') < 0)
    throw new Exception('System needs PHP 5.3.7 or later.');

  session_start();

  // Handle form posting
  $username = '';
  if($_POST)
  {
    $pdo = getPDO();

    // Redirect on correct pwd
    $username = $_POST['username'];
    $ok = tryLogin($pdo, $username, $_POST['password']);

    if($ok)
    {
      login($username);
      redirectAndExit('index.php');
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
      <title>
          A blog application | Login
      </title>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  </head>
  <body>
    <?php require 'templates/title.php' ?>

    <?php if($username): ?>
      <div style='border: 1px solid #ff6666; padding: 6px;'>
        Username or pwd is incorrect, please try again.
      </div>
    <?php endif ?>

    <p>Login here:</p>

    <form method='POST'>
      <p>Username: <input type='text' name='username' value='<?php echo htmlEscape($username) ?>'/></p>
      <p>Password: <input type='password' name='password'/></p>

      <input type='submit' name='submit' value='login'/>
    </form>
  </body>
</html>
