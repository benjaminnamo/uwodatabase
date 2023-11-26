/* This file displays the details for a specific TA entry.
Author: Benjamin Namo
*/

<?php
include 'connectdb.php';
include 'header.php';
?>

<style>

  h3 {
        margin-left: 50px;
    }

    h2 {
        margin-left: 50px;
        margin-top: 100px;
    }

    body {
        margin-top: 200px;
        margin-left: 50px;
        font-family: Arial, sans-serif;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    table {
        border-collapse: collapse;
        width: 30%;
        margin-left: 50px;
    }

    th, td {
        border: 1px solid #4f2683;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #4f2683;
        color: white;
    }

    .form-container label,
    .form-container input,
    .form-container select {
        margin-left: 50px;
    }

    .form-container {
        float: left;
    }

    .go-back {
        clear: both;
        text-align: center;
        margin-top: 20px;
    }

    .message {
        margin-top: 20px;
        margin-left: 50px;
        padding: 10px;
    }

    .success {
        color: green;
    }

    .error {
        color: red;

</style>

<?php
// Initialize error/success messages
$errorMessages = [];
$successMessages = [];

// Handle image modification form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newImageLink"])) {
    $modifiedTaUserId = mysqli_real_escape_string($connection, $_POST["modifiedTaUserId"]);
    $newImageLink = mysqli_real_escape_string($connection, $_POST["newImageLink"]);

    // Update the TA's image link
    $updateQuery = "UPDATE ta SET image = '$newImageLink' WHERE tauserid = '$modifiedTaUserId'";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
        $successMessages[] = "Image link for TA modified successfully!";
    } else {
        $errorMessages[] = "Error: " . mysqli_error($connection);
    }
}

// Handle assignment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["assignTa"])) {
    $selectedTaUserId = mysqli_real_escape_string($connection, $_POST["tauserid"]);
    $selectedCourseOffering = mysqli_real_escape_string($connection, $_POST["courseoffering"]);
    $hours = mysqli_real_escape_string($connection, $_POST["hours"]);

    // Check if the relationship already exists
    $checkQuery = "SELECT * FROM hasworkedon WHERE tauserid = '$selectedTaUserId' AND coid = '$selectedCourseOffering'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $errorMessages[] = "Warning: TA is already assigned to this course offering.";
    } else {
        // Create the relationship
        $insertQuery = "INSERT INTO hasworkedon (tauserid, coid, hours) VALUES ('$selectedTaUserId', '$selectedCourseOffering', '$hours')";
        $insertResult = mysqli_query($connection, $insertQuery);

        if ($insertResult) {
            $successMessages[] = "Assignment successful!";
        } else {
            $errorMessages[] = "Error: " . mysqli_error($connection);
        }
    }
}

