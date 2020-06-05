<?php
session_start();
$userloginname =  $_SESSION['username'];
$decrypass = $_SESSION['decrypass'];
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "password", "18022038d");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
if(isset($_REQUEST["term"])){
    // Prepare a select statement
    if(!empty($decrypass)){
    $sql2 = "SELECT noteid, title ,AES_DECRYPT(content,SHA2(CONCAT('$decrypass',salt),256),salt)  
    FROM notes WHERE username='$userloginname' and encrypted ='1' and AES_DECRYPT(content,SHA2(CONCAT('$decrypass',salt),256),salt) LIKE ?";
    }
       
       $sql = "SELECT noteid, title ,content  
        FROM notes WHERE username='$userloginname' and content LIKE ?";
    
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        
        // Set parameters
        $param_term = '%' . $_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo "<p>" . $row["title"] . "</p>";
                }
            } 
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
    if(isset($sql2)){
    if($stmt2 = mysqli_prepare($link, $sql2)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt2, "s", $param_term2);
        
        // Set parameters
        $param_term2 = '%' . $_REQUEST["term"] . '%';
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt2)){
            $result2 = mysqli_stmt_get_result($stmt2);
            
            // Check number of rows in the result set
            if(mysqli_num_rows($result2) > 0){
                // Fetch result rows as an associative array
                while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)){
                    echo "<p>" . $row2["title"] . "</p>";
                }
            } 
        } 
   
    }
    mysqli_stmt_close($stmt2);
}
}
     
    // Close statement
    mysqli_stmt_close($stmt);
   
 
// close connection
mysqli_close($link);
?>