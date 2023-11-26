<?php/* This file displays the header on all of our pages.
Author: Benjamin Namo
*/
?>

<!-- header.php -->
<?php
$currentFileName = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div class="header-content">
        <a href="mainmenu.php" class="logo">
            <img src="https://www.communications.uwo.ca/img/logo_teasers/Horizontal_Rev.gif" alt="Your Logo">
        </a>
        <nav>
            <ul>
		<li><a href="mainmenu.php" <?php echo ($currentFileName == 'mainmenu.php') ? 'class="active"' : ''; ?>>Home</a></li>
                <li><a href="allcoursedata.php" <?php echo ($currentFileName == 'allcoursedata.php') ? 'class="active"' : ''; ?>>Search Courses</a></li>
                <li><a href="alltadata.php" <?php echo ($currentFileName == 'alltadata.php') ? 'class="active"' : ''; ?>>Teaching Assistant Home</a></li>
            </ul>
        </nav>
    </div>
</header>

<style>
    /* Header styles */
    header {
        background-color: #4f2683;
        color: #FFFFFF;
        padding: 10px 0; /* Adjusted padding for thickness */
        text-align: left;
        width: 100%;
        position: fixed;
        top: 0;
        z-index: 1000; /* Set a high z-index to ensure it's above other elements */
    }

    .header-content {
        display: flex;
        align-items: center;
    }

    .logo img {
        max-width: 200px; /* Adjust the max-width as needed */
        height: auto;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0 0 0 20px; /* Adjusted margin to move buttons away from the logo */
        display: flex;
    }

    nav ul li {
        margin-right: 20px; /* Adjusted margin for spacing between buttons */
    }

    nav a {
        text-decoration: none;
        color: #FFFFFF;
        font-weight: bold;
        position: relative;
    }

    /* Remove the line and add a subtle shadow on hover */
    nav a:hover {
        text-decoration: none; /* Remove underline on hover */
    text-shadow: 0 0 2px rgba(255, 255, 255, 0.5); /* Adjust the shadow as needed */
    }

    /* Underline the selected button */
    nav a.active {
        border-bottom: 2px solid #FFFFFF; /* Adjust the thickness and color as needed */
    }
</style>
