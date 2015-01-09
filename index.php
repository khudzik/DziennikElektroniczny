<?php

    include 'connect.php';
    include 'header.php';

    $sql = "SELECT categories.cat_id, categories.cat_name, categories.cat_desc,
            COUNT(topics.top_id) AS topics
            FROM categories
            LEFT JOIN topics ON topics.top_id = categories.cat_id
            GROUP BY categories.cat_name, categories.cat_desc, categories.cat_id";

    $result = mysqli_query($conn, $sql) or mysqli_error($conn);

    if(!$result){
        echo 'Błąd połączenia z bazą.<br/>';
        echo 'Proszę spróbować później';
    }
    else{
        if(mysqli_num_rows($result) == 0){
            echo 'Brak kategorii';
        }
        else{
            echo    '<table border="1">
                        <tr><th>Klasy</th><th>Ostatni temat</th></tr>';	

            while($row = mysqli_fetch_array($result)){				
                echo '<tr>';
                echo    '<td class="leftpart">';
                echo        '<h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_desc'];
                echo    '</td>';
                echo    '<td class="rightpart">';
                            $topicsql = "SELECT top_id, top_subject, top_date, top_cat
                                        FROM topics
                                        WHERE top_cat = " . $row['cat_id'] . "
                                        ORDER BY top_date DESC LIMIT 1";

                            $topicsresult = mysqli_query($conn, $topicsql);

                            if(!$topicsresult){
                                echo 'Nie można wyświetlić ostatniego tematu.';
                            }
                            else{
                                if(mysqli_num_rows($topicsresult) == 0){
                                    echo 'Brak teamtów.';
                                }
                                else{
                                    while($topicrow = mysqli_fetch_assoc($topicsresult))
                                        echo '<a href="topic.php?id=' . $topicrow['top_id'] . '">' . $topicrow['top_subject'] . '</a> at ' . date('d-m-Y', strtotime($topicrow['top_date']));
                                }
                            }
                echo    '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }

    include 'footer.php';
?>