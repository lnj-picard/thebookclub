<?
class Database {

  private $host;
  private $username;
  private $dbname;
  private $password;

  public $connection;

  public function getConnection(){
    $this->connection = null;
    try {
      $this->connection = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->username, $this->password, array(
    		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    	));
    }

    catch(PDOException $exception) {
      echo 'Error: '.$exception->getMessage();
    }

    return $this->connection;
  }
  public function hydrate($data){
    $this->host = $data['host'];
    $this->dbname = $data['dbname'];
    $this->username = $data['username'];
    $this->password = $data['password'];
  }
}

$db = new Database;
$db->hydrate(array(
  'host' => 'localhost',
  'dbname' => 'thebookclub',
  'username' => 'root',
  'password' => ''
));

$db->getConnection();
?>
