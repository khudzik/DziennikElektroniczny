<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT * FROM users WHERE user_id = " .htmlspecialchars($_GET['id']);
    $que = mysqli_query($conn, $sql); 
    
    if($_SESSION['user_id'] < 0){
        echo 'Nie masz uprawnień żeby przeglądać tą stronę';
    }
    else{
        if(mysqli_num_rows($que) == 0){
            echo 'Użytkownik nie istnieje.';
        }
        else{
            while ($row = mysqli_fetch_array($que)){
                echo '<h2>'.$row['user_name'].' '.$row['user_last'].'</h2>';
                echo '<a href="mailto:'.$row['user_mail'].'">'.$row['user_mail'].'</a>';    
            }
            if($row['user_level'] == 0){
                echo '<h2>Klasy ucznia:</h2>';
                $sql_s = "SELECT categories.cat_id, categories.cat_name, categories.cat_desc FROM categories, classes, users, students
                           WHERE classes.cat_id = categories.cat_id
                            AND students.class_id = classes.class_id
                            AND students.user_id = users.user_id
                            AND students.user_id = " . $_GET['id'];
                $que_s = mysqli_query($conn, $sql_s);
                
                if (mysqli_num_rows($que_s) == 0){
                    echo 'Uczeń nie jest zapisany do żadnej klasy';
                }
                else{
                    while ($row_s = mysqli_fetch_array($que_s)){
                        echo '<a href="category.php?id='.$row_s['cat_id'].'">'. $row_s['cat_name'].'</a>';
                    }    
                }
            }
        }
    }
        
    include 'footer.php';
?>