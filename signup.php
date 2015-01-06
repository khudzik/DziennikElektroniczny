<?php
    include 'connect.php';
    include 'header.php';
    
    echo '<h3>Zarejestruj</h3>';
    
    if ($_SERVER['REQUEST_METHOD'] != 'POST'){
        echo    '<form method="post" action="">
                    Login:    <input type="text" name="user_login"/>
                    Imię:     <input type="text" name="user_name"/>
                    Nazwisko: <input type="text" name="user_last"/>
                    e-mail:   <input type="text" name="user_mail"/>
                
                    Hasło:         <input type="password" name="user_pass"/>
                    Powtórz hasło: <input type="password" name="user_pass_check"/>
                
                    <input type="submit" vallue="Add category"/>
    
                </form>';
    }
    else{
        $errors = array();
        
        //sprawdzanie loginu
        if(isset($_POST['user_login'])){
            if(!ctype_alnum($_POST['user_login'])){
                $errors[] = 'Nazwa użytkownika może zawierać tylko liczby lub litery';
            }
            if(strlen($_POST['user_login'])>40){
                $errors[] = 'Nazwa użytkownika może mieć najwyżej 40 znaków';
            }
        }
        else{
            $errors[] = 'Nazwa użytkownika nie może być pusta';
        }
        
        //sprawdzanie imienia
        if(isset($_POST['user_name'])){
            if(!ctype_alpha($_POST['user_name'])){
                $errors[] = 'Imię użytkownika może zaweirać tylko litery';
            }
            if(strlen($_POST['user_name'])>40){
                $errors[] = 'Imię użytkownika może mieć najwyżej 40 znaków';
            }
        }
        else{
            $errors[] = 'Imię użytkownika nie może być puste';
        }
        
        //sprawdzanie nazwiska
        if(isset($_POST['user_last'])){
            if(!ctype_alpha($_POST['user_last'])){
                $errors[] = 'Nazwisko użytkownika może zaweirać tylko litery';
            }
            if(strlen($_POST['user_name'])>40){
                $errors[] = 'Nazwisko użytkownika może mieć najwyżej 40 znaków';
            }
        }
        else{
            $errors[] = 'Nazwisko użytkownika nie może być puste';
        }
        
        //sprawdzanie maila
        if(isset($_POST['user_mail'])){
            if(substr_count($_POST['user_mail'], "@") != 1){
                $errors[] = 'Adres e-mail niepoprawny';
            }
        }
        else{
            $errors[] = 'Adres e-mail nie może być pusty';
        }
        
        //sprawdzanie hasła
        if(isset($_POST['user_pass'])){
            if(strlen($_POST['user_pass']) < 8){
                $errors[] = 'Hasło musi mieć przynajmniej 8 znaków';
            }
            if($_POST['user_pass'] != $_POST['user_pass_check']){
                $errors[] = 'Hasła nie pasują do siebie';
            }
        }
        else{
            $errors = 'Hasło nie może być puste';
        }
        
        //sprawdzanie wystąpienia błędów, jeśli brak dodanie rekordu do bazy
        if(!empty($errors)){
            echo 'Błąd rejestracji. Proszę poprawić dane!';
            
            echo '<ul>';
            foreach($errors as $temp => $value){
                echo '<li>'.$value.'</li>';
            }
            echo '<ul>';
        }
        else{
            $sql = "INSERT INTO users (user_login, user_name, user_last, user_mail, user_pass, user_level, user_date)
                    VALUES('" . htmlspecialchars($_POST['user_login']) . "',
                           '" . htmlspecialchars($_POST['user_name']) . "',
                           '" . htmlspecialchars($_POST['user_last']) . "',
                           '" . htmlspecialchars($_POST['user_mail']) . "',
                           '" . sha1($_POST['user_pass']) . "',
                           0,
                           NOW())";
            
            $result = mysqli_query($conn, $sql);
            
            if(!$result){
                echo 'Wystąpił błąd podczas rejestracji. Spróbuj później';
            }
            else{
                echo 'Rejestracja pomyślna. Możesz się teraz <a href="signin.php">zalogować</a>';
            }
        }
    }
    
    include 'footer.php';
?>