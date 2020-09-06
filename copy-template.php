<?php 
require __DIR__.'\DbConnect.php';

$db = DbConnect::getInstance();
$pdo = $db->getConnection();

// As this data is already in the db, there is no need to check for sql injections, apart from the initial id request, which we convert with intval()
$lesson_id = intval((isset($_GET['lessonId'])) ? $_GET['lessonId'] : 0);

try {
  $sql  = 'SELECT * FROM `lesson` WHERE `id` = ' . (int) $lesson_id;
  $stmt = $pdo->query( $sql );
  // Get the first row
  $lesson_row = $stmt->fetch( PDO::FETCH_ASSOC );

  if ( $lesson_row !== false ) {
    // There was at least one record, we can continue...

    // Insert the new lesson, use a prepared statement, just to be safe.
    $sql  = 'INSERT INTO `lesson` (`name`, `active`) VALUES (?, 0)';
    $stmt = $pdo->prepare( $sql );
    $stmt->execute( [ $lesson_row['name'] ] );

    // Get the new lesson id
    $new_lesson_id = $pdo->lastInsertId();

    // Copy the old sections
    $sql          = 'SELECT * FROM `section` WHERE `lesson_id` = ' . (int) $lesson_id;
    $stmt         = $pdo->query( $sql );
    $section_rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
    if ( count( $section_rows ) > 0 ) {
      // Use prepared statements, so the server can just reuse it over and
      // over, and also adding injection protection
      $sql  = 'INSERT INTO `section` (`name`, `section_type`, `lesson_id`) 
              VALUES ( :name, :sectiontype, :lessonid )';
      $stmt = $pdo->prepare( $sql );
      // Prefill the :lessonid since it never changes
      $values = [ 'lessonid' => $new_lesson_id ];

      foreach ( $section_rows as $key => $section ) {
        // Fill the values that change per row
        $values['name']        = $section['name'];
        $values['sectiontype'] = $section['section_type'];

        // Run the prepared statement with the values
        $stmt->execute( $values );

        // Get the new section id to manually put into the SELECT
        $new_section_id = $pdo->lastInsertId();
        // Insert copied section materials if there are any
        // I got some help from Reddit for this part :P
        $sql = 'INSERT INTO `material` (`name`, `material_type`, `section_id`)
                  SELECT `name`, `material_type`, ' . (int) $new_section_id . ' 
                  FROM `material`
                  WHERE `section_id` = ' . (int) $section['id'];
        $pdo->query( $sql );
      }
    }
    $lesson_id = $new_lesson_id; // set for the index form to use. 
  } else {
    echo '<p style="color:red">No lesson found with that id</p>';
  }
  
  $pdo = null;
} 
catch (PDOException $e) {
  echo '<pre>Error: '.$e->getMessage();
}

include __DIR__.'\index.php';