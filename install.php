<?php
  require_once 'lib/common.php';
  require_once 'lib/install.php'

  // Store stuff in the session to survive redirects
  session_start();

  // Only run installer when it comes from form
  if($_POST)
  {
    // Install
    $pdo = getPDO();
    list($_SESSION['count'], $_SESSION['error']) = installBlog($pdo);

    // Redirect from POST to GET
    redirectAndExit('install.php');
  }

  // Check if install was triggered
  $attempted = false;
  if($_SESSION)
  {
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];

    // Unsert session vars, so we only report install/failure once
    unset($_SESSION['count']);
    unset($_SESSION['error']);
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
      <?php if ($attempted): ?>
        <?php if ($error): ?>
            <div class="error box">
                <?php echo $error ?>
            </div>
        <?php else: ?>
            <div class="success box">
                The database and demo data was created OK.
                <?php foreach (array('post', 'comment') as $tableName): ?>
                    <?php if (isset($count[$tableName])): ?>
                        <?php // Prints the count ?>
                        <?php echo $count[$tableName] ?> new
                        <?php // Prints the name of the thing ?>
                        <?php echo $tableName ?>s
                        were created.
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>

      <?php else: ?>

        <p>Click the install button to reset the database.</p>
        <form method="post">
            <input
                name="install"
                type="submit"
                value="Install"
            />
        </form>

      <?php endif ?>
     </body>
 </html>
