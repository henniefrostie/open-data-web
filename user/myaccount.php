<!--myaccount.php-->
<?php 
    #view api
    #generate new
    #delete account
    require '../assets/functions.php';
    session_start();

    #call the db_connection() function from functions.php
    $con = db_connection();
    #check if the user is logged in
    if(isset($_SESSION['username'])){
        #if the user is logged in
    }else {
        #if the user is not logged in, reditect them to login.html
        header('Location: login.html');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!--MAIN-->
        <meta charset="UTF-8"/>
        <title>My Account - Crime Stats Website</title>
        <!--CSS FILES-->
        <link rel="stylesheet" href="../styles/stylesheet.css">
        <link rel="stylesheet" href="../styles/base.css">
        <!--RESPONSIVE-->
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
    </head>
    <body>
        <header>
            <div id="left-header">
                <h1><a href="../home.php">Total Crimes Stats</a></h1>
                <form id="searchForm" method="POST" action="../displaySearchRecords.php">
                    <label for="yeah">Year:</label>
                    <input type="text" id="year" name="year" placeholder="eg: 1989">
                    <input type="Submit" name="search" id="search" value="Search"/>
                </form>
            </div>
            <div id="right-header">
                <div class="user"><h2>Welcome <?php echo("".$_SESSION['username']."");?> </h2></div>
                <button onClick="location.href='myaccount.php'">My Account</button>
                <button onClick="location.href='logout.php'" type="button">Log Out</button>
            </div>
        </header>
        <main>
            <div>
                <h3>What would you like to do?</h3>
                <form action="myaccount.php" method="post">
                    <input type="submit" name="viewAPI" value="View API key" class="submit-1"/>
                    <input type="submit" name="newAPI" value="Generate New Api Key" class="submit-1"/>
                </form>
                <?php
                    if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['viewAPI'])){ 
                        ?>
                            <form class="apiForm" name="api_key">
                                <label>Your Api Key is:</label>
                                <input type="text" value="<?php echo($_SESSION['api']); ?>" id="myAPI" readonly>
                                <button id="apiForm-button" onclick="myFunction()">Copy text</button>
                            </form> 
                        <?php
                    }elseif($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['newAPI'])){
                        include 'newapi.php';
                    }
                ?>
            </div>
        </main>
    </body>
<script>
    function myFunction() {
        /* Get the text field */
        var copyText = document.getElementById("myAPI");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText.value);

        /* Alert the copied text */
        alert("Copied the text: " + copyText.value);
    }
</script>

</html>