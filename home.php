<!--home.php-->
<?php 
    require 'assets/functions.php';
    session_start();

    #call the db_connection() function from functions.php
    $con = db_connection();
    #check if the user is logged in
    if(isset($_SESSION['username'])){
        #if the user is logged in
        $sql = "SELECT total_recorded_crime AS crime_stat FROM crime_data WHERE `year` = '$_SESSION[birthyear]' AND '$_SESSION[birthyear]' BETWEEN '1989' AND '2002'";
        $result = mysqli_query($con, $sql);
        $fetch = mysqli_fetch_array($result);
    }else {
        #if the user is not logged in, reditect them to page.html
        header('Location: user/login.html');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--MAIN-->
        <meta charset="UTF-8"/>
        <title>Home - Crime Stats Website</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--CSS FILES-->
        <link rel="stylesheet" href="styles/stylesheet.css">
        <link rel="stylesheet" href="styles/base.css">
        <!--RESPONSIVE-->
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
    </head>
    <body>
        <header>
            <div id="left-header">
                <h1>Total Crimes Stats</h1>
                <form id="searchForm" method="POST" action="displaySearchRecords.php">
                    <label for="yeah">Year:</label>
                    <input type="text" id="year" name="year" placeholder="eg: 1989">
                    <input type="Submit" name="search" id="search" value="Search"/>
                </form>
            </div>
            <div id="right-header">
                <div class="user"><h2>Welcome <?php echo("".$_SESSION['username']."");?> </h2></div>
                <button onClick="location.href='user/myaccount.php'">My Account</button>
                <button onClick="location.href='user/logout.php'" type="button">Log Out</button>
            </div>
        </header>
        <main>
            <div class="columns-page">
                <div class="left-main">
                    <h3>What would you like to do?</h3>
                    <p id="sub-text">Your current api key can be found on the my account page</p> 
                    <div class="button-box">
                        <form action="home.php" method="post">
                            <input type="submit" name="someAction" value="View Data" class="submit-1"/>
                        </form>
                        <button class="button-8" onClick="location.href='user/myaccount.php'" type="button">My Account</button>
                    </div>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['someAction']))
                        {
                            include 'user/api.php';
                        }
                    ?>
                    <?php
                        if(mysqli_num_rows($result)!=0){ #if there are crimes recorded from the user's birthyear
                            ?>
                                <p>Fun Fact!</p>
                            <?php
                            echo("<p id='sub-text'>In your birth year a total of: ".$fetch['crime_stat']." crimes were recorded</p>");
                        }else{
                            echo("<p>Oh bummer! We have no data from your birthyear.</p>");
                        }
                    ?>
                </div>
                <div class="right-main hide-on-mobile">
                    <img src="images/museums-victoria-QLezSKMJOnw-unsplash-small.jpg" alt="grayscale photo of women reading newspaper">
                </div>
            </div>
        </main>
    </body>
</html>