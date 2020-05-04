<?
include 'classes/Database.php';
include 'classes/Users.php';
include 'classes/Review.php';
include 'classes/Books.php';

$limit = 5; //Number of posts to be loaded per call
$reviews = new Review($db->connection);
$reviews->displayReviews($_REQUEST, $limit);
?>
