<?php
    include 'connect.php';
    include 'header.php';

    echo '<h2>Wyloguj</h2>';
    
    if($_SESSION['signed_in'] == true){
	
	$_SESSION['signed_in'] = NULL;
	$_SESSION['user_name'] = NULL;
	$_SESSION['user_id']   = NULL;

	echo 'Nastąpiło wylogowanie. Dziękujemy za wizyte.<br>';
        echo 'Przejdź na <a href="/">stronę główną</a>';
    }
    else{
	echo 'Nie jesteś zalogowany. Możesz to zrobić <a href="signin.php">tutaj</a>';
    }

include 'footer.php';
?>