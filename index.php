<?
session_start();

if(!isset($_SESSION['logged'])) $_SESSION = false;
if(!isset($_SESSION['id'])) $_SESSION = null;

include 'php/classes/Database.php';
include 'php/classes/Books.php';
include 'php/classes/Users.php';
include 'php/classes/Review.php';
include 'php/classes/Timeline.php';

if (isset($_GET['page'])) {
  $page = $_GET['page'];
}
else {
  $page = 'home';
}

if (!file_exists('content/'.$page.'.php')) {
  include('content/404.php');
  exit;
}

?>

<!DOCTYPE html>
  <head>
    <base href="http://localhost/thebookclub/">
    <meta charset="utf-8">
    <title>the book club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/css/style.css"/>
    <link rel="stylesheet" href="public/fontawesome/all.min.css"/>
    <link rel="stylesheet" href="public/fontawesome/fontawesome.min.css"/>
    <script src="public/fontawesome/all.js"></script>
    <script type="text/javascript" src="public/js/jquery.min.js"></script>
    <script type="text/javascript" src="public/js/main.js"></script>
  </head>
  <body>
<?
    if($page != 'home'){
      include 'header.php';
    }
?>
    <div id="index_container">
<?
    include 'content/'.$page.'.php';

?>
   </div>
  </body>
</html>
