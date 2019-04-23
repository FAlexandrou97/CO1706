<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" type="text/css" href="CSS/Style.css">
     <title>Playlist</title>
  </head>
  <body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              My Music
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="tracks.php">My Tracks</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item active" href="playlist.php">My Playlist</a>
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
    <form method='post'><input class='btn btn-secondary' type='submit' name='randomTracks' value='Generate Random Tracks'>
      <small class="form-text text-muted">Generate between 5 and 25 random tracks!</small></form>
      <form method='post'><input class='btn btn-danger' type='submit' name='deleteTracks' value='Delete all Tracks'>
        <small class="form-text text-muted">Deletes all tracks without warning!</small></form>

        <?php
        $sql = "SELECT * FROM playlist";
        $result = $conn->query($sql);
        echo '<div id="tracks" class="container px-0"><h3 class="font-weight-normal text-center">My Playlist</h3><br/>';
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
            echo "<form method='post'><input class='btn btn-outline-secondary' type='submit' name='removeFromPlaylist' value='Remove From playlist'>";
            echo "<input type='hidden' value='$tempTrackID' name='trackID'/></form>";
            echo '</div>'; //column div
            echo  '</div><hr class="my-3 mx-3">'; //row div
          }
          echo '</div>'; //container div
        }
        ?>

        <!--Remove a track from playlist  -->
        <?php
        if (isset($_POST['removeFromPlaylist'])) {
          $sql = "DELETE FROM playlist WHERE ". $_POST['trackID'] ." = track_id";
          $result = $conn->query($sql);
          echo "<meta http-equiv='refresh' content='0'>";
        }

        // Delete all tracks from playlist
        if (isset($_POST['deleteTracks'])) {
          $sql = "DELETE FROM playlist";
          $result = $conn->query($sql);
          echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>

        <!-- Generate random tracks -->
        <?php
        if (isset($_POST['randomTracks'])) {
          for($i = 0; $i<= rand(5,25); $i++){
            $sql = "INSERT INTO playlist(track_id, artist, album, description, name, genre, image, thumb, sample)
            SELECT track_id, artist, album, description, name, genre, image, thumb, sample FROM tracks WHERE track_id = ceil(rand() * 122)";
            $result = $conn->query($sql);
          }
          echo "<meta http-equiv='refresh' content='0'>";
        }
        ?>

        <!-- Bootstrap JS libraries -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

 </body>
</html>
