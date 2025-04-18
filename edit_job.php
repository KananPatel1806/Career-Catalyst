<?php
require 'db.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the job ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_jobs.php");
    exit();
}

$job_id = $_GET['id'];

// Fetch job details
$sql = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_jobs.php");
    exit();
}

$job = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $ctc = $_POST['ctc'];

    // Validate inputs
    if (empty($title) || empty($location) || empty($ctc)) {
        $error = "All fields are required.";
    } else {
        // Update job details
        $sql = "UPDATE jobs SET title = ?, location = ?, ctc = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $location, $ctc, $job_id);

        if ($stmt->execute()) {
            header("Location: admin_jobs.php");
            exit();
        } else {
            $error = "Failed to update the job. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - Admin Panel</title>
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
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: bolder;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-label {
            font-weight: 600;
        }
        .header {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header a {
            color: white;
            text-decoration: none;
            padding: 5px 15px;
        }
        .header a:hover {
            background-color: #495057;
            border-radius: 5px;
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

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card p-4">
                    <h2 class="card-title text-center mb-4">Edit Job Details</h2>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="ctc" class="form-label">CTC (in INR)</label>
                            <input type="text" class="form-control" id="ctc" name="ctc" value="<?php echo htmlspecialchars($job['ctc']); ?>" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update Job</button>
                            <a href="manage_jobs.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-center mt-5 ">
        <p>&copy; <?php echo date("Y"); ?>  Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
