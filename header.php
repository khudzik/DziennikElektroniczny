<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
     
    <head>
        <title>Dziennik Elektroniczny</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="/css/style.css" type="text/css"/>
        
    </head>
    
    <body>
        <h1>Dziennik Elektroniczny</h1>
        
        <div id="wrapper">
            <div id="menu">
                <a class="item" href="/">Home</a>
                <a class="item" href="create_top.php">Temat+</a>
                <a class="item" href="create_cat.php">Kategoria+</a>
                
                 
                <div id="userbar">
                    <?php
                        if($_SESSION['signed_in']){
                            echo $_SESSION['user_name'].' '.$_SESSION['user_last'].' '.'<a class="item" href="signout.php">Wyloguj</a>';
                        }
                        else{
                            echo '<a class="item" href="signin.php">Zaloguj</a> <a class="item" href="signup.php">Zarejestruj</a>';
                        }
		?>
		</div>
            </div>
            
            <div id="content">
    
