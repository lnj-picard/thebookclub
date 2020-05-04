<?
class Books{
  private $connection;
  public $book;

  public function __construct($db, $book) {
    $this->connection = $db;
    $reqAll = $this->connection->prepare("SELECT * FROM books WHERE isbn = :isbn");
    $reqAll->bindValue(':isbn', $book);
    $reqAll->execute();
    $this->book = $reqAll->fetch(PDO::FETCH_ASSOC);
  }

  public function getBookId(){
    return $this->book['id'];
  }

  public function getTitle(){
    return $this->book['title'];
  }

  public function getAuthor(){
    return $this->book['author'];
  }

  public function getGenre(){
    return $this->book['genre'];
  }

  public function getSummary(){
    return $this->book['summary'];
  }
}
?>
