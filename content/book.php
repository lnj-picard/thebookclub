<?
$book = $_GET['profil'];
$user = $_SESSION['id'];
$bookInfos = new Books($db->connection, $book);
$newReview = new Review($db->connection);

if(isset($_POST['review_book'])){
  $newReview->submitReview();
}
?>
<div id="books">
  <div id="general_infos">
    <div id="book_cover"><img src="public/img/bookcover/<?= $book; ?>.jpg"></div>
    <div id="title_author">
      <p><strong><?= $bookInfos->getTitle(); ?></strong><br><?= $bookInfos->getAuthor(); ?><br>Genre : <?= $bookInfos->getGenre(); ?><br>Rating : x</p>
    </div>
  </div>
  <div id="book_description">
    <p><?= $bookInfos->getSummary(); ?></p>
  </div>
</div>

<form id="review_form" action="book/<?= $book ?>" method="POST">
  <textarea class="form_textarea" id="review_textarea" name="review_body">Tell us what you thought of this book!</textarea>
  <input type="number" min="0" max="5" step=".1" placeholder="Grade this book from 1 to 5">
  <button id="review_btn" type="submit" name="review_book">Add a review</button>
</form>

<div class="book_reviews">
</div>

<script>

$(document).ready(function() {

//original ajax request for loading first posts
$.ajax({
  url: "php/ajax-load-reviews.php",
  type: "POST",
  data: "page=1&profil="+<?=  $_GET['profil']?>
  })
  .done(function(data){
    $('.book_reviews').html(data);
  });

  $(window).scroll(function(){
    var height = $('.book_reviews').height();
    var scroll_top = $(this).scrollTop();
    var page = $('.book_reviews').find('.nextPage').val();
    var noMorePosts = $('.book_reviews').find('.noMorePosts').val();

    //load more posts on scroll
    if(noMorePosts == 'false'){
      var ajaxReq = $.ajax({
        url: "php/ajax-load-reviews.php",
        type: "POST",
        data: "page="+page+"&profil="+<?=  $_GET['profil']?>
      })
      .done(function(response){
        $('.book_reviews').find('.nextPage').remove(); //remove current next page
        $('.book_reviews').find('.noMorePosts').remove();

        $('.book_reviews').append(response);
      });
    }//end of if statement
    return false;
  });
});
</script>
