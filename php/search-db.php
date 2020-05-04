<?
   include 'classes/Database.php';

   $sql_connection = $db->getConnection();

   $reqEverything =  $sql_connection->prepare("SELECT * FROM books WHERE author LIKE :keyword OR title LIKE :keyword");
   $reqEverything->bindValue(':keyword', '%'.$_POST['keyword'].'%');
   $reqEverything->execute();

   $db_results = $reqEverything->fetchAll(PDO::FETCH_ASSOC);

   echo json_encode($db_results);
?>
