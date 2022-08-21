<!--login.php-->
<?php 
    require_once "../assets/functions.php";
    session_start();
    error_reporting(E_ALL); #check all types of errors
    ini_set('display_errors', 1); #display errors
    
    #call the db_connection() function from functions.php
    $con = db_connection();
    if(!$con) { #if the connection fails
        die("Connection failed: " .$con->connect_error);
    }else { #if the connection is successfull log the user in
        #if the submit button has been pressed
        if(isset($_POST['username']) && isset($_POST['password'])){    
            $clean_username = htmlentities($_POST['username'],ENT_QUOTES);
            $clean_password = htmlentities($_POST['password'],ENT_QUOTES);

            #clean the entered password the same way a new password is created
            $salt = '4N21$7%uhl^-\jd&KL#£-g75j3o';
            $salted_password = hash('sha256', $salt.$clean_password.$salt);
            $cleanSalted_password = "'".$salted_password."'";

            #select the username and cleaned password from the database
            $sql = "SELECT * FROM user WHERE `username` = '".$clean_username."'";
            $password = "SELECT password FROM user WHERE `password` = ".$cleanSalted_password."";

            $result = mysqli_query($con, $sql);
            $fetch = mysqli_fetch_array($result);

            #if the result results in data, log the user in and redirect them
            if(mysqli_num_rows($result)!=0){
                if($cleanSalted_password = $password)
                {
                    $_SESSION['username'] = $fetch['username'];
                    $_SESSION['api'] = $fetch['api'];
                    $YEAR_sql = "SELECT year(birthdate) AS birthyear FROM user WHERE `username` = '$_SESSION[username]'";
                    $YEAR_result = mysqli_query($con, $YEAR_sql);
                    $YEAR_fetch = mysqli_fetch_array($YEAR_result);
                    $_SESSION['birthyear'] = $YEAR_fetch['birthyear'];
                    header("Location: ../home.php");
                    mysqli_close($con);
                }
                else{ #if the passwords do not match
                    ?> 
                        <p>Sorry, password is wrong.</p>
                        <button onClick="location.href='login.html'" type="button">Try Again</button> 
                    <?php
                }
            }else { #if the user is not in the database
                ?> 
                    <p>Sorry, your details are wrong.</p>
                    <button onClick="location.href='login.html'" type="button">Try Again</button> 
                <?php
            }
        }
    }
?>
