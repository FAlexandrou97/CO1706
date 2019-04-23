<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="CSS/Style.css">
      <title>Register</title>
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
             <a class="nav-link" href="login.php">Login</a>
           </li>
           <li class="nav-item active">
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
     require 'variables.php';
     session_start();
     ?>

     <?php
     if ( isset( $_SESSION['id'] ) ) {
       header("Location: home.php");
     }
     else {
       $sql = "SELECT * FROM offers";
       $result = $conn->query($sql);
       //Registration Form
       //Association of labels with inputs for increased usability on mobile devices and for visually impaired people.
       echo '<div class="centered">
       <form action="register.php" method="post">
       <div class="form-group">
       <label for="registerUsername">Username</label>
       <input type="text" id="registerUsername" class="form-control" name="username" placeholder="Enter username" required>
       </div>
       <div class="form-group">
       <label for="registerPassword">Password</label>
       <input type="password" name="password" class="form-control" id="registerPassword" placeholder="Enter password" minlength="8" required>
       <small class="form-text text-muted">
       Your password must be at least 8 characters long.
       </small>
       </div>
       <label for="offerSelect">Select pricing plan</label>
       <select class="form-control" id="offerSelect" name="offer" required>
       <option hidden value="">Select pricing plan</option>';
       if (mysqli_num_rows($result) > 0){
         while($row = mysqli_fetch_assoc($result)) {
           echo '<option>' .$row['title'] . '</option>';
         }
       }
       echo '</select>
       <small class="form-text text-muted">Your data is safely encrypted and will not be shared with any 3rd-party application.</small><br>
       <button type="submit" name="register" class="btn btn-primary">Register</button>
       </form>
       </div>
       <div class="text-center fixed-bottom">
       <p id="isMember">Already a member? Click below to login!</p>
       <a href="login.php" class="btn btn-outline-primary" role="button">Login</a>
       </div>';
     }
     ?>

     <!--Handle register -->
     <?php
     if (isset($_POST['register'])) {
       if(isset($_POST['username'], $_POST['password'])){     // if statement for back-end validation.
         $usernameForm = $_POST['username'];
         $passwordForm = $_POST['password'];
         $offerForm = $_POST['offer'];
         $passwordHashed=password_hash($passwordForm, PASSWORD_DEFAULT);
         //Check if username exists in the db.
         $sql = "SELECT username FROM login WHERE username='".htmlspecialchars($usernameForm)."'";
         $result = $conn->query($sql);
         if ($result->num_rows > 0) {
           echo "Username '" .$_POST['username']. "' already exists! Please choose another.";
         }
         else {
           $sql = "INSERT INTO login (username, password, offer) VALUES ('$usernameForm', '$passwordHashed', '$offerForm')";
           $result = $conn->query($sql);
           if ($result) {
             $sql = "SELECT * FROM login WHERE username='".$usernameForm."'";
             $result = $conn->query($sql);
             $row = $result->fetch_assoc();
             $_SESSION['id'] = $row['id'];
             $_SESSION['username'] = $row['username'];
             header("Location: home.php");
           }
         }
       }
       else{
         echo "Something went wrong, try again!";
       }
     }
     ?>

     <!--Logout Functionality -->
     <?php
     if (isset($_POST['logout'])) {
       session_destroy();
       header("Refresh:0");
     }
     ?>

      <!-- Bootstrap JS libraries -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   </body>
</html>
