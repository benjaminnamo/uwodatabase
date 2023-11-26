/* This file displays the information about all TA's.
Author: Benjamin Namo
*/

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Assignment 3 CS3319</title>
    <link rel="stylesheet" type="text/css" href="style-alltadata.css">
</head>

<?php
include 'connectdb.php';
include 'header.php';
?>

<body>
    <?php
    // Set default values for orderField and orderDirection
    $orderField = isset($_POST["orderField"]) ? $_POST["orderField"] : null;
    $orderDirection = isset($_POST["orderDirection"]) ? $_POST["orderDirection"] : null;

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $orderField = $_POST["orderField"];
        $orderDirection = $_POST["orderDirection"];

        // Use ORDER BY clause in your SQL query based on user input
        $query = "SELECT * FROM ta ORDER BY $orderField $orderDirection";
    } else {
        // Default query without ordering
        $query = "SELECT * FROM ta";
    }
    ?>

    <!-- "Add a New TA" and "Delete a TA" buttons and ordering form -->
    <div style="margin-top: 100px;">
        <button onclick="location.href='add_ta.php'">Add a New TA</button>
        <button onclick="location.href='delete_ta.php'">Delete a TA</button>

        <!-- Display the ordering form beside the "Delete a TA" button -->
        <form method='post' action='alltadata.php' style='display: inline-block; margin-left: 20px; margin-top: -20px;'>
            <label>Order by:</label>
            <select name='orderField'>
                <option value='lastname' <?php echo ($orderField == 'lastname' ? 'selected' : ''); ?>>Last Name</option>
                <option value='degreetype' <?php echo ($orderField == 'degreetype' ? 'selected' : ''); ?>>Degree Type</option>
            </select>
            <label>Order direction:</label>
            <select name='orderDirection'>
                <option value='ASC' <?php echo ($orderDirection == 'ASC' ? 'selected' : ''); ?>>Ascending</option>
                <option value='DESC' <?php echo ($orderDirection == 'DESC' ? 'selected' : ''); ?>>Descending</option>
            </select>
            <input type='submit' value='Submit'>
        </form>
    </div>

    <!-- Add a div with class 'table-container' to center-align the table -->
    <div class="table-container">
        <?php
        $result = mysqli_query($connection, $query);

        echo "<table style='width: 100%;' border='1'>";

        echo "<tr><th>TA User ID</th><th>First Name</th><th>Last Name</th><th>Student Number</th><th>Degree Type</th>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            // Make TA User ID clickable
            echo "<td><a href='ta_details.php?tauserid=" . $row["tauserid"] . "'>" . $row["tauserid"] . "</a></td>";
            echo "<td>" . $row["firstname"] . "</td>";
            echo "<td>" . $row["lastname"] . "</td>";
            echo "<td>" . $row["studentnum"] . "</td>";
            echo "<td>" . $row["degreetype"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        mysqli_free_result($result);
        ?>
    </div>

    <!-- Add a link to go back to the home page after the table -->
    <div style='margin-top: 20px;'>
        <button onclick="location.href='mainmenu.php'">Go back to the home page</button>
    </div>

    <?php
    include 'footer.php';
    mysqli_close($connection);
    ?>
</body>

</html>
