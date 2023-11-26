/* This file displays the widget for assigning a TA to a course.
Author: Benjamin Namo
*/

<?php
include 'connectdb.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedTaUserId = mysqli_real_escape_string($connection, $_POST["tauserid"]);
    $selectedCourseOffering = mysqli_real_escape_string($connection, $_POST["courseoffering"]);
    $hours = mysqli_real_escape_string($connection, $_POST["hours"]);

    // Check if the relationship already exists
    $checkQuery = "SELECT * FROM hasworkedon WHERE tauserid = '$selectedTaUserId' AND coid = '$selectedCourseOffering'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Warning: TA is already assigned to this course offering.";
    } else {
        // Create the relationship
        $insertQuery = "INSERT INTO hasworkedon (tauserid, coid, hours) VALUES ('$selectedTaUserId', '$selectedCourseOffering', '$hours')";
        $insertResult = mysqli_query($connection, $insertQuery);

        if ($insertResult) {
            echo "Assignment successful!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}

// Display the form for assigning a TA to a course offering
echo "<h2>Assign TA to Course Offering</h2>";
echo "<form method='post' action=''>";

// Select TA
echo "<label>Select TA:</label>";
echo "<select name='tauserid'>";
$taQuery = "SELECT * FROM ta";
$taResult = mysqli_query($connection, $taQuery);

while ($taRow = mysqli_fetch_assoc($taResult)) {
    echo "<option value='" . $taRow['tauserid'] . "'>" . $taRow['firstname'] . " " . $taRow['lastname'] . "</option>";
}

echo "</select><br>";

// Select Course Offering
echo "<label>Select Course Offering:</label>";
echo "<select name='courseoffering'>";
$courseOfferingQuery = "SELECT * FROM courseoffer";
$courseOfferingResult = mysqli_query($connection, $courseOfferingQuery);

while ($courseOfferingRow = mysqli_fetch_assoc($courseOfferingResult)) {
    echo "<option value='" . $courseOfferingRow['coid'] . "'>" . $courseOfferingRow['coid'] . " - " . $courseOfferingRow['term'] . " " . $courseOfferingRow['year'] . "</option>";
}

echo "</select><br>";

// Number of hours
echo "<label>Number of Hours:</label>";
echo "<input type='number' name='hours' required><br>";

// Submit button
echo "<input type='submit' value='Assign TA'>";
echo "</form>";

mysqli_close($connection);
?>
