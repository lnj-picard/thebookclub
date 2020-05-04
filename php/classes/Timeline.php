<?
class Timeline{
  private $connection;

  //connect to db when a new object is created
  function __construct($db){
    $this->connection = $db;
  }

  function displayFriendsReviews($data, $limit){
    $page = $data['page'];//get the page value from ajax request

    if($page == 1){//if its the first reviews are being loaded we upload the first posts
      $start = 0;//start at the first results of sql request
    } else {
      $start = ($page - 1) * $limit;
    }

    $displayStr = '';//empty variable that will countain string to output

    //create user object with the id of the logged user
    $user_logged_obj = new Users($this->connection, $data['user']);

    //request reviews
    $reqFriendsReviews = $this->connection->prepare("SELECT * FROM reviews ORDER BY id DESC");
    $reqFriendsReviews->execute();
    $allReviews = $reqFriendsReviews->fetchAll(PDO::FETCH_ASSOC);

    if($allReviews > 0){
      $num_iterations = 0; //number of results checked not necessarily posted
      $count = 1;

    foreach($allReviews as $value){//for each reviews get book isbn to use in book obj line 38
      $id = $value['id'];
      $get_isbn = $this->connection->prepare("SELECT isbn FROM books WHERE id = :id");
      $get_isbn->bindValue(':id', $value['book_id']);
      $get_isbn->execute();
      $book_isbn = $get_isbn->fetch(PDO::FETCH_ASSOC);

      $reviewed_book = new Books($this->connection, $book_isbn['isbn']);//get reviewed book infos
      $review_user = new Users($this->connection, $value['user_id']);//get the infos of the user who wrote the review

      if($user_logged_obj->isFriend($value['user_id']) || $data['user'] == $value['user_id']){//if the review is from a friend or user itself

        if($num_iterations++ < $start)
          continue;
        //once 10 posts have been loaded, break
        if($count > $limit){
          break;
        } else {
          $count++;
        }

        //Timeframe
        $date_time_now = date("Y-m-d H:i:s");
        $start_date = new DateTime($value['review_date']); //Time of post
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

        $displayStr .=  "
               <div class='review_infos'>
                 <div class='user_name'><a href='profil/".$review_user->getUsername()."'>".$review_user->getUsername()."</a> commented on ".$reviewed_book->getTitle()." on by ".$reviewed_book->getAuthor()." </div>
                <div class='review_date'> posted ".$time_message."</div>
                <div class='review_txt'>".$value['body']."</div>
                </div>
                <div class='likes'>
                <iframe src='content/likes.php?review_id=$id' scrolling='no'></iframe>
                </div>
                <div class='post_comment'>
                <iframe src='content/comments.php?review_id=$id' id='comment_iframe' frameborder='0'></iframe>
                </div>
                <hr>
               ";
        }// end of isFriend condition
      }//end of foreach loop

      if($count > $limit){//update the page variable use un ajax request with hidden input
        $displayStr .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                        <input type='hidden' class='noMorePosts' value='false'>";
          }
          else {
            $displayStr .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;' class='noMorePostsText'> No more posts to show! </p>";
          }
          echo $displayStr;//echo the review with the right infos
         }
       }
      }
?>
