<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT  cat_id, top_id, top_subject FROM topics, categories
             WHERE top_cat = cat_id 
               AND topics.top_id = " . htmlspecialchars($_GET['id']);
			
    $result = mysqli_query($conn, $sql);

    if(!$result){
	echo 'Nie można wyświetlić tematu.<br/>';
        echo 'Proszę spróbować później.';
    }
    else{//czy temat istnieje
	if(mysqli_num_rows($result) == 0){
            echo 'Temat nie istnieje.';
	}
	else{//główny if
            $sql_lesson = "SELECT categories.cat_name, topics.*, lessons.* FROM categories, topics, lessons
                            WHERE categories.cat_id = topics.top_cat
                             AND lessons.top_id = topics.top_id
                             AND topics.top_id = ".$_GET['id'];
            $que_lesson = mysqli_query($conn, $sql_lesson);
            
            if (!$que_lesson){
                echo 'Błąd połączenia z bazą danych<br/>';
                echo 'Prosimy spróbować później.';
            }
            else{//if topicu - lekcji
                if(mysqli_num_rows($que_lesson) != 0){
                    $lesson = mysqli_fetch_array($que_lesson);
                    echo '<h2>'.$lesson['cat_name'].' - '.$lesson['top_subject'].'</h2><br/>';
                    
                    $sql = "SELECT user_last, user_name, grades.* FROM users, grades, students, lessons
                            WHERE grades.stu_id = students.student_id
                              AND students.user_id = users.user_id
                              AND grades.les_id = lessons.top_id
                              AND lessons.top_id = ".$_GET['id'];
                    $que = mysqli_query($conn, $sql);
                    
                    while($row=  mysqli_fetch_array($que)){
                        echo $row ['user_last'].' '.['user_name'].'<br/>';
                    }
                    echo '<a href ="addmark.php?id='.$_GET['id'].'">Dodaj Ocenę</a>';
                }
                
            }
            
            
            
            
            
            
            
            
            
            
            
            
            
            
		
        }
    }
    
   

include 'footer.php';
?>