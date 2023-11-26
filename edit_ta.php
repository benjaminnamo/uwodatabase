/* This file displays the widget for editing a TA's photo.
Author: Benjamin Namo
*/

<?php
include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["modifyTa"])) {
        $taToModify = mysqli_real_escape_string($connection, $_POST["modifyTa"]);
        
        // Fetch the current image link for the selected TA
        $currentImageQuery = "SELECT image FROM ta WHERE tauserid = '$taToModify'";
        $currentImageResult = mysqli_query($connection, $currentImageQuery);
        $currentImageRow = mysqli_fetch_assoc($currentImageResult);
        $currentImage = $currentImageRow['image'];

        // Display the form for modifying the TA's image link
        echo "<form method='post' action='modify_ta.php'>";
        echo "<label>TA User ID:</label>";
        echo "<input type='text' name='modifiedTaUserId' value='$taToModify' readonly><br>";

        echo "<label>Current Image Link:</label>";
        echo "<input type='text' name='currentImageLink' value='$currentImage' readonly><br>";

        echo "<label>New Image Link:</label>";
        echo "<input type='text' name='newImageLink' required><br>";

        echo "<input type='submit' value='Modify Image'>";
        echo "</form>";
    } elseif (isset($_POST["modifiedTaUserId"]) && isset($_POST["newImageLink"])) {
        $modifiedTaUserId = mysqli_real_escape_string($connection, $_POST["modifiedTaUserId"]);
        $newImageLink = mysqli_real_escape_string($connection, $_POST["newImageLink"]);

        // Update the TA's image link
        $updateQuery = "UPDATE ta SET image = '$newImageLink' WHERE tauserid = '$modifiedTaUserId'";
        $updateResult = mysqli_query($connection, $updateQuery);

        if ($updateResult) {
            echo "Image link for TA modified successfully!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}

// Display the list of TAs for modification
echo "<form method='post' action='modify_ta.php'>";
echo "<label>Select TA to modify:</label>";
echo "<select name='modifyTa'>";

$taQuery = "SELECT * FROM ta";
$taResult = mysqli_query($connection, $taQuery);

while ($taRow = mysqli_fetch_assoc($taResult)) {
    echo "<option value='" . $taRow['tauserid'] . "'>" . $taRow['firstname'] . " " . $taRow['lastname'] . "</option>";
}

mysqli_free_result($taResult);

echo "</select>";
echo "<br><input type='submit' value='Modify TA'>";
echo "</form>";

// Add a link to go back to the TA's page
echo "<br><a href='alltadata.php'>Go back to the TA's page</a>";


mysqli_close($connection);
?>
