
<?php
session_start();
$password =  $_SESSION['password'];
$userloginname =  $_SESSION['username'];
if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
  header ("Location: login.php");
  }
?>

<?php
   $servername = "localhost:3306";
   $username = "root";
   $serverpassword = "password";
   // Create connection
   $conn = new mysqli($servername, $username, $serverpassword);
   // Check connection
   if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
    }
    $username =  $_SESSION['username'];
    $noteid = $_GET['noteid'];
    $sql = "SELECT noteid, title, content ,salt, encrypted FROM 18022038d.notes WHERE noteid='$noteid';";
    
    $resultSet = $conn->query($sql);
      if (!$resultSet) {
      trigger_error('Invalid query: ' . $conn->error);
      }

      if ($resultSet->num_rows > 0) {
        $row = $resultSet ->fetch_assoc();
      if($row['encrypted']==1){

        echo '<h2> '.$row["title"].'</h2><br>';

        echo '<p>Your content is encrypted, please enter password to decrypt it</p><br></br>';
        ?>
        
        <form action="" method="POST">
        Password:
        <input type="text" name="password"><br></br>
        <input type="submit">
        </form>

        <?php
        if(isset($_POST["password"])){
          $decrypass = $_POST["password"];
          $salt = $row['salt'];
          $sql2 = "SELECT AES_DECRYPT(content,SHA2(CONCAT('$decrypass',salt),256),salt)  FROM 18022038d.notes WHERE noteid='$noteid';";
          $resultSet = $conn->query($sql2);
          $DecryptedContent = ($resultSet->fetch_row())[0];
          if($DecryptedContent==null){
            echo '<h3>Decrypt password not correct</h3>';
          }else{
          echo '<h3> Decrypted content: </h3><br>';
          echo '<h3> '.$DecryptedContent.'</h3><br>';
        }
        }

      }else{
     //fetch a result row as an associative array
        
      echo '<h2> '.$row["title"].'</h2><br>';
      echo '<h3> '.$row["content"].'</h3><br>';

      
      }
      echo'<br></br>';
      echo '<form action="" method="POST">';
      echo  "<input type= button style = \" width:300px; margin: 0 auto\"onClick=\"location.href='main.php'\" value='Back to main'>";
      echo '</form>';
      }


	$conn->close();

?>


<style>
  h1,h2,h3,p {
    margin:0 auto;
    width:300px
    }
    table{
    border: 1px solid black;
    margin:0 auto;
    width:auto;
    padding: 5px;
  }
  table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
}
form {
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