<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Copy Template Example</title></head><body><?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>

<h2>Save a template example.</h2>
<p>A simple example of how to make a copy of all database rows that are linked to other tables.</p><hr>

<h3>Copy a lesson</h3>
<form action="copy-template.php" method="get">
  <div>Enter lesson ID: <input type="text" value="<?php if (isset($lesson_id)) { echo $lesson_id; } else { echo '1'; } ?>" name="lessonId"></div>
  <div><input type="submit" value="Copy lesson"></div>
</form>

<h3>Show a lesson</h3>
<form action="show-lesson.php" method="get">
  <div>Enter lesson ID: <input type="text" value="<?php if (isset($lesson_id)) { echo $lesson_id; } else { echo '1'; } ?>" name="lessonId"></div>
  <div><input type="submit" value="Fetch lesson"></div>
</form>

<?php if (isset($output)) { ?>
  <h3>Lesson <?php if (isset($lesson_id)) { echo $lesson_id; } else { echo ''; } ?> output</h3>
  <pre>Current lesson: <?php if (isset($lesson_row)) { print_r($lesson_row); } else { echo ''; } ?></pre>
<?php } ?>

</body></html>