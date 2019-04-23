<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="CSS/Style.css">
     <title>Welcome</title>
   </head>
   <body>
     <!-- Navigation Bar Source: https://getbootstrap.com/docs/4.0/components/navbar/ -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <a class="navbar-brand" href="index.php">SoundBound</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
           <li class="nav-item active">
             <a class="nav-link" href="index.php">Home</a>
           </li>
           <li class="nav-item">
             <a class="nav-link" href="login.php">Login</a>
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
     require 'variables.php';
     session_start();
     ?>

     <?php
     //Check if user is logged in.
     if ( isset( $_SESSION['id'] ) ) {
       header("Location: home.php");
     }
     else {
       //Render website
       echo '<h1 class="display-4 text-center">Unlimited Music</h1>';
       echo '<div class="row my-5 justify-content-center">
       <a href="register.php" class="btn btn-primary btn-lg" role="button">Get SoundBound Now</a>
       </div>';
       //Show offers
       $sql = "SELECT * FROM offers";
       $result = $conn->query($sql);

       if (mysqli_num_rows($result) > 0) {
         // output data of each row
         echo '<div class="row justify-content-center">';
         while($row = mysqli_fetch_assoc($result)) {
           echo '<div class="card my-2">'
           ."<img src=".$row["image"]." class='card-img-top' alt='offer image'>".
           '<div class="card-body">
           <p class="card-text">'.$row['description']. '<br>Price: ' .$row['price']. '</p>
           </div>
           </div>';
         }
         echo '</div>';
       } else {
         echo "0 results";
       }
     }
     ?>
     <!-- Bootstrap JS libraries -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   </body>
</html>
