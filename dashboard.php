<?php
session_start();
require 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$part_time="";
 $full_time = "";


    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Fetch course type data
    $query = "SELECT course_type, COUNT(*) as count FROM students GROUP BY course_type";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
  
    $i=0;
    while ($row = mysqli_fetch_assoc($result)) {
        if($i==0){
            $full_time = $row["count"];
            $i++;
        }else{
            $part_time = $row["count"];
        }
       //echo "". $row["course_type"] ."". $row["count"];
       // $chartData[] = [$row['course_type'], (int)$row[;'count']];
    }

    // Variables for column chart
$range_0_5 = 0;
$range_6_10 = 0;
$range_11_15 = 0;
$range_16_20 = 0;
$range_20_plus = 0;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query for CTC ranges
$qry = "
    SELECT 
        CASE 
            WHEN ctc BETWEEN 0 AND 5 THEN '0-5'
            WHEN ctc BETWEEN 6 AND 10 THEN '6-10'
            WHEN ctc BETWEEN 11 AND 15 THEN '11-15'
            WHEN ctc BETWEEN 16 AND 20 THEN '16-20'
            ELSE '20+'
        END AS ctc_range,
        COUNT(*) AS count
    FROM `jobs`
    GROUP BY ctc_range
    ORDER BY ctc_range;
";

$ret = mysqli_query($conn, $qry);

if (!$ret) {
    die("Query failed: " . mysqli_error($conn));
}

// Assign counts to variables
while ($row = mysqli_fetch_assoc($ret)) {
    switch ($row['ctc_range']) {
        case '0-5':
            $range_0_5 = $row['count'];
            break;
        case '6-10':
            $range_6_10 = $row['count'];
            break;
        case '11-15':
            $range_11_15 = $row['count'];
            break;
        case '16-20':
            $range_16_20 = $row['count'];
            break;
        case '20+':
            $range_20_plus = $row['count'];
            break;
    }
}


// Handle CGPA filtering
$cgpa_filter = isset($_GET['cgpa']) ? $_GET['cgpa'] : '';

// Fetch students based on CGPA filter
$query = "SELECT * FROM students";
if (!empty($cgpa_filter)) {
    $query .= " WHERE student_cgpa >= ?";
}

