<!doctype html>
<html lang="en">
   <head>
     <!-- <link rel="stylesheet" type="text/css" href="CSS/Styles.css"> -->
      <meta charset="utf-8">
      <!-- Required meta tags -->
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="CSS/Style.css">
      <title>Search</title>

   </head>
   <body>

     <?php
     //Variables
     require 'variables.php';
     session_start();
     ?>
     <!-- Navigation Bar -->
     <?php
     if ( isset( $_SESSION['id'] ) ) {
       echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
       <a class="navbar-brand" href="home.php">SoundBound</a>
       <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
       </button>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
       <ul class="navbar-nav mr-auto">
       <li class="nav-item ">
       <a class="nav-link" href="home.php">Home</a>
       </li>
       <li class="nav-item dropdown">
       <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       My Music
       </a>
       <div class="dropdown-menu" aria-labelledby="navbarDropdown">
       <a class="dropdown-item" href="tracks.php">My Tracks</a>
       <div class="dropdown-divider"></div>
       <a class="dropdown-item" href="playlist.php">My Playlist</a>
       </div>
       </li>
       <li class="nav-item ">
       <a class="btn btn-outline-light my-2 my-sm-0" href="logout.php" role="Button">Logout</a>
       </li>
       </ul>
       <form action="search.php" method="get" class="form-inline my-2 my-lg-0">
       <input class="search-input form-control mr-sm-2" name="query" type="text" placeholder="Search" aria-label="Search" required>
       </form>
       </div>
       </nav>';
     }
     else {
       echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
       <li class="nav-item">
       <a class="nav-link" href="register.php">Register</a>
       </li>
       </ul>
       <form action="search.php" method="get" class="form-inline my-2 my-lg-0">
       <input class="search-input form-control mr-sm-2" name="query" type="text" placeholder="Search" aria-label="Search" required>
       </form>
       </div>
       </nav>';
     }
     ?>

     <h3 class="text-center m-4">Search Results</h3>

     <!-- Handle php search function -->
     <?php
     if ( isset( $_SESSION['id'] ) ) {
       $searchText = $_GET['query'];
       $sql = "SELECT * FROM tracks WHERE artist LIKE '%$searchText%'
       OR album LIKE '%$searchText%'
       OR name LIKE '%$searchText%'";
       $result = $conn->query($sql);
       if (mysqli_num_rows($result) > 0) {
         echo '<div id="tracks" class="container">';
         if (mysqli_num_rows($result) > 0) {
           while($row = mysqli_fetch_assoc($result)) {
             $tempTrackID = $row["track_id"];
             $sqlPlaylist = "SELECT tracks.*, playlist.* FROM tracks, playlist WHERE $tempTrackID = playlist.track_id";
             $resultPlaylist =$conn->query($sqlPlaylist);
             echo  '<div class="row justify-content-center"><div class="col">'.
             "<img src=" .$row["thumb"]. " class='img-thumbnail img-fluid'>".'<br/>'.
             '<span class="artist">'.$row["artist"].'</span><br/>'.
             "<a class='album' href='https://vesta.uclan.ac.uk/~falexandrou/album.php/?track_id=".$row['track_id']."'.>".$row['album']."</a>".'<br/>'.

             '</div><div class="col">'.
             "<a href='https://vesta.uclan.ac.uk/~falexandrou/trackDesc.php/?track_id=".$row['track_id']."'.>".$row['name']."</a>".
             '<span class="badge badge-primary mx-2">'.$row["genre"].'</span><br/>'.
             "<audio class='player' controls><source src=" .$row["sample"]. " type='audio/mpeg'></audio>".'<br/>';
             //Code that checks whether a track already exists in playlist.
             if(mysqli_num_rows($resultPlaylist) > 0){
               echo "<form method='post'><input class='btn btn-secondary' disabled type='submit' name='added' value='Added'></form>". '</td>';
             }
             else{
               echo "<form method='post'><input class='btn btn-outline-secondary' type='submit' name='addToPlaylist' value='Add to playlist'>";
               echo "<input type='hidden' value='$tempTrackID' name='trackID'/></form>". '</td>';
             }
             echo '</div>'; //column div
             echo  '</div><hr class="my-3 mx-3">'; //row div
           }
           echo '</div>'; //container div
         }
       }
     }
     else {
       echo "<h4 class='font-weight-normal text-center'>You must be logged in to use the search function!</h4>";
     }
     ?>

     <!--Add track to playlist -->
     <?php
     if (isset($_POST['addToPlaylist'])) {

       $sql = "INSERT INTO playlist(track_id, artist, album, description, name, genre, image, thumb, sample)
       SELECT track_id, artist, album, description, name, genre, image, thumb, sample FROM tracks WHERE ". $_POST['trackID'] ." = track_id";
       $result = $conn->query($sql);
       echo "<meta http-equiv='refresh' content='0'>";
     }
     ?>

     <!-- Bootstrap JS libraries -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   </body>
</html>
