<?php
    include 'connect.php';
    include 'header.php';
    
    echo '<h2>Napisz temat</h2>';
    
    if ($_SESSION['signed_in'] == false){
        echo 'Musisz byc <a href="signin">zalogowany</a> aby napisać teamt</a>';
    }
    else{
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($conn, $sql);
          
            if(!$result){
                echo "Błąd podczas pobierania danych z bazy.<br/>";
                echo "Spróbuj ponownie później.";
            }
            else{
                if(mysqli_num_rows($result) == 0){
                    if($_SESSION['user_level'] == 1){
                        echo 'Nie stworzyłeś jeszcze żadnych kategorii';
                    }
                    else{
                        echo 'Zanim napiszesz temat, musisz poczekać, aż nauczyciel stworzy odpowiedni post';
                    }
                }
                else{
                    echo    '<form method="post" action="">
                                Temat:<br/><input type="text" name="top_subject"/>
                                <br/>
                                Kategoria:<br/>'; 
                        echo    '<select name="top_cat">';
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo '<option value="'.$row['cat_id'].'">'.$row['cat_name'].'</option>';
                                    }
                        echo    '</select><br/>';
                        
                        echo    'Treść<br/>: <textarea name="pos_content"/></textarea><br/>
                                        <input type="submit" value="Napisz temat"/>';
                    echo    '</form>';
                }
            }
        }
        else{
            $query = "BEGIN WORK;";
            $result = mysqli_query($conn, $query);
            
            if(!$result){
                echo 'Wystąpił błąd podczas dodawania tematu.<br\>';
                echo 'Proszę spróbować później.';
            }
            else{
                $sql = "INSERT INTO topics (top_subject, top_cat, top_by, top_date)
                        VALUES('" . htmlspecialchars($_POST['top_subject']) . "',
                               '" . htmlspecialchars($_POST['top_cat']) . "',
                               '" . $_SESSION['user_id']."',
                               NOW())";
                
                $result = mysqli_query($conn, $sql);
                
                if(!$result){
                    echo 'Wystąpił błąd podczas dodawania tematu.<br\>';
                    echo 'Proszę spróbować później.';
                    
                    $sql = "ROLLBACK;";
                    $result = mysqli_query($conn, $sql);
                }
                else{
                    $topic_id = mysqli_insert_id($conn);
                    
                    $sql = "INSERT INTO posts (pos_content, pos_date, pos_topic, pos_by)
                            VALUES('" . htmlspecialchars($_POST['pos_content']) . "',
                                   NOW(),
                                   '" . $topic_id . "',
                                   '" . $_SESSION['user_id'] . "')";
                    $result = mysqli_query($conn, $sql);
                
                    if(!$result){
                        echo "Wystąpił błąd podczas dodawania posta.<br/>";
                        echo "Proszę spróbować później.";
                        
                        $sql = "ROLLBACK;";
                        $result = mysqli_query($conn, $sql);
                    }
                    else{
                        $sql = "COMMIT;";
                        $result = mysqli_query($conn, $sql);

                        echo 'Poprawnie utworzyłeś <a href="topic.php?id='. $topic_id . '">nowy temat</a>.';
                    }
                }
            }
        }
    }
    
    include 'footer.php';
?>