$stmt = $conn->prepare($query);
if (!empty($cgpa_filter)) {
    $stmt->bind_param("d", $cgpa_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            font-weight: bolder;
        }
        .header {
            background: linear-gradient(135deg, #004085, #003366);
            color: white;
            padding: 15px 20px;
            display: flex;
            overflow: hidden;
            align-items: center;
            justify-content: space-between;
        }
        ::-webkit-scrollbar {
            display: none;
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .footer {
            background: linear-gradient(135deg, #004085, #003366);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .dashboard-content {
            padding: 40px 0;
        }
        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .dashboard-actions a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 2px solid #ccc;
            padding: 15px;
            border-radius: 10px;
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-decoration: none;
        }
        .dashboard-actions a img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        .dashboard-actions a:hover {
            transform: scale(1.05);
            background-color: #f0f0f0;
        }
        .filter-section {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }
    .table-container {
        margin-top: 20px;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
    }
    thead {
        background: linear-gradient(135deg, #007b83, #005f66);
        color: white;
    }
    th, td {
        padding: 12px;
        text-align: center;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    tr:hover {
        background: #e6f7ff;
        transition: 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #004085, #003366);
        border: none;
        transition: 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg,rgb(110, 129, 147), #004085);
        transform: scale(1.05);
    }
    @media (max-width: 768px) {
        .filter-section .row {
            flex-direction: column;
        }
        .filter-section select,
        .filter-section button {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    </style>
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load Google Charts
        google.charts.load('current', { packages: ['corechart'] });

        // Draw Pie Chart
        google.charts.setOnLoadCallback(drawPieChart);

        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
                ['Course Type', 'Count'],
                ['Full-time', <?php echo $full_time; ?>],
                ['Internship', <?php echo $part_time; ?>]
            ]);
          
            var options = {
                title: 'Student Course Types',
                pieHole: 0.4,
                slices: {
                    0: {
                        color: '#9999ff'  // Red color for Full-time
                    },
                    1: {
                        color: '#4d4dff'  // Blue color for Internship
                    }
                },
                pieSliceTextStyle: {
                    color: 'white' // Optional: Make the text white for better visibility
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }



        // Draw Column Chart
        google.charts.setOnLoadCallback(drawColumnChart);
        function drawColumnChart() {
            var data = google.visualization.arrayToDataTable([
                ['CTC Range', 'Count', { role: 'style' }],
                ['0-5 LPA', <?php echo $range_0_5; ?>, 'color: #a0d2eb'], // Light blue
                ['6-10 LPA', <?php echo $range_6_10; ?>, 'color: #7fbce9'], // Medium light blue
                ['11-15 LPA', <?php echo $range_11_15; ?>, 'color: #5ea6e7'], // Medium blue
                ['16-20 LPA', <?php echo $range_16_20; ?>, 'color: #3c91e5'], // Deep blue
                ['20+ LPA', <?php echo $range_20_plus; ?>, 'color: #1b7be3'] // Dark blue
            ]);

            var options = {
                title: 'CTC Distribution',
                hAxis: { title: 'CTC Range' },
                vAxis: { title: 'Count' },
                legend: 'none',
                bar: { groupWidth: '80%' },
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('columnchart'));
            chart.draw(data, options);
        }

            // Redraw charts on window resize
            window.addEventListener('resize', function () {
                drawColumnChart();
            });


        
        // Redraw charts on window resize
        window.addEventListener('resize', function () {
            drawPieChart();
            drawColumnChart();
        });
    </script>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <img src="uploads/admin.jpg" alt="Admin Profile Picture" >
            <div>
                <h1>Admin Dashboard</h1>
                <p class="lead">Welcome, Admin! Manage the platform effectively.</p>
            </div>
        </div>
        <a href="logout.php"><img src="uploads/logout.jpg" alt="Logout Icon"></a>
    </div>

    <!-- Main Content -->
    <div class="container dashboard-content">
        <div class="dashboard-actions">
            <a href="view_users.php" class="btn btn-custom">
                <img src="uploads/database.png" alt="Student Database Icon">
                Student Database
            </a>
            <a href="admin_jobs.php" class="btn btn-custom">
                <img src="uploads/manage_job.png" alt="Manage Jobs Icon">
                Manage Jobs
            </a>
            <a href="create_job.php" class="btn btn-custom">
                <img src="uploads/jobs.webp" alt="Create Job Icon">
                Create New Job
            </a>
            <a href="upload_questions.php" class="btn btn-custom">
                <img src="uploads/upload.png" alt="Upload Material Icon">
                Upload Material
            </a>
            <a href="view_questions_admin.php" class="btn btn-custom">
                <img src="uploads/uploaded.png" alt="Uploaded Material Icon">
                Uploaded Material
            </a>
        </div>
    </div>


     <!-- Charts Section -->
     <div class="charts text-center">
    <h3>Statistics</h3>
    <div class="row">
        <div class="col-12 col-md-6">
            <div id="piechart" style="width: 100%; height: 400px;"></div>
        </div>
        <div class="col-12 col-md-6">
            <div id="columnchart" style="width: 100%; height: 400px;"></div>
        </div>
    </div> <br/>
</div>
    
<div class="container dashboard-content">
    <h2 class="text-center text-primary">Filter Students by CGPA</h2>

    <!-- Filter Form -->
    <form method="GET" action="" class="filter-section mt-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <label for="cgpa" class="form-label fw-bold">Select CGPA:</label>
                <select name="cgpa" id="cgpa" class="form-select">
                    <option value="">-- Select CGPA --</option>
                    <option value="6.0" <?= ($cgpa_filter == "6.0") ? 'selected' : '' ?>>Above 6.0</option>
                    <option value="7.0" <?= ($cgpa_filter == "7.0") ? 'selected' : '' ?>>Above 7.0</option>
                    <option value="8.0" <?= ($cgpa_filter == "8.0") ? 'selected' : '' ?>>Above 8.0</option>
                    <option value="9.0" <?= ($cgpa_filter == "9.0") ? 'selected' : '' ?>>Above 9.0</option>
                </select>
            </div>
            <div class="col-md-2 mt-5">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Students Table -->
    <div class="table-container">
        <table class="table table-bordered table-striped text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Aptitude Score</th>
                    <th>CGPA</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['aptitude_score']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_cgpa']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_phone']); ?></td>
                    </tr>
                <?php } ?>
                <?php if ($result->num_rows == 0) { ?>
                    <tr>
                        <td colspan="6" class="text-center text-danger">No students found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<br/><br/>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
