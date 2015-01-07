<?php
    include 'connect.php';
    include 'header.php';
    
           
        session_start();
        unset ($_SESSION['signed_in']);
        session_destroy();
	
        echo 'Nastąpiło wylogowanie. Dziękujemy za wizyte.<br>';
        echo 'Jeżeli nie nastąpi automatyczne przekierowanie na stronę główną, możesz to zrobić <a href="/">tutaj</a>';
        
        header ("refresh:3; url=/" );
        mysqli_close($conn);
    
        exit ();
    
    include 'footer.php';
?>