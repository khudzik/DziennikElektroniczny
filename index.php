<?php
    include 'connect.php';
    include 'header.php';
    
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);
    
    if(!$result){
        echo 'Nie można wyświetlić kateogrii. <br/>';
        echo 'Spróbuj ponownie później.';
    }
    else{
        if(mysqli_num_rows($result) == 0){
            echo 'Brak kategorii';
        }
        else{
            echo '<table>
                    <tr>
                        <th>Kategoria</th>
                        <th>Ostatni temat</th>
                    </tr>';
                             
            while($row = mysqli_fetch_assoc($result)){
                echo    '<tr>';
                echo        '<td class="leftpart">';
                echo            '<h3><a href="category.php?id=">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
                echo        '</td>';
                echo        '<td class="rightpart">';
                echo            '<a href="topic.php?id=">Temat</a>';
                echo        '</td>';
                echo    '</tr>';
            }
        }
    }
    
    include 'footer.php';
?>

