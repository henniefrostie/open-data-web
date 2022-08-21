<!--updateapi.php-->
<?php
    require "../assets/functions.php";
    session_start();
    error_reporting(E_ALL); #check all types of errors
    ini_set('display_errors', 1); #display errors

    #call the db_connection() function from functions.php
    $con = db_connection();
    if (!$con) {
        die("Connection Failed: " . $con->connect_error);
    }else{
        #if the submit button has been pressed
        if(isset($_POST['api_key_value'])){
            #select the new username from the user table
            $query = "UPDATE `user` SET api='".$_POST["api_key_value"]."' WHERE username = '".$_SESSION["username"]."'";

            #if the query is successful
            if(mysqli_query($con, $query)){
                #reassign the session api as the new api
                $_SESSION['api'] = $_POST['api_key_value'];
                header("Location: ../home.php");
            }else{
                echo ("Error: " . mysqli_errno($con) . ' ' . mysqli_error($con));
                echo("Please contact the administrator");
            }
            mysqli_close($con);
        }
    }
?>