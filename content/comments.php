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

if(isset($_GET['review_id'])){
  $review_id = $_GET['review_id'];
}

if(isset($_POST['postReview' . $review_id])){
  $post_body = $_POST['post_body'];
  $date_time_now = date("Y-m-d H:i:s");

  $insertComment = $db->connection->prepare("INSERT INTO comments(comment_body, posted_by, date_added, review_id) VALUES (:comment_body, :posted_by, :date_added, :review_id)");
  $insertComment->bindValue(':comment_body', $post_body);
  $insertComment->bindValue(':posted_by', $_SESSION['id']);
  $insertComment->bindValue(':date_added', $date_time_now);
  $insertComment->bindValue(':review_id', $review_id);
  $insertComment->execute();

  echo 'Comment posted!';
}

//get number of comments for each review
$get_number_comments = $db->connection->prepare("SELECT COUNT(*) AS nbr_comments FROM comments WHERE review_id = :review_id");
$get_number_comments->bindValue(':review_id', $review_id);
$get_number_comments->execute();
$comments_num = $get_number_comments->fetch(PDO::FETCH_ASSOC);
?>

  <form action="comments.php?review_id=<? echo $review_id; ?>" id="comment_form" name="postComment<? echo $review_id; ?>" method="POST">
    <textarea name="post_body"></textarea><br>
    <input type="submit" name="postReview<? echo $review_id; ?>" value="Post">
  </form>
  <div id="show_comments"><?= $comments_num['nbr_comments']; ?> comments</div>
  <div id="hide_comments">Hide comments</div>

  <script type="text/javascript" src="../public/js/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#show_comments').click(function(){
        $(this).hide();
        $('#hide_comments').show();
        $('.comment_section').show();
      });
      $('#hide_comments').click(function(){
        $(this).hide();
        $('#show_comments').show();
        $('.comment_section').hide();
      });
    });
  </script>
 </body>
</html>
<?
//load comments
$reqComments = $db->connection->prepare("SELECT * FROM comments WHERE review_id = :review_id ORDER BY id ASC");
$reqComments->bindValue(':review_id', $review_id);
$reqComments->execute();
$allComments = $reqComments->fetchAll(PDO::FETCH_ASSOC);

if($allComments > 0){
  foreach($allComments as $value){
    $user_obj = new Users($db->connection, $value['posted_by']);

    //Timeframe
    $date_time_now = date("Y-m-d H:i:s");
    $start_date = new DateTime($value['date_added']); //Time of post
    $end_date = new DateTime($date_time_now); //Current time
    $interval = $start_date->diff($end_date); //Difference between dates

    if($interval->y >= 1){
      if($interval->y == 1) {$time_message = $interval->y . " year ago"; //1 year ago
      }
      else {
        $time_message = $interval->y . " years ago"; //1+ year ago
      }
    }
    else if ($interval->m >= 1){
      if($interval->d == 0){
        $days = " ago";
      }
      else if($interval->d == 1){
        $days = $interval->d . " day ago";
      }
      else{
        $days = $interval->d . " days ago";
      }

      if($interval->m == 1){
        $time_message = $interval->m . " month ". $days;
      }
      else {
        $time_message = $interval->m . " months ". $days;
      }
    }
    else if($interval->d >= 1){
      if($interval->d == 1){
        $time_message = "Yesterday";
      }
      else {
        $time_message = $interval->d . " days ago";
      }
    }
    else if($interval->h >= 1){
      if($interval->h == 1){
        $time_message = $interval->h . " hour ago";
      }
      else{
        $time_message = $interval->h . " hours ago";
      }
    }
    else if($interval->i >= 1){
      if($interval->i == 1){
        $time_message = $interval->i . " minute ago";
      }
      else{
        $time_message = $interval->i . " minutes ago";
      }
    }
    else{
      if($interval->s < 30){
        $time_message = "Just now";
      }
      else{
        $time_message = $interval->s . " seconds ago";
      }
    }

    echo '<div class="comment_section">
           <p>'.$user_obj->getUsername().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$time_message.'</p>
           <p>'.$value['comment_body'].'</p>
           <hr>
         </div>';
  }
}
?>
