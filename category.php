<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT * FROM categories
            WHERE cat_id = " . htmlspecialchars($_GET['id']);
    
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    
    if(!$result){
        echo 'Nie można wyświetlić kategorii.<br/>';
        echo 'Proszę spróbować później.';
    }
    else{
        if(mysqli_num_rows($result) == 0){
            echo 'Ta kategoria nie istnieje.';
        }
        else{
            while ($row = mysqli_fetch_assoc($result)){
                echo '<h2>' . $row['cat_name'] . '</h2><br/>';
            }
            
            $sql = "SELECT top_id, top_subject, top_date, top_cat FROM topics
                    WHERE top_cat = " . htmlspecialchars($_GET['id']);
            
            $result = mysqli_query($conn, $sql);
            
            if(!$result){
                echo 'Nie można wyświetlić tematów.<br/>';
                echo 'Proszę spróbować później.';
            }
            else{
                if(mysqli_num_rows($result) == 0){
                    echo 'W tej kategorii nie ma jeszcze tematów';
                }
                else{
                    echo    '<table border 1>';
                    echo    '<tr><th>Temat</th><th>Utworzono:</th></tr>';	
					
                            while($row = mysqli_fetch_array($result)){				
                                echo '<tr>';
                                    echo '<td class="leftpart">';
                                        echo '<h3><a href="topic.php?id=' . $row['top_id'] . '">' . $row['top_subject'] . '</a></h3>';
                                    echo '</td>';
                                    echo '<td class="rightpart">';
                                        echo date('d-m-Y', strtotime($row['top_date']));
                                    echo '</td>';
                                echo '</tr>';
                            }
			
                    echo    '</table>';
                }
            }
        }
    }
    
    include 'footer.php';
?>