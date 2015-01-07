<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT * FROM categories
            WHERE cat_id = " . htmlspecialchars($_GET['id']);
    
    $result = mysqli_query($conn, $sql);
    
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
                echo '<h2>Tematy w &prime;'.$row['cat_name'].'&prime; kategori</h2><br/>';
            }
            
            $sql = "SELECT top_id, top_subject, top_date, top_cat FROM topics
                    WHERE top_cat = " . htmlspecialchars($_GET['id']);
            
            $result = mysqli_query($conn, $sql);
            
            if(!$result){
                echo 'Nie można wyświetlić tematu.<br/>';
                echo 'Proszę spróbować później.';
            }
            else{
                if(mysqli_num_rows($result) == 0){
                    echo 'W tej kategorii nie ma jeszcze tematów';
                }
                else{
                    echo    '<table>';
                    echo    '<tr><th>Temat</th><th>Data</th></tr>';	
					
                            while($row = mysqli_fetch_assoc($result)){				
                                echo '<tr>';
                                    echo '<td class="leftpart">';
                                        echo '<h3><a href="topic.php?id=' . $row['top_id'] . '">' . $row['top_subject'] . '</a><br /><h3>';
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