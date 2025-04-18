<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

// Fetch new job notifications
$sql = "SELECT title, description, posted_date FROM jobs WHERE notification_status = 0";
$result = $conn->query($sql);

// Debugging: Check if there are any issues with the query
if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Notifications</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f8ff, #e6f7ff); /* Subtle gradient */
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .header {
            background: linear-gradient(135deg, #1d5563, #007b83);
            color: white;
            padding: 20px 0;
            text-align: center;
            border-bottom: 4px solid #003b4d;
            animation: slideDown 1s ease-in-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        .navbar {
            background: linear-gradient(135deg, #729b9e, #004d61);
            color: white;
            padding: 10px 0;
        }
        .navbar a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
            font-size: 18px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .navbar a:hover {
            background-color: #becfd1;
            transform: scale(1.1);
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .card-body {
            background-color: #e9ecef;
        }
        .footer {
            background: linear-gradient(135deg, #1d5563, #007b83);
            color: white;
            text-align: center;
            padding: 15px 0;
           
            position: relative;
            width: 100%;
        }
        @media (max-width: 768px) {
            .footer {
                font-size: 14px;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Notifications</h1>
    </div>
    <nav class="navbar d-flex justify-content-center">
        <a href="student_dashboard.php" class="btn btn-extra btn-sm">Dashboard</a>
        <a href="view_jobs.php" class="btn btn-extra btn-sm">Job Opportunities</a>
        <a href="logout.php" class="btn btn-extra btn-sm">Logout</a>
        <a href="notifications.php" class="position-relative">
            <i class="fas fa-bell"></i>
        </a>
    </nav>
    <div class="container">
        <h1 class="text-center">New Job Notifications</h1>
        <p class="text-center">Here are the latest job postings:</p>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <small>Posted on: <?php echo htmlspecialchars($row['posted_date']); ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                No new job notifications available at the moment.
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
