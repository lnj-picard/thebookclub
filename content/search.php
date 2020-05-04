<div id="search_bar">
  <input type="text" id="search_input" name="search_bar" placeholder="Find a new book...">
  <ul id="search_results"></ul>
</div>

<script>
var searchTimeout;

$('#search_input').on('keyup', function(e){
  var value = $(this).val();
  clearTimeout(searchTimeout);

  if(value.length > 3){
    searchTimeout = setTimeout(function(){

      $.ajax({
        type: 'POST',
        url: 'php/search-db.php',
        data: {
          keyword: value
        },
        dataType: 'JSON'
      })
      .done(function(data){
        $('#search_results').html('');//empty the search results ul

        data.forEach(function(val){
            $('<li class="search_bar_results"><a href="book/'+val.isbn+'"><p><strong>'+val.title+'</strong></p><br><p>'+val.author+'</p></a></li>').appendTo($('#search_results'));
        });
      });

    }, 200);
  } else {
    $('#search_results').html('');
  }
});
</script>
