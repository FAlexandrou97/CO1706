<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Usbwebserver</title>
</head>
<body>
<h1>Page 1</h1>
<p>This is my page.</p>


<?php
$name = "Jon";
$colours = array("blue", "green", "red", "yellow");
echo "Hello " . $name . "<br>";

for ($i = 1; $i<=10; $i++){
	echo "$i <br>";
}

for ($i = 0; $i<count($colours); $i++){
	echo "<p style='color:$colours[$i]'>$colours[$i] </p>";
}
?>

<form action="page2.php" method="post">
<label>Username:
<input type="text" name="username">
</label><br>
<label>Password:
<input type="password" name="password">
</label><br>
<label>Red:
<input type="radio" name="colour" value="red">
</label>
<label>Green:
<input type="radio" name="colour" value="green">
</label>
<label>Blue:
<input type="radio" name="colour" value="blue">
</label><br>
<input type="submit" value="submit">
</form>
</body>

