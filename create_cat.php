<?php
    include 'connect.php';
    include 'header.php';
    
    echo '<h2>Dodaj klasę</h2>';
    
//    if ($_SESSION['signed_in'] == false){
//        echo 'Musisz byc <a href="signin">zalogowany</a> aby dodać kategorię</a>';
//    }
//    else
    if ($_SESSION['user_level'] < 2){
        echo 'Musisz być nauczycielem żeby dodać klasę';
    }
    else{
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            echo    '<form method="POST" action="">
                        Nazwa:<br/><input type="text" name="cat_name"/>
                        Klasa:<input type="checkbox" name="is_class"/>
                        <br/>
                        Opis:<br/><textarea name="cat_desc"/></textarea>
                        <br/>
                        <input type="submit" value="Dodaj kategorie"/>
                    </form>';
        }
        else{
            $sql = "INSERT INTO categories (cat_name, cat_desc, cat_by)
                    VALUES('" . htmlspecialchars($_POST['cat_name']) . "',	
                           '" . htmlspecialchars($_POST['cat_desc']) . "',
                           '" . $_SESSION['user_id'] . "')";

            $result = mysqli_query($conn, $sql);
            $id = mysqli_insert_id($conn);
            
            if($_POST['is_class'] == true){;
                $sql = "INSERT INTO classes (cat_id) VALUES($id)";
                $que = mysqli_query($conn, $sql);
            }
            
            if(!$result){
                echo 'Wystąpił błąd podczas dodawania kategori.<br/>';
                echo 'Spróbuj ponownie później.sdfsd';
            }
            else{
                echo 'Nowa kategoria dodana.';
            }
        }
    }
    
    include 'footer.php';
?>