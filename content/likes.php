<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../public/css/style.css">
  </head>
  <body>
    <style type="text/css">
      *{
        font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
        line-height: 1.4;
      }
    </style>
<?
session_start();

include '../php/classes/Database.php';
include '../php/classes/Users.php';


//get review id
if(isset($_GET['review_id'])){
  $review_id = $_GET['review_id'];
}

//get number of likes for each review
$get_likes = $db->connection->prepare("SELECT COUNT(*) AS nbr_likes FROM likes WHERE review_id = :review_id");
$get_likes->bindValue(':review_id', $review_id);
$get_likes->execute();
$num_likes = $get_likes->fetch(PDO::FETCH_ASSOC);

//check whether or not the user already liked the review
$check_user_likes = $db->connection->prepare("SELECT COUNT(*) AS user_likes FROM likes WHERE review_id = :review_id AND user_id = :user_id");
$check_user_likes->bindValue(':review_id', $review_id);
$check_user_likes->bindValue(':user_id', $_SESSION ['id']);
$check_user_likes->execute();
$user_liked = $check_user_likes->fetch(PDO::FETCH_ASSOC);

//update Likes
if(isset($_POST['like_button'])){
  $setLikes = $db->connection->prepare("INSERT INTO likes(review_id, user_id) VALUES(:review_id, :user_id)");
  $setLikes->bindValue(':review_id', $review_id);
  $setLikes->bindValue(':user_id', $_SESSION['id']);
  $setLikes->execute();
}

if($user_liked['user_likes'] > 0){
  echo '<form action="likes.php?review_id='.$review_id.'" method="POST">
        <p>You already liked this review</p>
        <div class="like_value">'.$num_likes['nbr_likes'].'</div>
        </form>
        ';

} else {
  echo '<form action="likes.php?review_id='.$review_id.'" method="POST">
        <input type="submit" class="comment_like" name="like_button" value="Like">
        <div class="like_value">'.$num_likes['nbr_likes'].'</div>
        </form>
  ';
}
?>

  </body>
</html>
