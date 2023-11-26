/* This file displays the main menu.
Author: Benjamin Namo
*/

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Assignment 3 CS3319</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php

include 'connectdb.php';
include 'header.php';
?>

<body>
    <div class="container">
        <div class="left-column">
            <h1>🎓 UWO Academic Database! 🎉</h1>
            <img src="https://4.bp.blogspot.com/-wBp48bzPFDo/TyY1ChTBlVI/AAAAAAAAAEw/zDeNq14lj6k/s1600/Western.png" alt="University Image" style="width: 80%; max-width: 200px; margin: 20px auto; display: block;">

            <form action="alltadata.php" method="get">
                <div class="cta-button">
                    <button type="submit">View TAs 📚</button>
                </div>
            </form>
            <form action="allcoursedata.php" method="get">
                <div class="cta-button">
                    <button type="submit">View Courses 🏫</button>
                </div>
            </form>
        </div>

        <div class="right-column">
            <h2>About the Project</h2>
            <p>Explore the UWO Academic Database, a user-friendly web application crafted to seamlessly interact with the Teaching Assistant (T.A.) information. This platform serves as a hub for managing and viewing details about T.A.s and their associated courses.</p>
        </div>
    </div>
</body>

</html>

<?php
include 'footer.php'
?>
