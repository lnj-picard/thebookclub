<?
include 'classes/Database.php';
include 'classes/Users.php';
include 'classes/Review.php';
include 'classes/Books.php';
include 'classes/Timeline.php';
include 'classes/Likes.php';

$limit = 2; //Number of posts to be loaded per call
$reviews = new Timeline($db->connection);
$reviews->displayFriendsReviews($_REQUEST, $limit);
?>
