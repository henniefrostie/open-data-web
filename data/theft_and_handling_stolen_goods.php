<!--theft_and_handling_stolen_goods.php-->
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
    <title>Theft and Handling Stolen Goods - Crime Stats Website</title>
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
        <div style="overflow-x: auto;">
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
                                $query = "SELECT * FROM `theft_and_handling_stolen_goods` ORDER BY id";
                                $result = mysqli_query($con,$query);

                                #sql to fetch total number from 1939
                                $tt1939sql = "SELECT `total_theft_and_handling_stolen_goods` AS total1939 FROM `theft_and_handling_stolen_goods` WHERE year = 1939";
                                $tt1939_result = mysqli_query($con, $tt1939sql);
                                $tt1939_fetch = mysqli_fetch_array($tt1939_result);
                                if(mysqli_num_rows($tt1939_result)!=0){
                                    $total1939['total1939'] = $tt1939_fetch['total1939'];
                                }
                                
                                #assign the chart values from the table attributes
                                $chart_data="";
                                while ($row = mysqli_fetch_array($result)) { 
                                    $year[] = $row['year'];
                                    $embezzlement[] = $row['embezzlement'];
                                    $aggravated_vehicle_taking[] = $row['aggravated_vehicle_taking'];
                                    $proceeds_of_crime[] = $row['proceeds_of_crime'];
                                    $theft_from_the_person[] = $row['theft_from_the_person'];
                                    $theft_in_a_house[] = $row['theft_in_a_house'];
                                    $theft_in_a_dwelling_other_than_from_automatic_machine_or_meter[] = $row['theft_in_a_dwelling_other_than_from_automatic_machine_or_meter'];
                                    $theft_by_an_employee[] = $row['theft_by_an_employee'];
                                    $theft_or_unauthorised_taking_from_mail[] = $row['theft_or_unauthorised_taking_from_mail'];
                                    $other_aggravated_larcenies[] = $row['other_aggravated_larcenies'];
                                    $abstracting_electricity[] = $row['abstracting_electricity'];
                                    $theft_of_pedal_cycle[] = $row['theft_of_pedal_cycle'];
                                    $theft_from_a_vehicle[] = $row['theft_from_a_vehicle'];
                                    $theft_from_shop[] = $row['theft_from_shop'];
                                    $theft_from_automatic_machine_or_meter[] = $row['theft_from_automatic_machine_or_meter'];
                                    $theft_or_unauthorised_taking_of_a_motor_vehicle[] = $row['theft_or_unauthorised_taking_of_a_motor_vehicle'];
                                    $other_theft_and_unauthorised_taking[] = $row['other_theft_and_unauthorised_taking'];
                                    $handling_stolen_goods[] = $row['handling_stolen_goods'];
                                    $vehicle_interference_and_tampering[] = $row['vehicle_interference_and_tampering'];
                                }
                                mysqli_close($con);
                                ?>
                                    <div class="data-canvas">
                                        <h2>Theft and handling stolen goods</h2>
                                        <h3>By Year*</h3>
                                        <p id="sub-text">Click the titles to remove a category from the chart</p>
                                        <canvas width="100%" id="chartjs_bar"></canvas>
                                        <p id="sub-text">*in 1939 no individual data is available. But the total crimes in this category for that year is: <?php echo("".$total1939['total1939']."");?></p>
                                    </div>
                                <?php
                            }else{ #if there is no data to display
                                ?> 
                                    <p>Sorry, there seems to be a problem with the data.</p>
                                    <p>Please contact the administrator</p>
                                    <button onClick="location.href='../ndex.php'" type="button">OK</button> 
                                <?php
                            }              
                            
                        }else { #the api key entered and the user api key does not match
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
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    //JavaScript code to display the chart
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($year); ?>,
            datasets: [{
                label: 'Embezzlement',
                data: <?php echo json_encode($embezzlement); ?>,
                fill: true,
                backgroundColor: 'rgba(230, 25, 75, 0.2)',
                borderColor: 'rgb(230, 25, 75)',
                tension: 0.1
            }, {
                label: 'Aggravated vehicle taking',
                data: <?php echo json_encode($aggravated_vehicle_taking); ?>,
                fill: true,
                backgroundColor: 'rgba(60, 180, 75, 0.2)',
                borderColor: 'rgb(60, 180, 75)',
                tension: 0.1
            }, {
                label: 'Proceeds of crime',
                data: <?php echo json_encode($proceeds_of_crime); ?>,
                fill: true,
                backgroundColor: 'rgba(255, 225, 25, 0.2)',
                borderColor: 'rgb(255, 225, 25)',
                tension: 0.1
            }, {
                label: 'Theft from the person',
                data: <?php echo json_encode($theft_from_the_person); ?>,
                fill: true,
                backgroundColor: 'rgba(0, 130, 200, 0.2)',
                borderColor: 'rgb(0, 130, 200)',
                tension: 0.1
            }, {
                label: 'Theft in a house',
                data: <?php echo json_encode($theft_in_a_house); ?>,
                fill: true,
                backgroundColor: 'rgba(245, 130, 48, 0.2)',
                borderColor: 'rgb(245, 130, 48)',
                tension: 0.1
            }, {
                label: 'Theft in a dwelling other than from automatic machine or meter',
                data: <?php echo json_encode($theft_in_a_dwelling_other_than_from_automatic_machine_or_meter); ?>,
                fill: true,
                backgroundColor: 'rgba(145, 30, 180, 0.2)',
                borderColor: 'rgb(145, 30, 180)',
                tension: 0.1
            }, {
                label: 'Theft by an employee',
                data: <?php echo json_encode($theft_by_an_employee); ?>,
                fill: true,
                backgroundColor: 'rgba(70, 240, 240, 0.2)',
                borderColor: 'rgb(70, 240, 240)',
                tension: 0.1
            }, {
                label: 'Theft or unauthorised taking from mail',
                data: <?php echo json_encode($theft_or_unauthorised_taking_from_mail); ?>,
                fill: true,
                backgroundColor: 'rgba(240, 50, 230, 0.2)',
                borderColor: 'rgb(240, 50, 230)',
                tension: 0.1
            }, {
                label: 'Other aggravated larcenies',
                data: <?php echo json_encode($other_aggravated_larcenies); ?>,
                fill: true,
                backgroundColor: 'rgba(210, 245, 60, 0.2)',
                borderColor: 'rgb(210, 245, 60)',
                tension: 0.1
            }, {
                label: 'Abstracting electricity',
                data: <?php echo json_encode($abstracting_electricity); ?>,
                fill: true,
                backgroundColor: 'rgba(250, 190, 212, 0.2)',
                borderColor: 'rgb(250, 190, 212)',
                tension: 0.1
            }, {
                label: 'Theft from a vehicle',
                data: <?php echo json_encode($theft_from_a_vehicle); ?>,
                fill: true,
                backgroundColor: 'rgba(0, 128, 128, 0.2)',
                borderColor: 'rgb(0, 128, 128)',
                tension: 0.1
            }, {
                label: 'Theft from shop',
                data: <?php echo json_encode($theft_from_shop); ?>,
                fill: true,
                backgroundColor: 'rgba(220, 190, 255, 0.2)',
                borderColor: 'rgb(220, 190, 255)',
                tension: 0.1
            }, {
                label: 'Theft from automatic machine or meter',
                data: <?php echo json_encode($theft_from_automatic_machine_or_meter); ?>,
                fill: true,
                backgroundColor: 'rgba(170, 110, 40, 0.2)',
                borderColor: 'rgb(170, 110, 40)',
                tension: 0.1
            }, {
                label: 'Theft or unauthorised taking of a motor vehicle',
                data: <?php echo json_encode($theft_or_unauthorised_taking_of_a_motor_vehicle); ?>,
                fill: true,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgb(255, 159, 64)',
                tension: 0.1
            }, {
                label: 'Other theft and unauthorised_taking',
                data: <?php echo json_encode($other_theft_and_unauthorised_taking); ?>,
                fill: true,
                backgroundColor: 'rgba(128, 0, 0, 0.2)',
                borderColor: 'rgb(128, 0, 0)',
                tension: 0.1
            }, {
                label: 'Handling stolen goods',
                data: <?php echo json_encode($handling_stolen_goods); ?>,
                fill: true,
                backgroundColor: 'rgba(170, 255, 195, 0.2)',
                borderColor: 'rgb(170, 255, 195)',
                tension: 0.1
            }, {
                label: 'Vehicle interference and tampering',
                data: <?php echo json_encode($vehicle_interference_and_tampering); ?>,
                fill: true,
                backgroundColor: 'rgba(0, 0, 128, 0.2)',
                borderColor: 'rgb(0, 0, 128)',
                tension: 0.1
            }]
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
            },
            elements: {
                line: {
                    borderWidth: 3
                }
            }
        }
    });
</script>
</html>
