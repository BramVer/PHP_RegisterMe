<?php
/**
 * @var $errors string
 * @var $commentData array
 */
?>

<hr/>

<?php if($errors): ?>
  <div class='error box'>
    <ul>
      <?php foreach($errors as $e): ?>
        <li><?php echo $e ?></li>
      <?php endforeach ?>
    </ul>
  </div>
<?php endif ?>

<h3>Add your comment</h3>

<form method='POST'>
  <p>
    <label for='comment-name'>
      Name:
    </label>
    <input type='text' id='comment-name' name='comment-name' value="<?php echo htmlEscape($commentData['name']) ?>"/>
  </p>

  <p>
    <label for='comment-website'>
      Website:
    </label>
    <input type='text' id='comment-website' name='comment-website' value="<?php echo htmlEscape($commentData['website']) ?>"/>
  </p>

  <p>
    <label for='comment-text'>
      Comment:
    </label>
    <textarea id='comment-text' name='comment-text' rows='8' cols='70'>
      <?php echo htmlEscape($commentData['text']) ?>
    </textarea>
  </p>

  <input type='submit' value='Submit comment'/>
</form>
