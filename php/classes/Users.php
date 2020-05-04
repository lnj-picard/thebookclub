<?
class Users{
  private $connection;
  public $user;

  public function __construct($db, $user) {
    $this->connection = $db;
    $reqAll = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
    $reqAll->bindValue(':id', $user);
    $reqAll->execute();
    $this->user = $reqAll->fetch(PDO::FETCH_ASSOC);
  }

  public function getUsername(){
    return $this->user['username'];
  }

  public function getEmail(){
    return $this->user['email'];
  }

  public function getPicture(){
    return $this->user['profil_pic'];
  }

  public function isFriend($user_to_check){
    $usernameComma = ','.$user_to_check.',';
    if(strstr($this->user['friends_array'], $usernameComma) || $user_to_check == $this->user['username']){
      return true;
    } else {
      return false;
    }
  }
}
?>
