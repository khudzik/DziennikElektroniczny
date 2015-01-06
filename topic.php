<?php
    include 'connect.php';
    include 'header.php';

    $sql = "SELECT  top_id, top_subject FROM topics
            WHERE topics.top_id = " . htmlspecialchars($_GET['id']);
			
    $result = mysqli_query($conn, $sql);

    if(!$result){
	echo 'Nie można wyświetlić tematu.<br/>';
        echo 'Proszę spróbować później.';
    }
    else{
	if(mysqli_num_rows($result) == 0){
            echo 'Temat nie istnieje.';
	}
	else{
            while($row = mysqli_fetch_assoc($result)){
                echo    '<table class="topic" border="1">
                            <tr> 
                                <th colspan="2">' . $row['topic_subject'] . '</th>
                            </tr>';
                            
                $posts_sql = "SELECT posts.pos_topic, posts.pos_content, posts.pos_date, posts.pos_by,
                                     users.user_id, users.user_login
                              FROM posts LEFT JOIN users ON	posts.pos_by = users.user_id
                              WHERE	posts.pos_topic = " . htmlspecialchars($_GET['id']);

                $posts_result = mysqli_query($conn, $posts_sql);
			
                if(!$posts_result){
                    echo '<tr><td>Nie można wyświetlić postów.</tr></td></table>';
                }
                else{
                    while($posts_row = mysqli_fetch_assoc($posts_result)){
                        echo    '<tr class="topic-post">
                                    <td class="user-post">' . $posts_row['user_login'] . '<br/>' . date('d-m-Y H:i', strtotime($posts_row['pos_date'])) . '</td>
                                    <td class="post-content">' . htmlentities(stripslashes($posts_row['pos_content'])) . '</td>
                                </tr>';
                    }
                }
			
                if(!$_SESSION['signed_in']){
                    echo '<tr><td colspan=2>Musisz być <a href="signin.php">zalogowany</a> żeby pisać posty.';
                }
                else{
                    echo    '<tr><td colspan="2"><h2>Odpowiedz:</h2><br/>
                            <form method="post" action="reply.php?id=' . $row['top_id'] . '">
                                <textarea name="reply-content"></textarea><br /><br />
                                <input type="submit" value="Submit reply" />
                            </form></td></tr>';
                }

                echo '</table>';
		
            }
        }
    }
   

include 'footer.php';
?>