<?php
    include 'connect.php';
    include 'header.php';
    
    echo'<h2>Dodaj dziecko</h2>';
    
    if ($_SESSION['user_level'] != 1){
        echo 'Tylko rodzic może oznaczać dziecko';
    }
    else{
        $sql = "SELECT users.user_id, users.user_last, users.user_name, categories.cat_name FROM users, students, classes, categories
                 WHERE users.user_id = students.user_id
                   AND students.class_id = classes.class_id
                   AND classes.cat_id = categories.cat_id
                 ORDER BY cat_name, user_last";
        $que = mysqli_query($conn, $sql);
        
        if(!$que){
            echo 'Błąd połączenia z bazą danych.<br/>';
            echo 'Spróbuj ponownie później';
        }
        else{
            echo '<table border 1>
                    <tr><th>Nazwisko</th><th>Imię</th><th>Klasa</th><th>Dodaj</th></tr>';
                        while ($row = mysqli_fetch_array($que)){
                            echo '<tr><td>' . $row['user_last'] . '</td><td>' . $row['user_name'] . '</td><td>' . $row[cat_name] . '</td>
                                    <td>
                                        <form method="post" action="">
                                            <input type="hidden" name="usr_id" value = "' . $row['user_id'] . '"/>
                                            <input type="submit" name="button" value="+" />
                                        </form>
                                    </td>
                                </tr>';
                            }
            echo '</table>';

            if (isset($_POST['button'])){
                $id = $_POST['usr_id'];

                //sprawdzenie czy ma już jakieś dzieci
                $sql = "SELECT children.* FROM children, parents
                         WHERE parents.children_id = children.child_id
                           AND parents.user_id = " . $_SESSION['user_id'];
                $que = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($que) == 0){
                    $sql = "INSERT INTO children () VALUES ()";
                    $que = mysqli_query($conn, $sql);
                    $lid = mysqli_insert_id($conn);

                    $sql2 = "UPDATE parents SET children_id = $lid WHERE user_id  = ".$_SESSION['user_id'];
                    $que2 = mysqli_query($conn, $sql2);
                }
                else{
                    $sql = "SELECT children_id FROM parents WHERE user_id = ". $_SESSION['user_id'];
                    $que = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($que);

                    $children_id = $row['children_id'];

                    function check($children_id, $conn){
                        $sql = "SELECT * FROM children WHERE child_id = ". $children_id;
                        $que = mysqli_query($conn, $sql);

                        while($row = mysqli_fetch_array($que)){
                            for($i=1; $i<=6; $i++){
                                if ($row["child_$i"] == NULL){
                                    return $i;
                                }
                            }
                        }   
                    }$first = check($children_id, $conn);
                    
                    function exists($id, $conn){
                        for ($i=1; $i<=6; $i++){
                            $sql = "SELECT children.* FROM children, parents
                                    WHERE children.child_id = parents.children_id
                                    AND parents.user_id = ".$_SESSION['user_id']." AND child_$i = $id";
                            $que = mysqli_query($conn, $sql);
                            
                            if(mysqli_num_rows($que) != 0){
                                return 0;
                            }
                            else{
                                return 1;
                            }
                        }
                    }$flag = exists($id, $conn);
                    
                    //odczyt flag
                    if ($flag == 0){
                        $message = "Już dodałeś to dziecko";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                    }
                    elseif($flag == 1){
                        $message = "Poprawnie dodałeś dziecko";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                        
                        function insert($first, $id, $children_id, $conn){
                            $sql = "UPDATE children SET child_$first = $id WHERE child_id = $children_id";
                            $que = mysqli_query($conn, $sql);
                        }insert($first, $id, $children_id, $conn);
                    }
                }
            }
        }
    }
?>