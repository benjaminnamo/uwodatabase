<?php/* This file displays a widget for deleting TA's.
Author: Benjamin Namo
*/
?>

<?php
include 'connectdb.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete TA</title>
    <link rel="stylesheet" type="text/css" href="style-delete_ta.css">
    <style>
        .confirmation-message {
            margin-top: 20px;
        }

	.success-message {
	   color: green;
	   margin-top:200px;
	}

        .error-message {
            color: red;
            margin-top: 200px;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deleteTa"])) {
        $taToDelete = mysqli_real_escape_string($connection, $_POST["deleteTa"]);

        // Check if the TA is assigned to a course offering
        $checkAssignmentQuery = "SELECT * FROM hasworkedon WHERE tauserid = '$taToDelete'";
        $checkAssignmentResult = mysqli_query($connection, $checkAssignmentQuery);

        if (mysqli_num_rows($checkAssignmentResult) > 0) {
            echo "<p class='error-message'>Cannot delete this TA. The TA has worked on a course offering.</p>";
        } else {
            // Display confirmation message
            echo "<div class='confirmation-message'>";
            echo "Are you sure you want to delete this TA?";
            echo "<form method='post' action='delete_ta.php'>";
            echo "<input type='hidden' name='confirmDelete' value='$taToDelete'>";
            echo "<input type='submit' value='Yes'>";
            echo "<a href='delete_ta.php'><button type='button'>No</button></a>";
            echo "</form>";
            echo "</div>";
        }
    } elseif (isset($_POST["confirmDelete"])) {
        $confirmedTaToDelete = mysqli_real_escape_string($connection, $_POST["confirmDelete"]);

        // Delete the TA
        $deleteQuery = "DELETE FROM ta WHERE tauserid = '$confirmedTaToDelete'";
        $deleteResult = mysqli_query($connection, $deleteQuery);

        if ($deleteResult) {
            echo "<p class='success-message'>TA deleted successfully!</p>";
        } else {
            echo "<p class='error-message'>Error: " . mysqli_error($connection) . "</p>";
        }
    }
}

// Display the list of TAs for deletion
echo "<form method='post' action='delete_ta.php'>";
echo "<label>Select TA to delete:</label>";
echo "<select name='deleteTa'>";

$taQuery = "SELECT * FROM ta";
$taResult = mysqli_query($connection, $taQuery);

while ($taRow = mysqli_fetch_assoc($taResult)) {
    $selected = isset($_POST["deleteTa"]) && $_POST["deleteTa"] == $taRow['tauserid'] ? 'selected' : '';
    echo "<option value='" . $taRow['tauserid'] . "' $selected>" . $taRow['firstname'] . " " . $taRow['lastname'] . "</option>";
}

mysqli_free_result($taResult);

echo "</select>";
echo "<br><input type='submit' value='Delete TA'>";
echo "</form>";

// Add a link to go back to the TA's page
echo "<br><a href='alltadata.php'>Go back to the TA's page</a>";

mysqli_close($connection);
?>
