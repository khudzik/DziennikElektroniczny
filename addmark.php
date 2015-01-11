<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT cat_name, top_subject, user_last, user_name, top_by, student_id, grades.*
              FROM categories, topics, lessons, students, users, classes, grades
             WHERE grades.les_id = lessons.les_id
               AND lessons.top_id = topics.top_id
               AND topics.top_cat = categories.cat_id
               AND classes.cat_id = categories.cat_id
               AND grades.stu_id = students.student_id
               AND students.class_id = classes.class_id
               AND students.user_id = users.user_id
               AND topics.top_id = ".$_GET['id']."
            ORDER BY user_last";
    
    $que = mysqli_query($conn, $sql);
    $que_c = mysqli_query($conn, $sql);
    if(!$que){
        echo 'Błąd połączenia z bazą danych.<br/>';
        echo 'Proszę spróbować później.';
    }
    else{//sprawdzenie czy to przedmiot właściciela
        $row_c = mysqli_fetch_array($que_c);
        if ($_SESSION['user_id'] != $row_c['top_by']){
            echo 'Nie możesz wpisywać ocen do nieswojego przedmiotu!';
        }
        else{//główny if
            echo '<h2>Klasa: '.$row_c['cat_name'].'  Przedmiot: '.$row_c['top_subject'].'</h2><br/>';

            echo '<form method="post" action="">';
            echo    'Kod oceny: <input type="text" name="nazwa"/>';
            echo '<table border 1>';
            echo    '<tr><th>Nazwisko</th><th>Imię</th><th>Ocena</th></tr>';
                    while($row = mysqli_fetch_array($que)){
                        echo '<tr><td>'.$row['user_last'].'</td><td>'.$row['user_name'].'</td>';
                        echo '<td><input type="text" name="' . $row['student_id'] . '"/></td></tr>';
                    }
                     
            echo '</table>';
            echo '<input type="submit" name="button" value="Zatwierdź"/>';
            echo '</form>';
            
            if(isset($_POST['button'])){
                $que_s = mysqli_query($conn, $sql);
                
                
                function first ($conn, $stude, $przed){
                    $sql = "SELECT * FROM grades WHERE stu_id = $stude AND les_id = $przed";
                    $que = mysqli_query($conn, $sql);
                    $num = (mysqli_num_fields($que)-3)/2;
                    
                    
                    while ($row = mysqli_fetch_array($que)){    
                        for($i=1; $i<=$num; $i++){
                            if($row['gd_'.$i.'d'] == NULL){
                                return $i;
                            }
                        }
                    }
                }
                
                
                function insert ($conn, $first, $stude, $przed){
                    $sql = "SELECT * FROM grades WHERE stu_id = $stude AND les_id = $przed";
                    $que = mysqli_query($conn, $sql);
                    $num = ((mysqli_num_fields($que)-3)/2)+1;
                    
                    $sql_1 = "ALTER TABLE grades ADD(
                                gd_".$num."d VARCHAR(40),
                                gd_".$num."  FLOAT
                            )";
                    $que_1 = mysqli_query($conn, $sql_1);
                }
                        
                //update
                while ($row_s=  mysqli_fetch_array($que_s)){
                    $nazwa = $_POST['nazwa'];
                    $stude = $row_s['student_id'];
                    $ocena = $_POST[$row_s['student_id']];
                    $przed = $row_s['les_id'];
                    
                    $first = first($conn, $stude, $przed);
                    if ($first == null){
                        insert($conn, $first, $stude, $przed);
                    }
                    
                    $first = first($conn, $stude, $przed);
                    $sql_up = "UPDATE grades SET gd_".$first."d = '$nazwa', gd_$first = $ocena
                               WHERE stu_id = $stude AND les_id = $przed";
                    
                    $que_up = mysqli_query($conn, $sql_up);
                }
            }
        }   
    }
    include 'footer.php'
?>
