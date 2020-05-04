<div class="book_reviews">
</div>

<script>

$(document).ready(function() {

//original ajax request for loading first posts
$.ajax({
  url: "php/ajax-load-friends-reviews.php",
  type: "POST",
  data: "page=1&user="+<?=  $_SESSION['id']; ?>
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
        url: "php/ajax-load-friends-reviews.php",
        type: "POST",
        data: "page="+page+"&user="+<?=  $_SESSION['id']; ?>
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
