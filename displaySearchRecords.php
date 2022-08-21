<!--page.html-->
<?php
    require_once "assets/functions.php";
    #call the db_connection() function from functions.php
    $con = db_connection();
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--MAIN-->
    <meta charset="UTF-8"/>
    <title>Search Result - Crime Stats Website</title>
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
            <form id="searchForm" method="POST" action="displaySearchRecords.php">
                <label for="yeah">Year:</label>
                <input type="text" id="year" name="year" placeholder="eg: 1989">
                <input type="Submit" name="search" id="search" value="Search"/>
            </form>
        </div>
        <div id="right-header">
            <?php
                #check if the user is logged in
                if(isset($_SESSION['username'])){
                    #if the user is logged in
                    ?>
                        <div class="user"><h2>Welcome <?php echo("".$_SESSION['username']."");?> </h2></div>
                        <button onClick="location.href='user/myaccount.php'">My Account</button>
                        <button onClick="location.href='user/logout.php'" type="button">Log Out</button>
                    <?php
                }else {
                    #if the user is not logged in, reditect them to page.html
                    ?>
                        <button onClick="location.href='user/login.html'" type="button">Log In</button>
                    <?php
                }
            ?>
        </div>
    </header>
    <main>
        <?php 
                    
            if(!$con) { #if the connection fails
                die("Connection failed: " .$con->connect_error);
            }else {#if the connection is successfull log the user in
                #if the submit button has been pressed
                if(ISSET($_POST['search']) && ($_POST['year']) != NULL){

                    $query = "SELECT * FROM `crime_data` WHERE ";
                    if($_POST['year'] !=NULL){ #if the user is searching by year
                        $query = $query . "year LIKE '".$_POST['year']."' ";
                        $search = " Year = '".$_POST['year']."'";
                    }

                    #check the connection of the database and search the database for the query
                    $result = mysqli_query($con,$query);
                    $chart_data="";
                    while ($row = mysqli_fetch_array($result)) { 
                        $productname[]  = "Total Crimes Recorded"  ;
                        $sales[] = $row['total_recorded_crime'];
                        $violence_against_the_person[] = $row['violence_against_the_person'];
                        $sexual_offences[] = $row['sexual_offences'];
                        $robbery[] = $row['robbery'];
                        $violent_crime[] = $row['violent_crime'];
                        $burglary[] = $row['burglary'];
                        $theft_and_handling_stolen_goods[] = $row['theft_and_handling_stolen_goods'];
                        $fraud_and_forgery[] = $row['fraud_and_forgery'];
                        $criminal_damage[] = $row['criminal_damage'];
                        $drug_offences[] = $row['drug_offences'];
                        $other_offences[] = $row['other_offences'];
                    }
                    if(mysqli_num_rows($result)!=0){
                        ?>
                            <div class="data-canvas">
                                <h2 class="page-header" >Total Crimes Recorded</h2>
                                <h3>in the year <?php printf($_POST['year']); ?></h3>
                                <p id="sub-text">Click the titles to remove a category from the chart</p>
                                <canvas width="80%" id="chartjs_bar"></canvas> 
                            </div>
                        <?php
                        mysqli_close($con);
                    }else{ #if there is no data to display
                        ?> 
                            <p>Sorry, there seems to be a problem with the data.</p>
                            <p>Please contact the administrator</p>
                            <button onClick="location.href='index.php'" type="button">OK</button> 
                        <?php
                    }
                }else{ #if no year was entered
                    ?>
                        <p>No year has been entered.</p>
                        <button onClick="location.href='index.php'" type="button">Please try again</button> 
                    <?php
                }
            }
        ?>
    </main>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels:<?php echo json_encode($productname); ?>,
            datasets: [{
                label: 'Violence against the person',
                data: <?php echo json_encode($violence_against_the_person); ?>,
                fill: true,
                backgroundColor: 'rgba(230, 25, 75, 0.2)',
                borderColor: 'rgb(230, 25, 75)',
                borderWidth: 1
            }, {
                label: 'Sexual offences',
                data: <?php echo json_encode($sexual_offences); ?>,
                fill: true,
                backgroundColor: 'rgba(60, 180, 75, 0.2)',
                borderColor: 'rgb(60, 180, 75)',
                borderWidth: 1
            }, {
                label: 'Robbery',
                data: <?php echo json_encode($robbery); ?>,
                fill: true,
                backgroundColor: 'rgba(255, 225, 25, 0.2)',
                borderColor: 'rgb(255, 225, 25)',
                borderWidth: 1
            }, {
                label: 'Violent crime',
                data: <?php echo json_encode($violent_crime); ?>,
                fill: true,
                backgroundColor: 'rgba(0, 130, 200, 0.2)',
                borderColor: 'rgb(0, 130, 200)',
                borderWidth: 1
            }, {
                label: 'Burglary',
                data: <?php echo json_encode($burglary); ?>,
                fill: true,
                backgroundColor: 'rgba(245, 130, 48, 0.2)',
                borderColor: 'rgb(245, 130, 48)',
                borderWidth: 1
            }, {
                label: 'Theft and handling stolen goods',
                data: <?php echo json_encode($theft_and_handling_stolen_goods); ?>,
                fill: true,
                backgroundColor: 'rgba(145, 30, 180, 0.2)',
                borderColor: 'rgb(145, 30, 180)',
                borderWidth: 1
            }, {
                label: 'Fraud and forgery',
                data: <?php echo json_encode($fraud_and_forgery); ?>,
                fill: true,
                backgroundColor: 'rgba(70, 240, 240, 0.2)',
                borderColor: 'rgb(70, 240, 240)',
                borderWidth: 1
            }, {
                label: 'Criminal damage',
                data: <?php echo json_encode($criminal_damage); ?>,
                fill: true,
                backgroundColor: 'rgba(240, 50, 230, 0.2)',
                borderColor: 'rgb(240, 50, 230)',
                borderWidth: 1
            }, {
                label: 'Drug offences',
                data: <?php echo json_encode($drug_offences); ?>,
                fill: true,
                backgroundColor: 'rgba(210, 245, 60, 0.2)',
                borderColor: 'rgb(210, 245, 60)',
                borderWidth: 1
            }, {
                label: 'Other offences',
                data: <?php echo json_encode($other_offences); ?>,
                fill: true,
                backgroundColor: 'rgba(250, 190, 212, 0.2)',
                borderColor: 'rgb(250, 190, 212)',
                borderWidth: 1
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
        }
    });
</script>

</html>