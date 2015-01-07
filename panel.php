<?php

    include 'connect.php';
    include 'header.php';
  
    if($_SESSION['signed_in'] = false){
        echo 'Musisz być zalogowany, żeby przeglądać tę stronę.';
    }
    else{
        //ADMINISTRATOR
        echo    '<div id="panelbar">';
        
        if ($_SESSION['user_level'] == 3){
            
            echo 'Administrator<br/>';
            
            //wyświetlanie zgłoszeń dostępu
            echo '<h3> Zgłoszenia </h3>';
            
            $a_sql = "SELECT users.user_id, activate.act_id, activate.act_date, users.user_login, users.user_name, users.user_last, users.user_level, activate.act_value
                      FROM users, activate
                      WHERE activate.act_by = users.user_id AND activate.act_flag = 3
                      ORDER BY act_date DESC";
            
            $a_res = mysqli_query($conn, $a_sql);
            
            if (mysqli_num_rows($a_res) == 0){
                echo 'Nie masz żadnych zgłoszeń';
            }
            else{
                echo '<table border 1>';
                echo    '<tr><th>Data</th><th>Użytkownik</th><th>Imię</th><th>Nazwisko</th><th>Obecny Poziom</th><th>Zgłaszany Poziom</th><th>+/-</th></tr>';
                
                while($row = mysqli_fetch_array($a_res)){ 
                    echo    '<tr>';
                    echo        '<td>'.$row['act_date'] .'</td>';
                    echo        '<td>'.$row['user_login'] .'</td>';
                    echo        '<td>'.$row['user_name'] .'</td>';
                    echo        '<td>'.$row['user_last'] .'</td>';
                    echo        '<td>'.$row['user_level'] .'</td>';
                    echo        '<td>'.$row['act_value'] .'</td>';
                    echo        "<td>   <form method='post' action=''>
                                            <input type='hidden' name='vl_act' value = '" . $row['act_value'] . "' />
                                            <input type='submit' name='button' value='+' />
                                            <input type='hidden' name='id_usr' value = '" . $row['user_id'] . "' /> 
                                            <input type='submit' name='button' value='-' />
                                        </form>";
                    echo        '</td>';
                }
                echo '</table>';
                
                if (isset($_POST['button'])){
                    $id_row = $_POST['id_usr'];		
                    $at_val = $_POST['vl_act'];
			
                    function funkcja_update($id, $vl, $conn){
                        $add = "UPDATE users SET user_level =  $vl WHERE user_id = $id";
                        $que = mysqli_query($conn, $add);
                        
                        funkcja_delete($id, $conn);
                        header ("refresh:0 url=panel.php");
                    }

                    function funkcja_delete($id, $conn){
                        $del = "DELETE FROM activate WHERE act_by = $id";
                        $que = mysqli_query($conn, $del);
                        header ("refresh:0 url=panel.php");
                    }
                    
                    switch($_POST['button']){
                        case "+":
                            funkcja_update($id_row, $at_val, $conn);
                            break;
                        case "-":
                            funkcja_delete($id_row, $conn);
                            break;
                    }
                }
            }  
        }
        
        
        //NAUCZYCIEL
        elseif ($_SESSION['user_level'] == 2){
            echo 'Nauczyciel';
            
            $class   = "SELECT * FROM classes";
            $class_q = mysqli_querry($conn, $class);
            
            if (mysqli_num_rows($class_q) == 0){
                echo 'Nie jesteś jesze dodany jako opiekun klasy.';
                echo 'Stwórz <a href="create_cat.php">nową klasę lub dodaj się do istniejącej
                      <select name="add_class">';
                       while ($row = mysqli_fetch_array($result)){
                                        echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                                    }
                echo  '</select>';
            }
        }
        
        
        //RODZIC
        elseif ($_SESSION['user_level'] == 1){
            echo 'Rodzic/Opiekun';
        }
        
        //UCZEN
        elseif ($_SESSION['user_level'] == 0){
            echo 'Uczeń';
        }
    }
        
    include 'footer.php';
?>