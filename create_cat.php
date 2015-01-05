<?php
    include "connect.php";
    include "header.php";
    
    echo    '<tr>
                <td class="leftpart">
                    <h3><a href="category.php?id=">Category name</a></h3> Category description goes here
                </td>
            
                <td class="rightpart">               
                    <a href="topic.php?id=">Topic subject</a> at 10-10
                </td>
            </tr>';

    include 'footer.php';
?>