if (isset($_GET['tauserid'])) {
    $tauserid = $_GET['tauserid'];

    // Fetch the details for the specific TA
    $query = "SELECT * FROM ta WHERE tauserid = '$tauserid'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed.");
    }

    // Display TA details
    echo "<h2>TA Details</h2>";
    echo "<table>";
    echo "<tr><th>TA User ID</th><th>First Name</th><th>Last Name</th><th>Student Number</th><th>Degree Type</th><th>Image</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["tauserid"] . "</td>";
        echo "<td>" . $row["firstname"] . "</td>";
        echo "<td>" . $row["lastname"] . "</td>";
        echo "<td>" . $row["studentnum"] . "</td>";
        echo "<td>" . $row["degreetype"] . "</td>";

        // Handle null image link by providing a generic one
        $imageLink = $row["image"] ? $row["image"] : "https://cdn.pixabay.com/photo/2016/08/31/11/55/icon-1633250_1280.png";
        echo "<td><img src='" . $imageLink . "' alt='TA Image' width='100' height='100'></td>";

        echo "</tr>";
    }

    echo "</table>";

    // Fetch and display all the courses the TA has worked on
    echo "<h3>Courses Worked On</h3>";

    $courseWorkedOnQuery = "SELECT ho.tauserid, ho.coid, ho.hours, co.term, co.year, co.whichcourse, c.coursename,
                            IFNULL(l.lcoursenum, '') AS loved_course, IFNULL(h.hcoursenum, '') AS hated_course
                           FROM hasworkedon ho
                           JOIN courseoffer co ON ho.coid = co.coid
                           JOIN course c ON co.whichcourse = c.coursenum
                           LEFT JOIN loves l ON ho.tauserid = l.ltauserid AND co.whichcourse = l.lcoursenum
                           LEFT JOIN hates h ON ho.tauserid = h.htauserid AND co.whichcourse = h.hcoursenum
                           WHERE ho.tauserid = '$tauserid'";
    $courseWorkedOnResult = mysqli_query($connection, $courseWorkedOnQuery);

    if (!$courseWorkedOnResult) {
        die("Database query failed.");
    }

    // Check if the TA has worked on any courses
    if (mysqli_num_rows($courseWorkedOnResult) > 0) {
        // Display course offerings
        echo "<table>";
        echo "<tr><th>Course Number</th><th>Course Name</th><th>Course Offering ID</th><th>Term</th><th>Year</th><th>Hours</th><th>Opinion</th></tr>";

        while ($courseRow = mysqli_fetch_assoc($courseWorkedOnResult)) {
            echo "<tr>";
            echo "<td><a href='course_details.php?coid=" . $courseRow["coid"] . "'>" . $courseRow["whichcourse"] . "</a></td>";
            echo "<td>" . $courseRow["coursename"] . "</td>";
            echo "<td>" . $courseRow["coid"] . "</td>";
            echo "<td>" . $courseRow["term"] . "</td>";
            echo "<td>" . $courseRow["year"] . "</td>";
            echo "<td>" . $courseRow["hours"] . "</td>";

            // Determine the opinion based on loves and hates tables
            $lovedCourse = $courseRow["loved_course"];
            $hatedCourse = $courseRow["hated_course"];

            // Set the opinion text based on the values in the columns
            if ($lovedCourse != '') {
                $opinionImage = 'https://www.freeiconspng.com/uploads/heart-png-15.png';
            } elseif ($hatedCourse != '') {
                $opinionImage = 'https://static.vecteezy.com/system/resources/previews/009/687/647/original/yellow-sad-face-emoji-file-png.png';
            } else {
                $opinionImage = 'https://upload.wikimedia.org/wikipedia/commons/4/48/BLANK_ICON.png ';
            }

            echo "<td><img src='" . $opinionImage . "' alt='Opinion Image' width='25' height='25'></td>";

            echo "</tr>";
        }

        echo "</table>";
    } else {
        // If the TA has not worked on any courses
        echo "This TA has not worked on any courses.";
    }
}
?>

<div class="form-container">
    <!-- Display the form for modifying the TA's image link -->
    <h3>Modify TA Image</h3>
    <form method='post' action=''>
        <input type='hidden' name='modifiedTaUserId' value='<?php echo $tauserid; ?>'>
        <label>New Image Link: </label>
        <input type='text' name='newImageLink' required>
        <input type='submit' value='Modify Image'>
    </form>

    <!-- Display the form for assigning a TA to a course offering -->
    <h3>Assign TA to Course Offering</h3>
    <form method='post' action=''>
        <input type='hidden' name='tauserid' value='<?php echo $tauserid; ?>'>

        <!-- Select Course Offering -->
        <label>Select Course Offering:</label>
        <select name='courseoffering'>
            <?php
            $courseOfferingQuery = "SELECT * FROM courseoffer";
            $courseOfferingResult = mysqli_query($connection, $courseOfferingQuery);
            while ($courseOfferingRow = mysqli_fetch_assoc($courseOfferingResult)) {
                echo "<option value='" . $courseOfferingRow['coid'] . "'>" . $courseOfferingRow['coid'] . " - " . $courseOfferingRow['term'] . " " . $courseOfferingRow['year'] . "</option>";
            }
            ?>
        </select><br>

        <!-- Number of hours -->
        <label>Number of Hours:</label>
        <input type='number' name='hours' required><br>

        <!-- Submit button -->
        <input type='submit' name='assignTa' value='Assign TA'>
    </form>
</div>

<!-- Add a link to go back to the TA's page -->
<div class="go-back">
    <a href='alltadata.php'>Go back to the TA's page</a>
</div>

<!-- Display success messages -->
<?php
foreach ($successMessages as $successMessage) {
    echo "<p class='message success'>" . $successMessage . "</p>";
}
?>

<!-- Display error messages -->
<?php
foreach ($errorMessages as $errorMessage) {
    echo "<p class='message error'>" . $errorMessage . "</p>";
}
?>

<?php
include 'footer.php';
mysqli_close($connection);
?>
