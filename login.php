
<form action="login.php" method="POST">
<h1>Login</h1>
Username:
<input type="text" name="user_name"><br>
Password:
<input type="text" name="password"><br></br>
<input type="submit">
<input type=button onClick="location.href='create_user.php'" value='Create user'>
</form>


<?php
session_start();
if (isset($_POST["user_name"]) && isset($_POST["password"])) {
   $servername = "localhost:3306";
   $username = "root";
   $serverpassword = "password";
   // Create connection
   $conn = new mysqli($servername, $username, $serverpassword);
   // Check connection
   if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
    }
    $loginpassword = $_POST["password"];
	$sql = "SELECT AES_DECRYPT(password,SHA2(CONCAT('$loginpassword',salt),256),salt) FROM 18022038d.user WHERE username='"
		.$_POST["user_name"]."';";

    $resultSet = $conn->query($sql);
      if (!$resultSet) {
      trigger_error('Invalid query: ' . $conn->error);
      }

      if ($resultSet->num_rows > 0) {
        $row = $resultSet ->fetch_row(); //fetch a result row as an associative array
        if($row[0]==$loginpassword){

          $_SESSION['username'] = $_POST["user_name"];
          $_SESSION['password'] = $password;
          header( "Location: main.php" );
        }
      }
      else {
        echo "<h2>Invalid username/password!</h2>";
        echo "<h2>Invalid username/password!</h2>";
      }

	$conn->close();
}
?>

<style>form {
  margin:0 auto;
  width:300px
}
input {
  margin-bottom:3px;
  padding:10px;
  width: 100%;
  border:1px solid #CCC
}
button {
  padding:10px
}
label {
  cursor:pointer
}
#form-switch {
  display:none
}
#register-form {
  display:none
}
#form-switch:checked~#register-form {
  display:block
}
#form-switch:checked~#login-form {
  display:none
}</style>