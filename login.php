<?php
  require_once 'lib/common.php';
  require_once 'vendor/password_compat/lib/password.php';

  // Test for min version PHP
  if(version_compare(PHP_VERSION, '5.3.7') < 0)
    throw new Exception('System needs PHP 5.3.7 or later.');

  session_start();

  if(isLoggedIn())
    redirectAndExit('index.php');

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
      <?php require 'templates/head.php' ?>
  </head>
  <body>
    <?php require 'templates/title.php' ?>

    <?php if($username): ?>
      <div class='error box'>
        Username or pwd is incorrect, please try again.
      </div>
    <?php endif ?>

    <p>Login here:</p>

    <form method='POST' class='user-form'>
      <div>
        <label for='username'>Username:
          <input type='text' id='username' name='username' value='<?php echo htmlEscape($username) ?>'/>
        </label>
      </div>
      <div>
        <label for='password'>Password:
          <input type='password' id='password' name='password'/>
        </label>
      </div>

      <input type='submit' name='submit' value='login'/>
    </form>
  </body>
</html>
