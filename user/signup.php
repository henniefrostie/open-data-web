<!--signup.php-->
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
        if(isset($_POST['username']) && isset($_POST['password'])){
            #get the values from signup.html
            $clean_username = htmlentities($_POST['username'],ENT_QUOTES);
            $clean_password = htmlentities($_POST['password'],ENT_QUOTES);
            $clean_birthdate = htmlentities($_POST['birthdate'],ENT_QUOTES);

            #clean the entered password
            $salt = '4N21$7%uhl^-\jd&KL#£-g75j3o';
            $salted_password = hash('sha256', $salt.$clean_password.$salt);
            $cleanSalted_password = "'".$salted_password."'";

            #api
            $secretKey = "secretkey";
            $salt = mt_rand();
            $signature = hash_hmac('sha256', $salt, $secretKey, true);
            $encodedSignature = base64_encode($signature);
            $encodedSignature = urlencode($encodedSignature);

            #select the new username from the user table
            $check = "SELECT * FROM user WHERE `username` = '".$clean_username."'";
            $result = mysqli_query($con, $check);
            #$fetch = mysqli_fetch_array($result);

            #if the user is not already in the database
            #make a new user with the values
            if(mysqli_num_rows($result)==0){
                $sql = "INSERT INTO user (`username`, `password`, `api`, `birthdate`) VALUES ('".$clean_username."',".$cleanSalted_password.",'".$encodedSignature."','".$clean_birthdate."')";
                
                if(mysqli_query($con, $sql)){
                    #assign the new user's information as the session user
                    $_SESSION['username'] = $clean_username;
                    $_SESSION['api'] = $encodedSignature;
                    $YEAR_sql = "SELECT year(birthdate) AS birthyear FROM user WHERE `username` = '$_SESSION[username]'";
                    $YEAR_result = mysqli_query($con, $YEAR_sql);
                    $YEAR_fetch = mysqli_fetch_array($YEAR_result);
                    $_SESSION['birthyear'] = $YEAR_fetch['birthyear'];
                    header("Location: ../home.php");
                    mysqli_close($con);
                }else{ #if there is a database error
                    ?> 
                        <p>Sorry, there is something wrong, please contact the administrator.</p>
                        <button onClick="location.href='signup.html'" type="button">Try Again</button> 
                    <?php
                }
            }else{ #if the user already exists, alert the user and ask them to try again
                ?> 
                    <p>Sorry, the username <?php echo("".$_POST['username']."");?> already exists.</p>
                    <button onClick="location.href='signup.html'" type="button">Try Again</button> 
                <?php
            }
        }
    }
?>