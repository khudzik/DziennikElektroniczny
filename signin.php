<?php
    include 'connect.php';
    include 'header.php';
   
    echo '<h3>Logowanie</h3>';
    
    //sprawdzenie czy juz zalogowany
    if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true){
        echo 'Jesteś już zalogowany. Możesz się <a href="signout.php">wylogować</a> jeśli chcesz';
    }
    else{
        if ($_SERVER['REQUEST_METHOD'] != 'POST'){
            echo    '<form method = "post" action="">
                        Login: <input type="text"     name="user_login" />
                        Hasło: <input type="password" name="user_pass" />
                               <input type="submit" value="Zaloguj się"/>
                    </form>';
        }
        else{
            $errors = array();
            
            if(!isset($_POST['user_login'])){
                $errors[] = 'Pole login nie może być puste';
            }
            
            if(!isset($_POST['user_pass'])){
                $errors[] = 'Pole hasło nie może być puste';
            }
            if(!empty($errors)){
                echo 'Błąd logowania. Proszę poprawić dane!';
            
                echo '<ul>';
                foreach($errors as $temp => $value){
                    echo '<li>'.$value.'</li>';
                }
                echo '<ul>';
            }
            else{
                $sql = "SELECT * FROM users
                        WHERE user_login = '" . htmlspecialchars($_POST['user_login']) . "'
                          AND user_pass  = '" . sha1($_POST['user_pass']) . "'";
                        
                $result = mysqli_query($conn, $sql) or trigger_error("Istnieje konto przypisane do tego id");
                
                
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_NUM);
                    $_SESSION['signed_in'] = true;
                    
                    $_SESSION['user_id']    = $row[0];
                    $_SESSION['user_login'] = $row[1];
                    $_SESSION['user_name']  = $row[2];
                    $_SESSION['user_last']  = $row[3];
                    $_SESSION['user_mail']  = $row[5];
                    $_SESSION['user_level'] = $row[7];
                    
                    session_regenerate_id();
                    session_regenerate_id();
                    mysqli_close($conn);
                    
                    echo 'Zalogowałeś się poprawnie<br/>';
                    echo 'Jeżeli nie nastąpi automatyczne przekierowanie na stronę główną, możesz to zrobić <a href="/">tutaj</a>';
                    header ("refresh:3; url=/" );
                
                    exit();
                }
                else{                 
                    echo 'Podałeś błędne dane logowania. <a href="signin.php">Spróbuj ponownie.</a>';
                    session_destroy();
                }
            }
        }
    }
    
    include 'footer.php';
    
    
?>