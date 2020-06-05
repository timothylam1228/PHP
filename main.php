<?php
session_start();
$_SESSION['decrypass']="";
$password =  $_SESSION['password'];
$userloginname =  $_SESSION['username'];
echo  '<h1>Welcome, ' . $userloginname . '</h1>';

if (!(isset($_SESSION['username']) && $_SESSION['username'] != '')) {
  header("Location: login.php");
}
?>


<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.search-box input[type="text"]').on("keyup input", function() {
      /* Get input value on change */
      var inputVal = $(this).val();
      var resultDropdown = $(this).siblings(".result");
      if (inputVal.length) {
        $.get("backend-search.php", {
          term: inputVal
        }).done(function(data) {
          // Display the returned data in browser
          resultDropdown.html(data);
        });
      } else {
        resultDropdown.empty();
      }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result p", function() {
      $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
      $(this).parent(".result").empty();
    });
  });
</script>


<form action="main.php" method="POST">
  <input type=button onClick="location.href='create_note.php'" value="Create notes">
  <br><br>
  <input type="checkbox" name="decryptcheck" id="decryptcheck" value="1" style="width: auto" onclick="myFunction() ">
  <label for="decrypt">Decrypt?</label><br><br>
  <div class="password" id="decrypt" style="display:none">
    Decrypt password:
    <input type="text" name="decrypass" style="width:45%">
    <input type="submit" style="width:45%">
  </div>
</form>

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
$sql = "SELECT noteid, title, content FROM 18022038d.notes WHERE username='$userloginname';";
$resultSet = $conn->query($sql);
if (!$resultSet) {
  trigger_error('Invalid query: ' . $conn->error);
}
if ($resultSet->num_rows > 0) {
  echo '<table>
        <tr>
          <th></th>
            <th>Title</th>
            <th>Content</th>
        </tr>';
  while ($row = $resultSet->fetch_assoc()) { //fetch a result row as an associative array
    $noteid = $row["noteid"];
    echo '<tr>
            <td> ' . $row["noteid"] . '</td>
            <td> <a href="view.php?noteid=' . $noteid . '"> ' . $row["title"] . '</a> </td>
            <td>' . $row["content"] . '</td>
            </tr>';
  }
  echo '</table>';

  if (isset($_POST['decrypass'])) {
    if (!empty($_POST['decrypass'])) {
      $decrypass = $_POST['decrypass'];
      $_SESSION['decrypass'] = $decrypass;
      $sql2 = "SELECT noteid, title ,AES_DECRYPT(content,SHA2(CONCAT('$decrypass',salt),256),salt)  FROM 18022038d.notes WHERE username='$userloginname' and encrypted ='1';";
      #print_r($sql2);
      $resultSet2 = $conn->query($sql2);
      #print_r($resultSet2);
      if (!$resultSet2) {
        trigger_error('Invalid query: ' . $conn->error);
      }
      if ($resultSet2->num_rows > 0) {
        echo '
        <h2 style="text-align: center;">Decrypted</h2>
        <table>
              <tr>
                <th>Note ID</th>
                  <th>Title</th>
                  <th>Content</th>
              </tr>';
        $array = array();
        #while ($row2 = $resultSet2->fetch_all()) { //fetch a result row as an associative array
        $row2 = $resultSet2->fetch_all();

        for ($int = 0; $int < sizeof($row2); $int++) {

          if ($row2[$int][2] != null) {
            
            $noteid2 = $row2[$int][0];
            echo '<tr>
                  <td> ' . $noteid2 . '</td>
                  <td> <a href="view.php?noteid=' . $noteid2 . '"> ' . $row2[$int][1] . '</a> </td>
                  <td> ' . $row2[$int][2] . ' </td>
                  </tr>';
          }
        }
      }
      echo '</table>';
      # }
    }
  }
} else {
  echo "No note is created";
}

$conn->close();

?>

<body>
  <form>
    <br><br><br>
    <div class="search-box">
      <input type="text" autocomplete="off" placeholder="Search content..." />
      <div class="result" style="width:100%"></div>
</body>

<form method="post" action="logout.php">
  <label class="logoutLblPos">
    <input name="submit2" type="submit" id="submit2" value="logout">
  </label>
</form>
<style>
  h1 {
    margin: 0 auto;
    width: 300px
  }

  table {
    border: 1px solid black;
    margin: 0 auto;
    width: auto;
    padding: 5px;
  }

  table,
  th,
  td {
    border: 1px solid black;
    border-collapse: collapse;
  }

  th,
  td {
    padding: 10px;
  }

  form {
    margin: 0 auto;
    width: 300px
  }

  form .logoutLblPos {
    margin: 0 auto;
    width: 30px
  }

  input {
    margin-bottom: 3px;
    padding: 10px;
    width: 100%;
    border: 1px solid #CCC
  }

  button {
    padding: 10px
  }

  label {
    cursor: pointer
  }

  #form-switch {
    display: none
  }

  #register-form {
    display: none
  }

  #form-switch:checked~#register-form {
    display: block
  }

  #form-switch:checked~#login-form {
    display: none
  }

  .logoutLblPos {

    right: 500px;
    top: 50px;
  }
</style>

<script>
  function myFunction() {
    // Get the checkbox
    var checkBox = document.getElementById("decryptcheck");
    // Get the output text
    var text = document.getElementById("text");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true) {
      decrypt.style.display = "block";
    } else {
      decrypt.style.display = "none";
    }




  }
</script>