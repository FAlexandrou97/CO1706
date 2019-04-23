<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="https://vesta.uclan.ac.uk/~falexandrou/CSS/Style.css">
     <title>Album Description</title>
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

     <?php
     if (isset($_GET['track_id'])) {
       $track_id = $_GET["track_id"];
       $sql = "SELECT * FROM tracks WHERE album = (SELECT album from tracks WHERE track_id = '$track_id')";
       $result = $conn->query($sql);
       if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);
         echo '<div class="container">
         <div class="row"><div id="albumCol" class="col-6 h-25 p-2 position-sticky">'.
         "<img src=https://vesta.uclan.ac.uk/~falexandrou/".$row["image"]." class='img-fluid' alt='album image'>
         <h4 class='font-weight-normal'>".$row['album']."</h4>
         <p>By ".$row['artist']."</p>
         </div><div class='col p-1'>
         <p>Tracks:</p>";
         $resultTracks = $conn->query($sql);
         while ($track = mysqli_fetch_assoc($resultTracks)) {
           echo '<p>'.$track['name']. "<audio class='player' controls><source src=".'https://vesta.uclan.ac.uk/~falexandrou/' .$track["sample"]. " type='audio/mpeg'></audio>".'</p>';
         }
         echo "</div></div></div>";
       }
     }
     ?>



     <!-- Bootstrap JS libraries -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   </body>
</html>
