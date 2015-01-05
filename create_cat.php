<?php
    include "connect.php";
    include "header.php";
   
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        echo    '<form method="POST" action="">
                    Nazwa:<br/><input type="text" name="cat_name"/>
                    <br/>
                    Opis:<br/><textarea name="cat_desc"/></textarea>
                    <br/>
                    <input type="submit" value="Dodaj kategorie"/>
                    
                </form>';
    }
    else{
        $sql = "INSERT INTO categories (cat_name, cat_desc)
                VALUES('". htmlspecialchars($_POST['cat_name'])."',
                       '". htmlspecialchars($_POST['cat_desc'])."')";
        
        $result = mysqli_query($conn, $sql);
        
        if(!$result){
            echo 'Wystąpił błąd podczas dodawania kategori.<br/>';
            echo 'Spróbuj ponownie później.';
        }
        else{
            echo 'Nowa kategoria dodana.';
        }
    }
    
    include "footer.php";
?>