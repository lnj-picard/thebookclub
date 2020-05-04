<?
class Review{
  private $connection;
  private $user_obj;
  private $book_obj;

  public $review_date;

  public function __construct($db){
    $this->connection = $db;
    $this->book_obj = $bookInfos = new Books($db, $_REQUEST['profil']);
  }

  public function submitReview(){
    $this->user_obj = new Users($this->connection, $_SESSION['id']);
    $review_date = date("Y-m-d H:i:s");

    $addReview = $this->connection->prepare("INSERT INTO reviews(book_id, user_id, review_date, body) VALUES (:book_id, :user_id, :review_date, :body)");
    $addReview->bindValue(':book_id', $this->book_obj->getBookId());
    $addReview->bindValue(':user_id', $_SESSION['id']);
    $addReview->bindValue(':review_date', $review_date);
    $addReview->bindValue(':body', $_POST['review_body']);
    $addReview->execute();
  }

  public function displayReviews($data, $limit){
    $page = $data['page'];

    if($page == 1){//if its the first reviews are being loaded we upload the first posts
      $start = 0;
    } else {
      $start = ($page - 1) * $limit;
    }

    $displayStr = '';

    $reqReview = $this->connection->prepare("SELECT * FROM reviews WHERE book_id = :book_id ORDER BY id DESC");
    $reqReview->bindValue(':book_id', $this->book_obj->getBookId());
    $reqReview->execute();
    $reviewBody = $reqReview->fetchAll(PDO::FETCH_ASSOC);

    if($reviewBody > 0){

      $num_iterations = 0; //number of results checked not necessarily posted
      $count = 1;


      foreach ($reviewBody as $value) {

        $this->user_obj = new Users($this->connection, $value['user_id']);
        $userUsername = $this->user_obj->getUsername();
        $reviewContent = $value['body'];

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

        $displayStr .= '
               <div class="review_infos">
                 <div class="review_date"> posted '.$time_message.'</div>
                 <div class="user_name"><a href="profil/'.$userUsername.'">'.$userUsername.'</a> :</div>
                <div class="review_txt">'.$reviewContent.'</div>
                </div>
               ';
      }

      if($count > $limit)
        $displayStr .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
              <input type='hidden' class='noMorePosts' value='false'>";
      else
        $displayStr .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;' class='noMorePostsText'> No more posts to show! </p>";
    } // end of if reviewsBody greater than 0

    echo $displayStr;
  }

  

}
?>
