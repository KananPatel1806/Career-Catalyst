<?php
require 'db.php';

// Get job_id from URL
if (!isset($_GET['id'])) {
    die("Job ID is required.");
}

$job_id = intval($_GET['id']);

// Fetch job details
$job_sql = "SELECT * FROM jobs WHERE id = ?";
$job_stmt = $conn->prepare($job_sql);
$job_stmt->bind_param("i", $job_id);
$job_stmt->execute();
$job_result = $job_stmt->get_result();
$job = $job_result->fetch_assoc();

if (!$job) {
    die("Job not found.");
}

// Fetch applicants for the job
$app_sql = "SELECT ja.*, s.name, s.image AS profile_image, s.email, s.student_phone AS phone, ja.resume_path 
            FROM job_applications ja 
            INNER JOIN students s ON ja.student_id = s.id 
            WHERE ja.job_id = ?";
$app_stmt = $conn->prepare($app_sql);
$app_stmt->bind_param("i", $job_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Job: <?php echo htmlspecialchars($job['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
          .nav-link {
        font-size: 20px !important;
        color: white;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        transition: color 0.3s ease, background-color 0.3s ease;
    }
    .navbar{
        background: linear-gradient(135deg, #004085, #003366);
    }
    .nav-link:hover {
        color: #000102; /* Change text color on hover */
        background-color: rgba(255, 255, 255, 0.1); /* Add a slight background on hover */
        border-radius: 5px; /* Optional: Rounded corners for hover effect */
    }
    .admin-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        ::-webkit-scrollbar {
            display: none;
        }
        body {
            background-image: url('');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            background-attachment: fixed;
            color: #fff;
            font-weight: bolder;
        }
        .container {
            margin-top: 50px;
            background: rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            background-color:rgb(2, 38, 77);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .applicant-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .table th {
            background-color: #007bff;
            color: white;
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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <img src="uploads/admin.jpg" alt="Admin" class="admin-image">
                
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="view_users.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_job.php">Create Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upload_questions.php">Upload  Material</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_questions_admin.php">Uploaded  Material</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="logout.php">
                        <img src="uploads/logout.jpg" alt="Logout" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Header -->
    <div class="header">
        <h1>Applicants for Job: <?php echo htmlspecialchars($job['title']); ?></h1>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?> | 
           <strong>CTC:</strong> <?php echo htmlspecialchars($job['ctc']); ?></p>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Student Image</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Profile</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($app_result->num_rows > 0): ?>
                            <?php while ($applicant = $app_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="d-flex justify-content-center align-items-center">
                                        <img src="uploads/<?php echo htmlspecialchars($applicant['profile_image']); ?>" 
                                            alt="Profile Image" class="applicant-image">
                                    </td>
                                    <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                                    <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                                    <td><?php echo htmlspecialchars($applicant['phone']); ?></td>
                                    <td>
                                        <a href="view_profile.php?student_id=<?php echo urlencode($applicant['student_id']); ?>" 
                                        class="btn btn-primary btn-sm">View Profile</a>
                                    </td>
                                    <td>
                                        <a href="<?php echo htmlspecialchars($applicant['resume_path']); ?>" 
                                           class="btn btn-success btn-sm" download>Download Resume</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No students have applied for this job yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?>  Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
