<?php

require('./configs/database.php');
session_start();

//check if button is clicked

if (isset($_POST['submit'])) {

  //Check if the chebox are check

  $logAdmin= $_POST['log_admin'];
  $error =false;
  $error_email = '';
  $error_password= '';

  $email= htmlspecialchars($_POST['email']);
  $password= htmlspecialchars($_POST['password']);

  if(!empty($email) AND !empty($password)){

    

  if(isset($logAdmin)){
    //Login as Admin

    //Check if the email is reconize in our database*

    $checkEmailExist = $databaseConnexion->prepare('SELECT * from admins where email = ?');

    $checkEmailExist->execute(array($email));

    if($checkEmailExist->rowCount() >=1){

      foreach($checkEmailExist as $result){

        //Check if password and db password are

        if(sha1($password) == $result['password']){
          
          $_SESSION['admin_logged']=true;
          $_SESSION['admin_id']= $result['id'];
          $_SESSION['admin_email']= $result['email'];

          header('location: views/admin/index.php');

        }else{

          $error=true;
          $error_password= 'Password wrong';
        }


      
      }

    }else{
      $error= true;
      $error_email = 'Email not reconize';
    }

  }else{
    //Login as customer
  }
  }else{
    $error= true;
    $error_email= 'Email required';
    $error_password= 'Password required';
  }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Car rental Project</title>
  <link rel="stylesheet" href="./assets/css/login.css">
</head>

<body>

  <h1>Login To Your Dashboard</h1>
  <form method="post">

  <?php
  
  if(isset($error)){?> 
  
    <div class="error"><?= $error_email ?></div>
    <div class="error"><?= $error_password ?></div>
  <?php }

  ?>
    <div class="row">
      <label for="email">Email</label>
      <input type="email" name="email" autocomplete="off" placeholder="email@example.com">
    </div>
    <div class="row">
      <label for="password">Password</label>
      <input type="password" name="password">
    </div>
    <div style="display: flex; color:#8086a9;
  ">
      <input type="checkbox" name="log_admin">
      <label for="">Log as admin</label>
    </div>
    <div style="display: flex; color:#8086a9;
  ">
      <div class="links">
        <a href="/views/customer/signin" class="link">Create an account</a>
        <a href="/views/admin/registration" class="link">Admin account</a>
      </div>
    </div>
    <button type="submit" name="submit">Login</button>
  </form>

</body>

</html>