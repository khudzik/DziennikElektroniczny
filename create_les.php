<?php
    include 'connect.php';
    include 'header.php';
    
    echo '<h2>Dodaj przedmiot do klasy</h2>';
    
    if ($_SESSION['user_level'] < 2){
        echo 'Musisz byc nauczycielem, żeby dodać przedmiot.';
    }
    else{
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));;
          
            if(!$result){
                echo "Błąd podczas pobierania danych z bazy.<br/>";
                echo "Spróbuj ponownie później.";
            }
            else{
                if(mysqli_num_rows($result) == 0){
                    echo 'Nie ma jeszcze klas do których możesz dodać przedmiot';
                }
                else{
                    echo    '<form method="post" action="">
                                Nazwa przedmiotu:<input type="text" name="top_subject"/><br/>
                                Klasa:'; 
                        echo    '<select name="top_cat">';
                                    while ($row = mysqli_fetch_array($result)){
                                        echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                                    }
                        echo    '</select><br/>
                                 <input type="submit" value="Wygeneruj przedmiot"/>';
                    echo    '</form>';
                }
            }
        }
        else{
            $query = "BEGIN WORK;";
            $result = mysqli_query($conn, $query);
            
            if(!$result){
                echo 'Wystąpił błąd podczas dodawania przedmiotu.<br\>';
                echo 'Proszę spróbować później.';
            }
            else{
                $sql = "INSERT INTO topics (top_subject, top_cat, top_by, top_date)
                        VALUES('" . htmlspecialchars($_POST['top_subject']) . "',
                               '" . htmlspecialchars($_POST['top_cat']) . "',
                               '" . $_SESSION['user_id'] . "',
                               NOW())";
                
                $result = mysqli_query($conn, $sql);
                $id_top = mysqli_insert_id($conn);
                
                if(!$result){
                    echo 'Wystąpił błąd podczas dodawania tematu.<br\>';
                    echo 'Proszę spróbować później.';

                    $sql = "ROLLBACK;";
                    $result = mysqli_query($conn, $sql);
                }
                else{
                    $sql1 = "COMMIT;";
                    $que1 = mysqli_query($conn, $sql1);
                    
                    $sql2 = "INSERT INTO lessons(top_id) VALUES ($id_top)";
                    $que2 = mysqli_query($conn, $sql2);
                    $id_les = mysqli_insert_id($conn);
                    
                    $sql3 = "SELECT student_id, user_last, user_name FROM users, students, classes, categories
                             WHERE users.user_id = students.user_id
                               AND students.class_id = classes.class_id
                               AND classes.class_id = categories.cat_id
                               AND categories.cat_id = ".$_POST['top_cat']."
                             ORDER BY user_last";

                    $que3 = mysqli_query($conn, $sql3);
                    
                    while ($row = mysqli_fetch_array($que3)){
                        $sql = "INSERT INTO grades(les_id, stu_id) VALUES ($id_les,". $row['student_id'].")";
                        $que = mysqli_query($conn, $sql);                        
                        echo $id_les.' '.$row['student_id'].' '.$row['user_last'].' '.$row['user_name'].'<br/>';
                    }
                    
                    
                    
                    echo 'Poprawnie wygenerowałeś nowy przedmiot: <a href="topic.php?id=' .$id_top. '">'.$_POST['top_subject'].'</a>.';
                }
            }
        }
    }
    
    include 'footer.php';
?>