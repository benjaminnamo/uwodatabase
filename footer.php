<?php/* This file displays the footer on our pages.
Author: Benjamin Namo
*/
?>

<!-- footer.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Assignment 3 CS3319</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <!-- Your content here -->

    <!-- Footer styles -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        footer {
            background-color: #4f2683; /* Purple background color */
            color: #FFFFFF; /* White text color */
            padding: 10px 20px 10px; /* Adjusted padding for thickness and text position */
            width: 100%;
            position: fixed;
            bottom: 0;
            display: flex;
            justify-content: space-between; /* Space items evenly between the start and end */
            align-items: center;
        }

        footer p {
            margin: 0; /* Remove default margin */
        }

        footer ul {
            list-style: none;
            padding: 0;
            display: inline-block; /* Allow buttons to be on the same line */
        }

        footer ul li {
            display: inline;
            margin-right: 20px;
        }

        footer a {
            text-decoration: none;
            color: #FFFFFF;
            font-weight: bold;
            background-color: #4F2683; /* Purple background color for buttons */
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.2s ease-in-out; /* Add smooth transition */
        }

        footer a:hover {
            background-color: #3B1B5E; /* Darker purple for button hover effect */
        }
    </style>

    <footer>
        <p style="flex-grow: 1; text-align: center; margin-right: -200px;">&copy; <?php echo date("Y"); ?> UWO Directory. Benjamin Namo. All rights reserved.</p>
        <ul class="footer-buttons">
            <li><a href="https://github.com/benjaminnamo/uwodatabase" target="_blank">Project Repo</a></li>
            <li><a href="https://www.linkedin.com/in/bnamo/" target="_blank">LinkedIn</a></li>
        </ul>
    </footer>
</body>

</html>
