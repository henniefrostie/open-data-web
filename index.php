<!--index.php-->
<?php
    require 'assets/functions.php';
    session_start();

    #call the db_connection() function from functions.php
    $con = db_connection();
    #check if the user is logged in
    if(isset($_SESSION['username'])){
        #if the user is logged in redirect them to the home page
        header('Location: home.php');
    }else {
        #if the user is not logged in
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--MAIN-->
    <meta charset="UTF-8"/>
    <title>Home - Crime Stats Website</title>
    <!--CSS FILES-->
    <link rel="stylesheet" href="styles/stylesheet.css">
    <link rel="stylesheet" href="styles/base.css">
    <!--RESPONSIVE-->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    <header>
        <div id="left-header">
            <h1><a href="index.php">Total Crimes Stats</a></h1>
            <h3>Welcome to the Total Crime Stats Website</h3>
        </div>
        <div id="right-header">
            <button onClick="location.href='user/login.html'" type="button">Log In</button>
        </div>
    </header>
    <main>
        <div>
            <h2>Search</h2>
            <form id="searchForm" method="POST" action="displaySearchRecords.php">
                <label for="yeah">Year:</label>
                <input type="text" id="year" name="year" placeholder="eg: 1989 or 2001/02">
                <input type="Submit" name="search" id="search" value="Search"/>
                <p id="sub-text">Please enter the years normally, unless it is the following years: 1997/8, 1998/9, 1999/00, 2000/01, 2001/02</p>
            </form>
        </div>
        <div>
            <h2>Important Notice</h2>
            <p>The following changes were made from 1 April 1998:  the change to the Home Office Counting Rules for recorded crime had the effect of increasing the number of crimes counted. Numbers of offences for years before and after this date are therefore not directly comparable.</p>
            <p>Introduction of the National Crime Recording Standard (NCRS) across England and Wales on 1 April 2002. Some forces adopted the Standard prior to this date.  Broadly, the NCRS had the effect of increasing the number of crimes recorded by the police. Therefore, following the introduction of the Standard, numbers of recorded crimes are not comparable with previous years.</p>
        </div>
            </br>
        </div>
            <h2>About</h2>
            <p>This website displays the historical crime records between the years 1898 and 2001/2. All the original data can be found <a target="_blank" href="https://data.gov.uk/dataset/f79c8194-93b0-41eb-bba5-56a83fd32f10/historical-crime-data">here</a>.</p>
            <p>It was created as part of an assignment for the Foundation Degree in Computing.</p>
        <div>
    </main>
</body>
</html>