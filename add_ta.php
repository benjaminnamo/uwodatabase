
/* This file displays the adding a TA page.
Author: Benjamin Namo
*/

<?php
include 'connectdb.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add TA</title>
    <link rel="stylesheet" type="text/css" href="style-add_ta.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission to insert a new TA

    // Validate and sanitize user inputs
    $newTaUserId = mysqli_real_escape_string($connection, $_POST["newTaUserId"]);
    $newFirstName = mysqli_real_escape_string($connection, $_POST["newFirstName"]);
    $newLastName = mysqli_real_escape_string($connection, $_POST["newLastName"]);
    $newStudentNum = mysqli_real_escape_string($connection, $_POST["newStudentNum"]);
    $newDegreeType = mysqli_real_escape_string($connection, $_POST["newDegreeType"]);
    $newImageLink = mysqli_real_escape_string($connection, $_POST["newImageLink"]);

    // Check if the TA User ID or Student Number already exists
    $checkQuery = "SELECT * FROM ta WHERE tauserid = '$newTaUserId' OR studentnum = '$newStudentNum'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<div class='error-message'> Error: TA User ID or Student Number already exists. Please choose a different one.";
    } else {
        // Insert the new TA into the ta table
        $insertQuery = "INSERT INTO ta (tauserid, firstname, lastname, studentnum, degreetype, image) VALUES ('$newTaUserId', '$newFirstName', '$newLastName', '$newStudentNum', '$newDegreeType', '$newImageLink')";
        $insertResult = mysqli_query($connection, $insertQuery);

        if ($insertResult) {
            echo "New TA added successfully!";
        } else {
            echo "Error: " . mysqli_error($connection);
        }

        // Handle the courses they love or hate
        $loveCourse = isset($_POST["loveCourse"]) ? mysqli_real_escape_string($connection, $_POST["loveCourse"]) : null;
        $hateCourse = isset($_POST["hateCourse"]) ? mysqli_real_escape_string($connection, $_POST["hateCourse"]) : null;

        if ($loveCourse !== null) {
            $insertLoveQuery = "INSERT INTO loves (ltauserid, lcoursenum) VALUES ('$newTaUserId', '$loveCourse')";
            mysqli_query($connection, $insertLoveQuery);
        }

        if ($hateCourse !== null) {
            $insertHateQuery = "INSERT INTO hates (htauserid, hcoursenum) VALUES ('$newTaUserId', '$hateCourse')";
            mysqli_query($connection, $insertHateQuery);
        }
    }
}

echo <<<HTML
    <form method='post' action='add_ta.php' class='main-form' style='text-align: center; margin-top:150px;'>
        <div class='form-container'>
            <div class='left-section'>
                <label>TA User ID:</label>
                <input type='text' name='newTaUserId' style='width: 200px;' required><br>

                <label>First Name:</label>
                <input type='text' name='newFirstName' style='width: 200px;' required><br>

                <label>Last Name:</label>
                <input type='text' name='newLastName' style='width: 200px;' required><br>

                <label>Student Number:</label>
                <input type='text' name='newStudentNum' style='width: 200px;' required><br>

                <label>Degree Type:</label>
                <input type='text' name='newDegreeType' style='width: 200px;' required><br>

                <label>Image Link:</label>
                <input type='text' name='newImageLink' style='width: 200px;'><br>
            </div>

            <div class='right-section'>
                <label>Courses they love:</label><br>
HTML;

$courseQuery = "SELECT * FROM course";
$courseResult = mysqli_query($connection, $courseQuery);

while ($courseRow = mysqli_fetch_assoc($courseResult)) {
    echo "<input type='radio' name='loveCourse' value='" . $courseRow['coursenum'] . "'>" . $courseRow['coursename'] . "<br>";
}

mysqli_free_result($courseResult);

echo <<<HTML
                <br>

                <label>Courses they hate:</label><br>
HTML;

$courseResult = mysqli_query($connection, $courseQuery);

while ($courseRow = mysqli_fetch_assoc($courseResult)) {
    echo "<input type='radio' name='hateCourse' value='" . $courseRow['coursenum'] . "'>" . $courseRow['coursename'] . "<br>";
}

mysqli_free_result($courseResult);

echo <<<HTML
            </div>
        </div>

        <br>

        <input type='submit' value='Add TA'>
    </form>
HTML;


// Add a link to go back to the main TA page
echo "<br><a href='alltadata.php'>Go back to TA's page</a>";

include 'footer.php';
mysqli_close($connection);
?>
