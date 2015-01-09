<?php
    $hostname = 'sql3.freesqldatabase.com'; 
    $username = 'sql363159';
    $password = 'rX3!cB1*';
    $dbasname = 'sql363159';
    
    $conn = mysqli_connect($hostname, $username, $password, $dbasname);

    if (!$conn) {
        die('Could not connect to MySQL: '.mysqli_connect_error());
    }

    mysqli_query($conn, 'SET NAMES \'utf8\'');  
    session_start();
?>