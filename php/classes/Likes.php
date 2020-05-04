<?
class Likes{
  private $connection;
  private $review_obj;

  public function __construct($db){
    $this->connection = $db;
  }

  public function addLike($user, $review){
    $addlike = $this->connection->prepare("INSERT INTO likes (review_id) VALUES (:review_id)");
    $addlike->bindValue(':user_id', $user);
    $addlike->bindValue(':review_id', $review);
    $addlike->execute();
  }

  public function getLikes($review_id){
    $reqLikes = $this->connection->prepare("SELECT COUNT(*) AS nbr_likes FROM likes WHERE review_id = :review_id");
    $reqLikes->bindValue(':review_id', $review_id);
    $reqLikes->execute();
    $nbrLikes = $reqLikes->fetch(PDO::FETCH_ASSOC);

    return $nbrLikes['nbr_likes'];
  }

  public function isLiked($user_id, $review_id){
    $reqUserLikes = $this->connection->prepare("SELECT COUNT(*) AS nbr_user_like FROM likes WHERE review_id = :review_id AND user_id = :user_id");
    $reqUserLikes->bindValue('review_id', $review_id);
    $reqUserLikes->bindValue('user_id', $user_id);
    $reqUserLikes->execute();

    $userLikes = $reqUserLikes->fetch(PDO::FETCH_ASSOC);

    if($userLikes['nbr_user_like'] > 0){
      return true;
    } else {
      return false;
    }
  }
}
?>
