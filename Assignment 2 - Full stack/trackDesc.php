<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="https://vesta.uclan.ac.uk/~falexandrou/CSS/Style.css">
     <title>Track Description</title>
   </head>
   <body>
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <a class="navbar-brand" href="https://vesta.uclan.ac.uk/~falexandrou/home.php">SoundBound</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
           <li class="nav-item ">
             <a class="nav-link" href="https://vesta.uclan.ac.uk/~falexandrou/home.php">Home</a>
           </li>
           <li class="nav-item dropdown active">
             <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               My Music
             </a>
             <div class="dropdown-menu" aria-labelledby="navbarDropdown">
               <a class="dropdown-item" href="https://vesta.uclan.ac.uk/~falexandrou/tracks.php">My Tracks</a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" href="https://vesta.uclan.ac.uk/~falexandrou/playlist.php">My Playlist</a>
             </div>
           </li>
           <li class="nav-item ">
             <a class="btn btn-outline-light my-2 my-sm-0" href="https://vesta.uclan.ac.uk/~falexandrou/logout.php" role="Button">Logout</a>
           </li>
         </ul>
         <form action="https://vesta.uclan.ac.uk/~falexandrou/search.php" method="get" class="form-inline my-2 my-lg-0">
           <input class="search-input form-control mr-sm-2" name="query" type="text" placeholder="Search" aria-label="Search" required>
         </form>
       </div>
     </nav>

     <?php
     //Variables
     require 'variables.php';
     session_start();
     //Check if user is logged in.
     if ( isset( $_SESSION['id'] ) ) {

     }   else {
       header("Location: index.php");
     }
     ?>

     <!-- Handle review posting -->
     <?php
     if(isset($_POST['review'])){
       if(isset($_POST['comment'], $_POST['rating'])){         // if statement for back-end validation
         $track_id = $_GET['track_id'];
         $username = $_SESSION['username'];
         $comment = htmlspecialchars($_POST['comment']);
         $rating = $_POST['rating'];
         $sql="INSERT into reviews (product_id, name, review, rating) VALUES ('$track_id','$username', '$comment', '$rating')";
         $result = $conn->query($sql);
         mysqli_close($conn);
         if ($result) {
           header("Location: https://vesta.uclan.ac.uk/~falexandrou/trackDesc.php/?track_id=".$track_id);  //Duplicate form submission avoidance
         }
       }
       else {
         echo 'Make sure you typed your comment and rated correctly!!';
       }
     }
     ?>

     <?php
     if (isset($_GET['track_id'])) {
       $reviewSum = 0;
       $reviewCounter = 0;
       $track_id = $_GET["track_id"];
       $sql = "SELECT * FROM tracks WHERE track_id = '$track_id'";
       $result = $conn->query($sql);
       if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);
         echo '<div class="jumbotron border border-secondary rounded">
         <h1 class="display-4">'.$row['name'].'</h1>
         <p>By '.$row['artist'].'</p>
         <hr class="my-4">
         <p class="lead">'.$row['description'].'</p>
         </div>';

         $sqlReviews = "SELECT * FROM reviews WHERE product_id = '$track_id'";
         $resultReviews = $conn->query($sqlReviews);
         if (mysqli_num_rows($resultReviews) > 0) {
           echo "<h2>Reviews: </h2>";
           while($rowReview = mysqli_fetch_assoc($resultReviews)){
             $reviewSum+=(int)$rowReview['rating'];
             $reviewCounter++;
             echo "<div class='mx-2'><img src='https://img.icons8.com/ios-glyphs/30/000000/user.png' class='border border-dark rounded-circle mr-2'>".$rowReview['name'];
             echo '<br/>Rating: '.$rowReview['rating'].'/10'.'<br/><p class="comment">'.$rowReview['review']."</p></div>";
             echo '<hr class="my-3 mx-3">';
           }
           $reviewAvg = $reviewSum/$reviewCounter;
           echo "<p>Track Average Rating: " . number_format((float)$reviewAvg, 2, '.', '').'</p>';
         }
         echo '<form class="form-group" action="" method="post">
         <label class="sr-only" for="inlineFormInputName2">Name</label>
         <input class="form-control" type="text" placeholder="User: '.$_SESSION["username"].'" readonly>
         <label for="comment">Comment:</label>
         <textarea name="comment" id="comment" class="form-control" rows="3" placeholder= "Please share your opinion.." required></textarea>
         <input type="number" name="rating" class="form-control" placeholder="Rating" step="1" min="1" max="10" required>
         <small class="form-text text-muted">
         The rating must range between the numbers 1 and 10.
         </small>
         <button type="submit" name="review" class="btn btn-primary mb-2">Submit</button>
         </form>';
       }
     }
     ?>
        
     <!-- Bootstrap JS libraries -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   </body>
</html>
