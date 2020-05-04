<div id="landingPage_container">
  <div id="lp_content">
    <div id="lp_logo"><h2>logo</h2></div>
    <div id="lb_slogan">
      <h3>always <b><span class="yellow_accent">book</span></b><br> on the bright side of life</h3>
      <p>join the book club today <i class="fab fa-angellist"></i></p>
    </div>
    <div id="action_btn">
      <div id="signin_btn" class="form_btn">SIGN IN</div>
      <div id="register_btn" class="form_btn">CREATE NEW</div>
    </div>
    <div id="tbc_description">
        <p><i class="fas fa-book-reader"></i>  Track everything you're reading.</p>
        <p><i class="fas fa-user-friends"></i> Meet other passionate people.</p>
        <p><i class="fas fa-star-half-alt"></i>  Talk about what you love.</p>
        <p><i class="fas fa-fire"></i>  Discover new books <br>& so much more!</p>
    </div>
  </div>

  <div class="modals" id="signin_modal">
    <div class="close_modal"><i class="fas fa-arrow-left fa-lg"></i></div>
    <div id="signin_form" class="action_forms">
      <div id="form_title"><h3>SIGN IN</h3></div>

  <!-- USE PHP AND DB TO LOG IN A USER -->
<?
   $error = false;

   if(isset($_POST['signin_form'])){ //if form has been sent
?>
    <script>//keep the modal open when errors
     $('#signin_modal').show();
    </script>
<?
     $username = trim($_POST['username']);
     $password = trim($_POST['password']);

     if(!empty($username) && !empty($password)){//make sure the input fieds are not empty
       $reqMatchUsername = $db->connection->prepare("SELECT COUNT(*) AS nbre_username FROM users WHERE username = :username");
       $reqMatchUsername->bindValue(':username', $username);
       $reqMatchUsername->execute();

       $usernameIn = $reqMatchUsername->fetch(PDO::FETCH_ASSOC);//check is users table if there is already a username matching the username input
       if($usernameIn['nbre_username'] == 1){
         $reqUser = $db->connection->prepare("SELECT * FROM users WHERE username = :username");
         $reqUser->bindValue(':username', $username);
         $reqUser->execute();
        //if the username is already in our db, get all the users's info
         $user = $reqUser->fetch(PDO::FETCH_ASSOC);

         if(password_verify($password, $user['password'])){//check is the password typed in match the password linked to the username in our db
           $_SESSION['logged'] = true;
           $_SESSION['id'] = $user['id'];//if the password match log the user in & get their id

           header('Location: profil');
         } else {
           $error = 'Wrong username or password, please try again.';
         }
       } else {
         $error = 'Wrong username or password, please try again.';
       }
     }//end of checkinf for empty inputs
   }//end of isset
?>
      <form id="signin" method="POST" action="">
        <input class="form_input" type="text" name="username" placeholder="Username" required>
        <input class="form_input" type="password" name="password" placeholder="Password" required>
        <button class="login_btn" type="submit" name="signin_form">Sign in</button>
      </form>
    </div>
  </div>


  <div class="modals" id="register_modal">
    <div class="close_modal"><i class="fas fa-arrow-left fa-lg"></i></div>
    <div id="register_form" class="action_forms">
      <div id="create_new_title"><h3>CREATE NEW</h3></div>

  <!-- CHECK INPUT VALIDITY AND ADD USERS TO DB-->
