<?php
    include 'connect.php';
    include 'header.php';

    if ($_SERVER['REQUEST_METHOD'] != 'POST'){
        echo 'Nie można wyświetlić tej strony bezpośrednio.';
    } 
    else{
        if (!$_SESSION['signed_in']) {
            echo 'Musisz być <a href="signin.php"> zalogowany </a> żeby pisać posty.';
        } 
        else {
            $sql = "INSERT INTO posts(pos_content, pos_date, pos_topic, pos_by) 
                    VALUES ('" . $_POST['reply-content'] . "',
                            NOW(),
                            " . htmlspecialchars($_GET['id']) . ",
                            " . $_SESSION['user_id'] . ")";

            $result = mysqli_query($conn, $sql);

            if (!$result){
                echo 'Twoja odpowiedź nie została zapisana.<br/>';
                echo 'Spróbuj ponownie później.';
            } 
            else {
                echo 'Twoja odpowiedź została zapisana.<br/>';
                echo 'Sprawdź <a href="topic.php?id=' . htmlentities($_GET['id']) . '">temat</a>.';
            }
        }
    }

    include 'footer.php';
?>