<?
$user = $_SESSION['id'];

if($_SESSION['logged'] == false){
  header('Location: home');
} else {
  $userInfos = new Users($db->connection, $user);
?>

<div id="user_activity">
  <div id="profil_pic"><img src="<?= $userInfos->getPicture(); ?>"></div>
  <div id="books_read"><p>books</p></div>
  <div id="friends"><p>Friends</p></div>
  <div id="following"><p>Following</p></div>
</div>
<form>
  <textarea class="form_textarea">
  </textarea>
  <input class="form_input" type="text" name="username" placeholder="Username" value="<?= $userInfos->getUsername($user); ?>">
  <input class="form_input" type="email" name="email" placeholder="Email" value="<?= $userInfos->getEmail(); ?>"><br>
  <input class="gender" type="radio" name="gender" value="male"> Male<br>
  <input class="gender" type="radio" name="gender" value="female"> Female<br>
  <input class="gender" type="radio" name="gender" value="other"> Non binary<br>
</form>

<?
}
?>
