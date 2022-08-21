<?php
    function db_connection(){
        $servername = "localhost";
        $username = "root";
        $password = "F1refox";
        $my_db = "crimestatsdatabase";

        $con = mysqli_connect($servername, $username, $password, $my_db);
        if (mysqli_connect_errno()){
            printf("Failed to connect to MySqli: %s\n", mysqli_connect_errno());
        }else {
            return $con;
        }
    }
?>