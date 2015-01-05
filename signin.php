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
                        
                $result = mysqli_query($conn, $sql);
                
                
                if(!$result){
                    echo 'Błąd logowania, prosimy spróbować później';
                }
                else{
                    if (mysqli_num_rows($result) == 0){
                        echo 'Zły login lub hasło';
                    }
                    else{
                        $_SESSION['signed_in'] = true;
                    
                        while ($row = mysqli_fetch_array($result)) {
                            $_SESSION['user_id']    = $row['user_id'];
                            $_SESSION['user_login'] = $row['user_login'];
                            $_SESSION['user_level'] = $row['user_level'];
                            $_SESSION['user_name']  = $row['user_name'];
                            $_SESSION['user_last']  = $row['user_last'];
                        }
                        
                        echo 'Witaj '. $_SESSION['user_name'].' '.$_SESSION['user_last'].'<br>';
                        echo 'Przejdź na <a href="/">stronę główną</a>';
                    }
                }
            }
        }
    }
    
    include 'footer.php';
?>