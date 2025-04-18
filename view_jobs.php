<?php
require 'db.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch all job opportunities with notification_status = 0
$sql = "SELECT * FROM jobs WHERE notification_status = 0 ORDER BY posted_date DESC";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM students WHERE id = $student_id";
$result1 = $conn->query($sql1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Opportunities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f8ff, #e6f7ff);
            margin: 0;
            padding: 0;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .header, .footer {
            background: linear-gradient(135deg, #1d5563, #007b83);
            color: white;
            text-align: center;
            padding: 15px;
        }
        .navbar {
            background: linear-gradient(135deg, #729b9e, #004d61);
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
            padding-top: 60px;
        }
        .job-card {
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .job-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }
        .low-score {
            background: #ffdddd;
            color: #d9534f;
            border: 2px solid #d9534f;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Job Opportunities</h1>
        <p>Explore the latest job opportunities curated for you</p>
    </div>

    <nav class="navbar d-flex justify-content-center">
        <a href="student_dashboard.php" class="btn">Dashboard</a>
        <a href="view_questions.php" class="btn">Interview Material</a>
        <a href="logout.php" class="btn">Logout</a>
        <a href="notifications.php" class="position-relative">
            <i class="fas fa-bell"></i>
        </a>
    </nav>

    <div class="container mb-5">
        <?php if ($result1 && $result1->num_rows > 0): ?>
            <?php while ($job1 = $result1->fetch_assoc()): ?>
                <?php if ($job1['aptitude_score'] > 7): ?>
                    
                    <h1>Your Aptitude Score: <?php echo $job1['aptitude_score']; ?></h1>
                    
                    <div class="row">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($job = $result->fetch_assoc()): ?>
                                <div class="col-lg-4">
                                    <div class="card job-card">
                                        <div class="card-header">
                                            <h5><?php echo htmlspecialchars($job['title']); ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                                            <p><strong>CTC:</strong> <?php echo htmlspecialchars($job['ctc']); ?></p>
                                            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                                            <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                                            <p><strong>Date Posted:</strong> <?php echo date("d M Y", strtotime($job['posted_date'])); ?></p>
                                            <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="btn btn-success">Apply Now</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <div class="alert alert-warning">
                                    No job opportunities available at the moment.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <div class="low-score">
                        <h1>Sorry, your score is too low (<?php echo $job1['aptitude_score']; ?>)you can not view job opportunities.</h1>
                    </div>
                <?php endif; ?>    
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
