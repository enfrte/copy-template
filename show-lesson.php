<?php 
require __DIR__.'\DbConnect.php';
$db = DbConnect::getInstance();
$pdo = $db->getConnection();

$lesson_id = intval((isset($_GET['lessonId'])) ? $_GET['lessonId'] : 0);

try {
  if ($lesson_id > 0) {
    $sql = $pdo->query("SELECT * FROM lesson WHERE id = $lesson_id");
    $lesson_row = $sql->fetch(PDO::FETCH_ASSOC); 
    $output = true;
  }
  $pdo = null;
} 
catch (PDOException $e) {
  echo '<pre>Error: '.$e->getMessage();
}

include __DIR__.'\index.php';