<?php
require 'db.php';
session_start();

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

// Get the job ID from the query parameter
if (!isset($_GET['job_id'])) {
    header("Location: view_jobs.php");
    exit();
}

$job_id = $_GET['job_id'];
$student_id = $_SESSION['student_id'];

// Fetch job details
$sql = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement preparation is successful
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: view_jobs.php");
    exit();
}

$job = $result->fetch_assoc();

// Check if the student has already applied for the job
$sql_check = "SELECT * FROM job_applications WHERE student_id = ? AND job_id = ?";
$stmt_check = $conn->prepare($sql_check);

if ($stmt_check === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt_check->bind_param("ii", $student_id, $job_id);
$stmt_check->execute();
$application_result = $stmt_check->get_result();

if ($application_result->num_rows > 0) {
    $message = "You have already applied for this job.";
    $already_applied = true;
} else {
    $already_applied = false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$already_applied) {
    // Check if the resume file was uploaded
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resume = $_FILES['resume'];

        // Generate a unique file name for the uploaded file
        $upload_dir = 'uploads/resumes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if not exists
        }

        $resume_path = $upload_dir . uniqid() . '_' . basename($resume['name']);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($resume['tmp_name'], $resume_path)) {
            // Insert job application
            $sql_apply = "INSERT INTO job_applications (student_id, job_id, resume_path) VALUES (?, ?, ?)";
            $stmt_apply = $conn->prepare($sql_apply);

            if ($stmt_apply) {
                $stmt_apply->bind_param("iis", $student_id, $job_id, $resume_path);
                if ($stmt_apply->execute()) {
                    $message = "Application submitted successfully!";
                    $application_success = true;

                    // Insert notification for admin
                    $notification_message = "Student ID: $student_id applied for Job ID: $job_id.";
                    $sql_notify = "INSERT INTO notifications (admin_id, student_id, job_id, message) VALUES (?, ?, ?, ?)";
                    $stmt_notify = $conn->prepare($sql_notify);

                    if ($stmt_notify) {
                        $admin_id = 1; // Replace with your actual admin ID
                        $stmt_notify->bind_param("iiis", $admin_id, $student_id, $job_id, $notification_message);
                        $stmt_notify->execute();
                    } else {
                        error_log("Error inserting notification: " . $conn->error);
                    }
                } else {
                    $message = "Error submitting application.";
                    $application_success = false;
                }
            } else {
                $message = "Error preparing application statement.";
                $application_success = false;
            }
        } else {
            $message = "Error uploading resume.";
            $application_success = false;
        }
    } else {
        $message = "No file was uploaded or there was an upload error.";
        $application_success = false;
    }
}






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - <?php echo htmlspecialchars($job['title']); ?></title>
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
        }  width: 100%;
        
        .btn-custom {
        width: 250px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        background: linear-gradient(135deg, #f2b600, #e89b00);
        border: none;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 5px;
    }
    .btn-custom:hover {
        background: linear-gradient(135deg, #e89b00, #d68200);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transform: scale(1.1);
    }
    </style>
</head>
<body>


    
    <!-- Header -->
    <div class="header">
        <h1>Apply for Job: <?php echo htmlspecialchars($job['title']); ?></h1>
    </div>
    <nav class="navbar d-flex justify-content-center">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="view_jobs.php">Job Opportunities</a>
        <a href="view_questions.php">Interview Material</a>
        <a href="logout.php">Logout</a>
        <a href="notifications.php" class="position-relative">
            <i class="fas fa-bell"></i>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $application_success ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!$already_applied): ?>
            <h4>Job Details:</h4>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>CTC:</strong> <?php echo htmlspecialchars($job['ctc']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
            
            <form action="apply_job.php?job_id=<?php echo $job_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="resume" class="form-label">Upload Resume (PDF, DOC, DOCX)</label>
                    <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </form>
        <?php else: ?>
            <p>You have already applied for this job.</p>
            <a href="view_jobs.php" class="btn btn-warning">Back to Jobs</a>
        <?php endif; ?>
        <div class="card-body text-center">
                 <a href="test.php" class="btn btn-lg btn-custom">Practice Exam</a>
            </div>
    </div><br/>

    <!-- Footer -->
  <div class="footer">
        <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
  </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
