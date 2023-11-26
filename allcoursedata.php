<?php //This file displays all the courses available, and allows you to sort them. We also have buttons for adding TA and deleting TA.
//Author: Benjamin Namo
?>


<?php
include 'connectdb.php';
include 'header.php';

// Fetch all courses for the dropdown
$courseQuery = "SELECT * FROM course";
$courseResult = mysqli_query($connection, $courseQuery);

// Fetch distinct years from courseoffer table
$distinctYearQuery = "SELECT DISTINCT year FROM courseoffer ORDER BY year ASC ";
$distinctYearResult = mysqli_query($connection, $distinctYearQuery);

// Initialize variables to store selected values
$selectedCourseValue = isset($_POST["selectedCourse"]) ? $_POST["selectedCourse"] : null;
$startYearValue = isset($_POST["startYear"]) ? $_POST["startYear"] : "Any Year";
$endYearValue = isset($_POST["endYear"]) ? $_POST["endYear"] : "Any Year";

// Initialize an empty array to store the table rows
$tableRows = array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedCourse = mysqli_real_escape_string($connection, $selectedCourseValue);
    $startYear = ($startYearValue !== "Any Year") ? mysqli_real_escape_string($connection, $startYearValue) : null;
    $endYear = ($endYearValue !== "Any Year") ? mysqli_real_escape_string($connection, $endYearValue) : null;

    // Fetch course offerings based on the selected course and year range
    $courseOfferingQuery = "SELECT * FROM courseoffer WHERE whichcourse = '$selectedCourse'";

    if (!empty($startYear)) {
        $courseOfferingQuery .= " AND year >= $startYear";
    }

    if (!empty($endYear)) {
        $courseOfferingQuery .= " AND year <= $endYear";
    }

    $courseOfferingResult = mysqli_query($connection, $courseOfferingQuery);

    // Populate the array with table rows
    while ($courseOfferingRow = mysqli_fetch_assoc($courseOfferingResult)) {
        $tableRows[] = $courseOfferingRow;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Offering Viewer</title>  
    <link rel="stylesheet" type="text/css" href="style-allcoursedata.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
	    overflow-x: hidden;
        }

	.search-section h1{
	   margin-top: -30px;
	}

        .container {
            display: flex;
            justify-content: space-between;
            width: 105%;
            text-align: center;
            margin: 10px 0;
        }

        .search-section,
        .table-section {
            flex: 1;
            margin: 10px;
        }

        table {
            margin-top: 20px;
        }

        .centered-link {
	    position: fixed;
   	    bottom: 10%;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
             margin-top: 10px;
         }

    </style>
</head>
<body>

<div class="container">

    <!-- Search Courses Section -->
    <div class="search-section">
        <h1>Search Courses</h1>

        <form method="post" action="">
            <label>Select Course:</label>
            <select name="selectedCourse">
                <?php
                while ($courseRow = mysqli_fetch_assoc($courseResult)) {
                    $selected = ($courseRow['coursenum'] == $selectedCourseValue) ? "selected" : "";
                    echo "<option value='" . $courseRow['coursenum'] . "' $selected>" . $courseRow['coursename'] . "</option>";
                }
                mysqli_free_result($courseResult);
                ?>
            </select>
            <br>

            <label>Start Year:</label>
            <select name="startYear">
                <?php
                $selectedAnyYear = ($startYearValue == "Any Year") ? "selected" : "";
                echo "<option value='Any Year' $selectedAnyYear>Any Year</option>";
                
                while ($yearRow = mysqli_fetch_assoc($distinctYearResult)) {
                    $selected = ($yearRow['year'] == $startYearValue) ? "selected" : "";
                    echo "<option value='" . $yearRow['year'] . "' $selected>" . $yearRow['year'] . "</option>";
                }
                mysqli_free_result($distinctYearResult);
                ?>
            </select>
            <br>

            <label>End Year:</label>
            <select name="endYear">
                <?php
                $selectedAnyYear = ($endYearValue == "Any Year") ? "selected" : "";
                echo "<option value='Any Year' $selectedAnyYear>Any Year</option>";
                
                $distinctYearResult = mysqli_query($connection, $distinctYearQuery);
                while ($yearRow = mysqli_fetch_assoc($distinctYearResult)) {
                    $selected = ($yearRow['year'] == $endYearValue) ? "selected" : "";
                    echo "<option value='" . $yearRow['year'] . "' $selected>" . $yearRow['year'] . "</option>";
                }
                mysqli_free_result($distinctYearResult);
                ?>
            </select>
            <br>

            <input type="submit" value="View Course Offerings">
        </form>
    </div>
<!-- Table Section -->
<div class="table-section">
    <?php
    // Display the table if there are rows
    if (!empty($tableRows)) {
        $courseName = mysqli_fetch_assoc(mysqli_query($connection, "SELECT coursename FROM course WHERE coursenum = '$selectedCourseValue'"))['coursename'];
        echo "<h2>$courseName - Course Offering Details</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Course Offering ID</th><th>Number of Students</th><th>Term</th><th>Year</th></tr>";

        foreach ($tableRows as $row) {
            echo "<tr>";
            echo "<td><a href='course_details.php?coid=" . $row['coid'] . "'>" . $row['coid'] . "</a></td>";
            echo "<td>" . $row['numstudent'] . "</td>";
            echo "<td>" . $row['term'] . "</td>";
            echo "<td>" . $row['year'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        // Display a message if no course is selected
        echo "<div style='border: 2px solid gray; padding: 10px; text-align: center;'>";
        echo "<p>Please select a course to view offerings.</p>";
        echo "</div>";
    }
    ?>
</div>
</div>

<!-- Go back to main menu link -->
<div class="centered-link">
    <a href='mainmenu.php'>Go back to the main page</a>
</div>

</body>
</html>

<?php
include 'footer.php';
mysqli_close($connection);
?>