<?
  $error = false;

  if(isset($_POST['register_form'])){
?>
    <script>
      $('#register_modal').show();
    </script>
<?
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $conf =  trim($_POST['password_conf']);

    if(!empty($email) && !empty($username) && !empty($password) && !empty($conf)){//make sure input are not empty
      $error = array('type' => 'success');

      if(filter_var($email, FILTER_VALIDATE_EMAIL)){//make sure email is valid
        $error = array('type' => 'success');
      } else {
        $error = array('type' => 'error', 'msg' => 'Your email is not valid.');
      }

      if(strlen($username) > 30){//make sure the username isnt too long
        $error = array('type' => 'error', 'msg' => 'Your username is too long. Make sure it\'s bellow 30 charaters');
      } else {
        $error = array('type' => 'success');
      }

      if($password === $conf){//make sure the passwords match
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);//create a hashed password
        $error = array('type' => 'success');
      } else {
        $error = array('type' => 'error', 'msg' => 'Your passwords do not match.');
      }
    }//end of if not empty
    else {
      $error = array('type' => 'error', 'msg' => 'Some fields are empty.');
    } //end if not empty if and ELSE
  }//end isset


    if (isset($error['type']) && $error['type'] == 'success'){//if there are no errors in the form, send data to db
      $reqError = '';
      $date = date("Y-m-d");//get today's date

      //make sure the username doesnt already exist in the database
      $reqCheckUsername = $db->connection->prepare("SELECT COUNT(*) AS nbre_user FROM users WHERE username = :username");
      $reqCheckUsername->bindValue(':username', $username);
      $reqCheckUsername->execute();
      $usernameExists = $reqCheckUsername->fetch(PDO::FETCH_ASSOC);

      //make sure there isent already an account linked to the email
      $reqCheckEmail = $db->connection->prepare('SELECT COUNT(*) AS nbre_mail FROM users WHERE email = :email');
      $reqCheckEmail->bindValue(':email', $email);
      $reqCheckEmail->execute();
      $emailExists = $reqCheckEmail->fetch(PDO::FETCH_ASSOC);

      //update the db if email and username are not already in it
      if($usernameExists['nbre_user'] > 0){
        $reqError = 'This username is already taken, please try another one.';
      } elseif($emailExists['nbre_mail'] > 0){
        $reqError = 'There already is an account linked to this email, please try loging in instead.';
      } else {
        $confirmKey = md5($username);//create a unique confirmKey based on the username
        $rand = rand(1, 2); //create random number between one and two
        if($rand == 1){
          $profil_pic = "public/img/profil_pictures/default_blue.jpg";
        } else {
          $profil_pic = "public/img/profil_pictures/default_yellow.jpg";
        }

        echo $profil_pic;

        $reqInsertUsers = $db->connection->prepare("INSERT INTO users (email, username, password, register_date, confirmkey, profil_pic) VALUES (:email, :username, :password, :register_date, :confirmkey, :profil_pic)");
        $reqInsertUsers->bindValue(':email', $email);
        $reqInsertUsers->bindValue(':username', $username);
        $reqInsertUsers->bindValue(':password', $hashed_password);
        $reqInsertUsers->bindValue(':register_date', $date);
        $reqInsertUsers->bindValue(':confirmkey', $confirmKey);
        $reqInsertUsers->bindValue(':profil_pic', $profil_pic);

        $reqInsertUsers->execute();

        $_SESSION['logged'] = true;

        header('Location: timeline');
      }
    }
?>
      <form id="create_new" method="POST" action="index.php">
        <input class="form_input" type="email" name="email" placeholder="Email" value="<? if(isset($email)){ echo $email;}?>" required>
        <input class="form_input" type="text" name="username" placeholder="Username" value="<? if(isset($username)){ echo $username;}?>" required>
        <input class="form_input" type="password" name="password" placeholder="Password" value="<? if(isset($password)){ echo $password;}?>" required>
        <input class="form_input" type="password" name="password_conf" placeholder="Password confirmation" value="<? if(isset($conf)){ echo $conf;}?>" required>
        <button class="login_btn" type="submit" name="register_form">Create new</button>
      </form>
    </div>
  </div>

</div>

<script>
  $('#signin_btn').click(function(){
    $('#signin_modal').show();
  });
  $('.close_modal').click(function(){
    $('.modals').hide();
  });
  $('#register_btn').click(function(){
    $('#register_modal').show();
  });
</script>
