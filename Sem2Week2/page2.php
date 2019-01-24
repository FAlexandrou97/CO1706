<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Usbwebserver</title>
</head>
<body>
<p>Hello <?php echo $_POST["username"];?></p>
<?php

//if password == password then print rubbish password.
if ($_POST["password"] == "password"){
	echo "This is a rubbish password";
}

//Show cat picture.
if ($_POST["username"] == "cat" && $_POST["password"] == "grumpy"){
	echo '<img src="images/cat.jpg">';
}

//Change the background colour according to the radio button selected.
echo '<style type="text/css">
        body {
            background-color:' . $_POST["colour"] .';}</style>';
?>

</body>