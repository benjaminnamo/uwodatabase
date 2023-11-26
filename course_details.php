/* This file displays the specific details of a course offering.
Author: Benjamin Namo
*/

<?php
include 'connectdb.php';
include 'header.php';
?>

<style>

    a {
//	margin-left:50px;
	}

    h2 {
        margin-left: 50px;
        margin-top: 100px;
    }

     h3 {
        margin-left: 50px;
	margin-top:30px;
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
        margin-left: 50px;
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
    }

	.bottom-links {
	   margin-left:50px;
}
</style>

<?php
if (isset($_GET['coid'])) {
    $coid = $_GET['coid'];

    // Fetch the details for the specific course offering
    $courseOfferingQuery = "SELECT * FROM courseoffer WHERE coid = '$coid'";
    $courseOfferingResult = mysqli_query($connection, $courseOfferingQuery);

    if (!$courseOfferingResult) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Display course offering details
    echo "<h2>Course Offering Details</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Course Offering ID</th><th>Number of Students</th><th>Term</th><th>Year</th></tr>";

    while ($courseOfferingRow = mysqli_fetch_assoc($courseOfferingResult)) {
        echo "<tr>";
        echo "<td>" . $courseOfferingRow['coid'] . "</td>";
        echo "<td>" . $courseOfferingRow['numstudent'] . "</td>";
        echo "<td>" . $courseOfferingRow['term'] . "</td>";
        echo "<td>" . $courseOfferingRow['year'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Fetch the TAs who have worked on the specific course offering
    $hasWorkedOnQuery = "SELECT ta.* FROM ta 
                        JOIN hasworkedon ON ta.tauserid = hasworkedon.tauserid 
                        WHERE hasworkedon.coid = '$coid'";

    $hasWorkedOnResult = mysqli_query($connection, $hasWorkedOnQuery);

    if (!$hasWorkedOnResult) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Display TAs who have worked on the course offering
    echo "<h3>TAs who have worked on this course offering</h3>";

    // Check if there are TAs assigned to the course offering
    if (mysqli_num_rows($hasWorkedOnResult) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>TA User ID</th><th>First Name</th><th>Last Name</th><th>Student Number</th><th>Degree Type</th><th>Image</th></tr>";

        while ($taRow = mysqli_fetch_assoc($hasWorkedOnResult)) {
            echo "<tr>";
            echo "<td><a href='ta_details.php?tauserid=" . $taRow["tauserid"] . "'>" . $taRow["tauserid"] . "</a></td>";
            echo "<td>" . $taRow["firstname"] . "</td>";
            echo "<td>" . $taRow["lastname"] . "</td>";
            echo "<td>" . $taRow["studentnum"] . "</td>";
            echo "<td>" . $taRow["degreetype"] . "</td>";

            // Handle null image link by providing a generic one
            $imageLink = $taRow["image"] ? $taRow["image"] : "https://cdn.pixabay.com/photo/2016/08/31/11/55/icon-1633250_1280.png";
            echo "<td><img src='" . $imageLink . "' alt='TA Image' width='100' height='100'></td>";

            echo "</tr>";
        }

        echo "</table>";
    } else {
        // Display a message if no TAs are assigned to the course offering
        echo "<p>No TAs are assigned to this course offering.</p>";
    }

         // Add a link to go back to the main page with the "bottom-links" class
    echo "<br><a href='javascript:history.go(-1)' class='bottom-links'>Go back to courses</a>";
    // Link to go back to courses with the "bottom-links" class
    echo "<br><a href='mainmenu.php' class='bottom-links'>Go back to the home page</a>";    

} else {
	//error message
    echo "Course Offering ID not specified.";
}

include 'footer.php';
mysqli_close($connection);
?>
