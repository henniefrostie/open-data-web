<!--totalcrimestats.php-->
<?php 
    require "../assets/functions.php";
    #call the db_connection() function from functions.php
    $con = db_connection();
    session_start();
    #check if the user is logged in
    if(isset($_SESSION['username'])){
        #if the user is logged in
    }else {
        #if the user is not logged in, redirect them to login.html
        header('Location: ../user/login.html');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--MAIN-->
    <meta charset="UTF-8"/>
    <title>Total Crime Stats - Crime Stats Website</title>
    <!--CSS FILES-->
    <link rel="stylesheet" href="../styles/stylesheet.css">
    <link rel="stylesheet" href="../styles/base.css">
    <!--RESPONSIVE-->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    <header>
        <div id="left-header">
            <h1><a href="../index.php">Total Crimes Stats</a></h1>
            <form id="searchForm" method="POST" action="../displaySearchRecords.php">
                <label for="yeah">Year:</label>
                <input type="text" id="year" name="year" placeholder="eg: 1989">
                <input type="Submit" name="search" id="search" value="Search"/>
            </form>
        </div>
        <div id="right-header">
            <div class="user"><h2>Welcome <?php echo("".$_SESSION['username']."");?> </h2></div>
            <button onClick="location.href='../user/myaccount.php'">My Account</button>
            <button onClick="location.href='../user/logout.php'" type="button">Log Out</button>
        </div>
    </header>
    <main>
        <?php 
            if(!$con) { #if the connection fails
                die("Connection failed: " .$con->connect_error);
            }else { #if the connection is successfull
                #get the api key entered on the api page
                $api_key = htmlentities($_POST['api_key'],ENT_QUOTES);
                #if there is an api key
                if(isset($_POST['api_key'])){
                    #select the api key from the database that belongs to the logged in user
                    $apiSecurity = "SELECT `api` FROM user WHERE `username` = '$_SESSION[username]'";

                    #if the user's api key is the same as the key entered, display the data
                    if($api_key == $_SESSION['api']){
                        $apiResult = mysqli_query($con, $apiSecurity);
                        $fetch = mysqli_fetch_array($apiResult);
                        #display the data
                        if(mysqli_num_rows($apiResult)!=0){
                            $query = "SELECT * FROM `crime_data` ORDER BY dataID";
                            $result = mysqli_query($con,$query);

                            #sql to fetch total number from 1939
                            $tt1939sql = "SELECT `total_recorded_crime` AS total1939 FROM `crime_data` WHERE year = 1939";
                            $tt1939_result = mysqli_query($con, $tt1939sql);
                            $tt1939_fetch = mysqli_fetch_array($tt1939_result);
                            if(mysqli_num_rows($tt1939_result)!=0){
                                $total1939['total1939'] = $tt1939_fetch['total1939'];
                            }

                            #assign the chart values from the table attributes
                            $chart_data="";
                            while ($row = mysqli_fetch_array($result)) { 
                    
                                $year[]  = $row['year']  ;
                                $total[] = $row['total_recorded_crime'];
                            }
                            ?>
                                <div class="data-canvas">
                                    <h2>Total Crimes Reported</h2>
                                    <h3>By Year*</h3>
                                    <canvas width="100%" id="chartjs_bar"></canvas>
                                    <p id="sub-text">*in 1939 no individual data is available. But the total crimes in this category for that year is: <?php echo("".$total1939['total1939']."");?></p> 
                                </div>
                            <?php
                        mysqli_close($con);
                        }else{ #if there is no data to display
                            ?> 
                                <p>Sorry, there seems to be a problem with the data.</p>
                                <p>Please contact the administrator</p>
                                <button onClick="location.href='../index.php'" type="button">OK</button> 
                            <?php
                        }              
                    }else{ #the api key entered and the user api key does not match
                        ?> 
                            <p>Sorry, your api key does not match.</p>
                            <button onClick="location.href='../home.php'" type="button">Try Again</button> 
                        <?php
                    }
                }else{ #if no api key was entered
                    ?> 
                        <p>Please enter a valid api key.</p>
                        <button onClick="location.href='../home.php'" type="button">Try Again</button> 
                    <?php
                }
            }
        ?>
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    //JavaScript code to display the chart
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:<?php echo json_encode($year); ?>,
            datasets: [{
                label: '# of Crimes Reported',
                data:<?php echo json_encode($total); ?>,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192)',
                tension: 0.1
            }],
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'rgb(0, 0, 0)'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                }
            }
        }
    });
</script>
</html>