<?
include 'classes/Database.php';
include 'classes/Likes.php';

$review = $data['review'];

$addlike = $this->connection->prepare("INSERT INTO likes (user_id, review_id) VALUES (:user_id; :review_id)");
$addlike->bindValue(':user_id',$userLogged);
$addlike->bindValue(':review_id', $review);
$addlike->execute();

?>
