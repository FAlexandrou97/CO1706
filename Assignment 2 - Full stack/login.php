<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="CSS/Style.css">
      <title>Login</title>
   </head>
   <body>
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <a class="navbar-brand" href="index.php">SoundBound</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
           <li class="nav-item">
             <a class="nav-link" href="index.php">Home</a>
           </li>
           <li class="nav-item">
             <a class="nav-link active" href="login.php">Login</a>
           </li>
           <li class="nav-item">
             <a class="nav-link" href="register.php">Register</a>
           </li>
         </ul>
         <form action="search.php" method="get" class="form-inline my-2 my-lg-0">
           <input class="search-input form-control mr-sm-2" name="query" type="text" placeholder="Search" aria-label="Search" required>
         </form>
       </div>
     </nav>

     <?php
     //Variables
     //test
     //$sql = "SELECT * FROM login WHERE username='".$usrnm."' AND password='".$passwd."'";
     require 'variables.php';
     session_start();
     ?>
     <?php
     if ( isset( $_SESSION['id'] ) ) {
       header("Location: home.php");
     }
     else {

       echo '<h3 class="loginHeader font-weight-normal">Enter Your Credentials</h3>
       <div class="centered">
       <form action="login.php" method="post">
       <div class="form-group">
       <label for="loginUsername">Username</label>
       <input type="text" id="loginUsername" class="form-control" name="username" placeholder="Enter username" required>
       </div>
       <div class="form-group">
       <label for="loginPassword">Password</label>
       <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Enter password" required>
       </div>
       <button type="submit" name="login" class="btn btn-primary">login</button>
       </form>
       </div>
       <div class="text-center fixed-bottom">
       <p>Don′t have an account? Click below to register!</p>
       <a href="register.php" class="btn btn-outline-primary" role="button">Register</a>
       </div>';
     }
     ?>
     <?php
     if (isset($_POST['logout'])) {
       session_destroy();
       header("Refresh:0");
     }
     ?>
     <!--Handle Login -->
     <?php
     if (isset($_POST['login'])) {
       if(isset($_POST['username'], $_POST['password'])){
         $usernameForm = $_POST["username"];
         $passwordForm = $_POST["password"];
         $sql = "SELECT * FROM login WHERE username='".htmlspecialchars($usernameForm)."'";
         $result = $conn->query($sql);
         //Verify Hashed Password (one of the safest methods)
         if (mysqli_num_rows($result) > 0) {
           $row = $result->fetch_assoc();
           if(password_verify($passwordForm, $row['password'])){
             echo "Logged in Successfully!!";
             "<br>";
             $_SESSION['id'] = $row['id'];
             $_SESSION['username'] = $row['username'];
             header("Location: home.php");
           }
           else{
             echo "<h3 class='text-danger text-center font-weight-light'>Check Your Password!</h3>";
           }
         }
         else {
           echo "<h3 class='text-danger text-center font-weight-light'>No Username Found!</h3>";
         }
       }
     }
     ?>
      <!-- Bootstrap JS libraries -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   </body>
</html>
