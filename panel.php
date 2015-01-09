<?php

    include 'connect.php';
    include 'header.php';
    
    session_regenerate_id();
    
    if($_SESSION['signed_in'] = false){
        echo 'Musisz być zalogowany, żeby przeglądać tę stronę.';
    }
    else{
        //ADMINISTRATOR
        if ($_SESSION['user_level'] == 3){
            
            echo 'Administrator<br/>';
            
            //wyświetlanie zgłoszeń dostępu
                        
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
                    echo        '</td></tr>';
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
                            
                            if ($at_val == 0){
                                $sql = "INSERT INTO students (user_id) VALUES ($id_row)";
                                mysqli_query($conn, $sql);
                            }
                            
                            if ($at_val == 1){
                                $sql = "INSERT INTO parents (user_id) VALUES ($id_row)";
                                mysqli_query($conn, $sql);
                            }
                            
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
            //KLASY
            echo '<h2>Moje klasy</h2>';
            $class   = "SELECT categories.cat_id, categories.cat_name, categories.cat_desc FROM categories, classes
                         WHERE classes.cat_id = categories.cat_id
                            AND categories.cat_by = " . $_SESSION['user_id'];
            
            $class_q = mysqli_query($conn, $class);
            
            if (mysqli_num_rows($class_q) == NULL){
                echo    'Nie jesteś jesze dodany jako opiekun klasy. ';
                echo    'Stwórz <a href="create_cat.php">nową klasę.</a>';               
            }
            else{
                echo    '<table border 1><th>Nazwa</th><tr>';
                            while ($row = mysqli_fetch_array($class_q)){
                                echo '<tr><td><h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_desc'] . '</td></tr>';
                            }
                echo    '<table>';
            }
            
            //UCZNIOWIE
            echo '<h2>Wnioski o przyjęcie do klasy</h2>';
            $student = "SELECT activate.act_date, users.user_id, users.user_name, users.user_last, categories.cat_name, activate.act_value
                          FROM activate, users, categories
                         WHERE activate.act_by    = users.user_id
                           AND activate.act_value = categories.cat_id
                           AND categories.cat_by  = " . $_SESSION['user_id'];
            
            $student_q = mysqli_query($conn, $student);
            
            if(mysqli_num_rows($student_q) == 0){
                echo 'Nie masz żadnych zgłoszeń o przyjęcie do klasy';
            }
            else{
                echo '<table border 1>';
                echo    '<tr><th>Data</th><th>Imię</th><th>Nazwisko</th><th>Klasa</th><th>+/-</th></tr>';
                
                while($row = mysqli_fetch_array($student_q)){ 
                    echo    '<tr>';
                    echo        '<td>'.$row['act_date'] .'</td>';
                    echo        '<td>'.$row['user_name'] .'</td>';
                    echo        '<td>'.$row['user_last'] .'</td>';
                    echo        '<td>'.$row['cat_name'] .'</td>';
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
                        $add = "UPDATE students SET class_id =  $vl WHERE user_id = $id";
                        $que = mysqli_query($conn, $add);
                        funkcja_delete($id, $conn);
                        header ("refresh:0");
                    }

                    function funkcja_delete($id, $conn){
                        $del = "DELETE FROM activate WHERE act_by = $id";
                        $que = mysqli_query($conn, $del);
                        header ("refresh:0");
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
        
        
        //RODZIC
        elseif ($_SESSION['user_level'] == 1){
            echo '<h2>Moje dzieci</h2>';
            
            $children = "SELECT children.* FROM children, parents 
                          WHERE parents.children_id = children.child_id
                            AND parents.user_id = ". $_SESSION['user_id'];
            $children_q = mysqli_query($conn, $children);
            
            if (mysqli_num_rows($children_q) == null){
                echo 'Nie oznacznyłeś się jeszcze jako rodzic dla dziecka.<br/>';
                echo  '<a href="makeparent.php">Dodaj dziecko</a>';
            }
            else{
                echo    '<table border 1><tr><th>Imię</th><th>Nazwisko</th><th>Klasa</th><th>Usuń</th></tr>
                            <tr>';
                            while ($row = mysqli_fetch_array($children_q)) {
                                for ($i=1; $i<=6; $i++){
                                    error_reporting(0);
                                    $sql = "SELECT users.user_id, users.user_last, users.user_name, categories.cat_name FROM users, students, classes, categories
                                             WHERE users.user_id = students.user_id
                                               AND students.class_id = classes.class_id
                                               AND classes.cat_id = categories.cat_id
                                               AND students.user_id = ". $row["child_$i"];
                                    $que = mysqli_query($conn, $sql);
                                    
                                    while ($row1 = mysqli_fetch_array($que)){
                                        echo    '<tr>';
                                        echo        '<td><a href="user.php?id=' . $row1['user_id'] . '">' . $row1['user_last'] . '</a></td>';
                                        echo        '<td>'.$row1['user_name'] .'</td>';
                                        echo        '<td>'.$row1['cat_name'] .'</td>';
                                        echo        "<td><form method='post' action=''>
                                                            <input type='hidden' name='id_usr' value = '" . $row1['user_id'] . "' /> 
                                                            <input type='submit' name='button' value='-' />
                                                         </form>
                                                    </td>
                                                </tr>";
                                    }                        
                                }
                            }
                echo        '<tr><td colspan=4><a href="makeparent.php">Dodaj nowe dziecko</a></td></tr>';
                echo '</table>';
                
                if (isset($_POST['button'])){
                    $id_stud = $_POST['id_usr'];
                                       
                    function del_kid($conn, $id_stud){
                        
                        for($i=1; $i<=6; $i++){
                            $sql = "UPDATE children, parents SET child_$i = NULL
                                     WHERE children.child_id = parents.children_id
                                       AND parents.user_id = '". $_SESSION['user_id'] . "'
                                       AND children.child_$i = $id_stud";
                            $que = mysqli_query($conn, $sql);
                            header ("refresh:0");
                        }
                    }del_kid($conn, $id_stud);
                }
            }
        }
    
        
        //UCZEN
        elseif ($_SESSION['user_level'] == 0){
            //klasa ucznia
            echo '<h2>Moja klasa</h2>';
            $class = "SELECT categories.cat_id, categories.cat_name, categories.cat_desc from categories, classes, users, students
                       WHERE classes.cat_id = categories.cat_id
                         AND students.class_id = classes.class_id
                         AND students.user_id = users.user_id
                         AND students.user_id = " . $_SESSION['user_id'];
            
            $class_q = mysqli_query($conn, $class);
            
            $active = "SELECT * FROM activate WHERE act_by = " . $_SESSION['user_id'];
            $active_q = mysqli_query($conn, $active);
            
            if (mysqli_num_rows($active_q) != NULL){
                echo 'Twoje zgłoszenie zostało przyjęte.<br/>Poczekaj na akceptacje dodania cie do klasy przez nauczyciela.';
            }
            elseif (mysqli_num_rows($class_q) == NULL){
                if ($_SERVER['REQUEST_METHOD'] != 'POST'){
                
                    $class_all = "SELECT categories.cat_id, categories.cat_name FROM categories, classes
                                   WHERE classes.cat_id = categories.cat_id";
                    $class_que = mysqli_query($conn, $class_all);
                    
                    echo 'Nie jesteś jeszcze dodany do klas<br/>';
                    
                    if (mysqli_num_rows($class_que) == NULL){
                        echo 'Brak klas do wyboru. Spróbuj ponownie później.';
                    }
                    else{
                        echo 'Dodaj się do klasy:';
                        echo    '<form method="post" action="">
                                    <select name="class_id">';
                                        while ($row = mysqli_fetch_array($class_que)){
                                            echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                                        }
                        echo        '</select>';
                        echo        '<input type="submit" value="Zatwierdź"/>';
                        echo   '</form>';
                    }
                    
                }
                else{
                    $sql = "INSERT INTO activate (act_by, act_date, act_value, act_flag)
                            VALUES ('" . $_SESSION['user_id'] . "',
                                    NOW(),
                                    '" . $_POST['class_id'] . "',
                                    2)";
                    $que = mysqli_query($conn, $sql);
                    
                    echo 'Dodałeś się do klasy. Poczekaj na akceptację przez nauczyciela';
                    header ("refresh:3");
                }
            }
            else{
                echo    '<table border 1><th>Nazwa</th><tr>';
                            while ($row = mysqli_fetch_array($class_q)){
                                echo '<tr><td><h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_desc'] . '</td></tr>';
                            }
                echo    '<table>';
            }
            
        }
    }
        
    include 'footer.php';
?>