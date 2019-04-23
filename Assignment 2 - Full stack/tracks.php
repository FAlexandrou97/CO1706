<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="CSS/Style.css">
      <title>Tracks</title>
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
               <a class="dropdown-item active" href="tracks.php">My Tracks</a>
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
     </nav>

     <?php
     //Variables
     require 'variables.php';
     session_start();
     ?>

     <!-- Code that executes only for authorized users -->
     <!-- Output all tracks -->
     <?php
     if ( isset( $_SESSION['id'] ) ) {
       $sql = "SELECT * FROM tracks";
       $result = $conn->query($sql);
       //The 3 filtering options
       echo '<select class="custom-select mt-2" id="genreSelect" onChange="filterTracksGenre()">
       <option hidden>Select Genre</option>
       <br/>
       <option value="Rap">Rap</option>
       <br/>
       <option value="Rock">Rock</option>
       <br/>
       <option value="R and B">R and B </option>
       <br/>
       <option value="Indie">Indie</option>
       <br/>
       <option value="Dance">Dance</option>
       <br/>
       </select>
       <input class="form-control my-2" type="text" id="albumInput" onkeyup="filterTracksAlbum()" placeholder="Search by Album..">
       <input class="form-control my-2 type="text" id="artistInput" onkeyup="filterTracksArtist()" placeholder="Search by Artist..">
       <br>';

       //-----------------------------//
       // Track recommendation system //
       //-----------------------------//
       if (isset($_SESSION['id'])) {
         $username = $_SESSION['username'];
         // Create a temporary table with the highest rated tracks of the current user sorted descending
         $sqlTempTable = "CREATE TEMPORARY TABLE top_ratings AS (SELECT product_id, name, rating FROM reviews WHERE name = '$username' ORDER BY rating DESC)";
         $resultTempTable = $conn->query($sqlTempTable);
         // Randomly select (track_id) one of the first three highest ratings
         $sqlRandSelect = "SELECT * FROM top_ratings WHERE rating > 6 ORDER BY RAND() LIMIT 3";
         $resultRandSelect= $conn->query($sqlRandSelect);
         if(mysqli_num_rows($resultRandSelect) > 0) {
           $rowRandSelect = mysqli_fetch_assoc($resultRandSelect);
           $votedTrackID = $rowRandSelect['product_id'];
           // Select tracks of a specified artist with a high rating given by the user
           $sqlRecommend = "SELECT * FROM tracks where artist = (SELECT artist FROM tracks WHERE track_id = '$votedTrackID')";
           $resultRecommend = $conn->query($sqlRecommend);
           if(mysqli_num_rows($resultRecommend) > 0) {
             $counter=0;
             echo '<div class="border m-1"><h3 class="font-weight-light text-center">Recommended Tracks:</h3></br><div class="container-fluid my-2">';
             while($rowRecommend = mysqli_fetch_assoc($resultRecommend) and $counter < rand(3,7)) {
               $counter++;
               echo "<p class='text-center'><a href='https://vesta.uclan.ac.uk/~falexandrou/trackDesc.php/?track_id=".$rowRecommend['track_id']."'.>".$rowRecommend['name']."</a></p><hr class='mx-3'>";
             }
             echo 'By: '. $rowRecommend['artist'].'</div></div><br/>';
           }
         }
       }

       // Display All Tracks
       echo '<div id="tracks" class="container px-0"><h3 class="font-weight-normal text-center">All Tracks:</h3><br/>';
       if (mysqli_num_rows($result) > 0) {
         while($row = mysqli_fetch_assoc($result)) {
           $tempTrackID = $row["track_id"];
           $sqlPlaylist = "SELECT tracks.*, playlist.* FROM tracks, playlist WHERE $tempTrackID = playlist.track_id";
           $resultPlaylist =$conn->query($sqlPlaylist);
           echo  '<div class="row justify-content-center"><div class="col">'.
           "<img src=" .$row["thumb"]. " class='img-thumbnail img-fluid' alt='album artwork'>".'<br/>'.
           '<span class="artist">'.$row["artist"].'</span><br/>'.
           "<a class='album' href='https://vesta.uclan.ac.uk/~falexandrou/album.php/?track_id=".$row['track_id']."'.>".$row['album']."</a>".'<br/>'.

           '</div><div class="col">'.
           "<a href='https://vesta.uclan.ac.uk/~falexandrou/trackDesc.php/?track_id=".$row['track_id']."'.>".$row['name']."</a>".
           '<span class="badge badge-primary mx-2 genre">'.$row["genre"].'</span><br/>'.
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
       } else {
         echo "0 results";
       }
     }
     else
     header("Location: index.php");
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


     <!-- Table Filtering Functions -->
     <!--Table Filtering Function source: https://www.w3schools.com/howto/howto_js_filter_table.asp  -->
     <script>
     function filterTracksGenre() {
       // Declare variables
       var select, option, filter, container, row, column, genre, i, txtValue;
       select = document.getElementById("genreSelect");
       option = select.options[select.selectedIndex].value;
       filter = option.toUpperCase();
       container = document.getElementById("tracks");
       row = container.getElementsByClassName("row");
       hr = container.getElementsByTagName("hr");
       // Loop through all table rows, and hide those who don't match the search query
       for (i = 0; i < row.length; i++) {
         column = row[i].getElementsByClassName("col")[1];
         genre = column.getElementsByClassName("genre")[0];
         if (genre) {
           txtValue = genre.textContent || genre.innerText;
           if (txtValue.toUpperCase().indexOf(filter) > -1) {
             row[i].style.display = "";
             hr[i].style.display = "";
           } else {
             row[i].style.display = "none";
             hr[i].style.display = "none";
           }
         }
       }
     }

     function filterTracksAlbum(){
       // Declare variables
       var select, input, filter, container, row, column, album, i, txtValue;
       input = document.getElementById("albumInput").value;
       filter = input.toUpperCase();
       container = document.getElementById("tracks");
       row = container.getElementsByClassName("row");
       hr = container.getElementsByTagName("hr");
       // Loop through all table rows, and hide those who don't match the search query
       for (i = 0; i < row.length; i++) {
         column = row[i].getElementsByClassName("col")[0];
         album = column.getElementsByClassName("album")[0];
         if (album) {
           txtValue = album.textContent || album.innerText;
           if (txtValue.toUpperCase().indexOf(filter) > -1) {
             row[i].style.display = "";
             hr[i].style.display = "";
           } else {
             row[i].style.display = "none";
             hr[i].style.display = "none";
           }
         }
       }
     }

     function filterTracksArtist(){
       // Declare variables
       var select, input, filter, container, row, column, artist, i, txtValue;
       input = document.getElementById("artistInput").value;
       filter = input.toUpperCase();
       container = document.getElementById("tracks");
       row = container.getElementsByClassName("row");
       hr = container.getElementsByTagName("hr");
       // Loop through all table rows, and hide those who don't match the search query
       for (i = 0; i < row.length; i++) {
         column = row[i].getElementsByClassName("col")[0];
         artist = column.getElementsByClassName("artist")[0];
         if (artist) {
           txtValue = artist.textContent || artist.innerText;
           if (txtValue.toUpperCase().indexOf(filter) > -1) {
             row[i].style.display = "";
             hr[i].style.display = "";
           } else {
             row[i].style.display = "none";
             hr[i].style.display = "none";
           }
         }
       }
     }
     </script>


     <!-- Bootstrap JS libraries -->
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

   </body>
</html>
