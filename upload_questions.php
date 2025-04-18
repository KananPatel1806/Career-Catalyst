<?php
require 'db.php';

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['upload_file'])) {
    $file_name = basename($_FILES['upload_file']['name']);
    $source_file = $_FILES['upload_file']['tmp_name'];
    $target_dir = "uploads/questions/";
    $target_file = $target_dir . $file_name;
    $language = $_POST['language'] ?? 'English';

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Move the file and insert details into the database
    if (move_uploaded_file($source_file, $target_file)) {
        $sql = "INSERT INTO interview_questions (language, file_name) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $language, $file_name);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>File '$file_name' uploaded successfully and recorded in the database.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Database error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-danger'>Error: Failed to upload the file.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Upload - Interview Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
        font-size: 20px !important;
        color: white;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        transition: color 0.3s ease, background-color 0.3s ease;
    }
    .nav-link:hover {
        color: #000102; /* Change text color on hover */
        background-color: rgba(255, 255, 255, 0.1); /* Add a slight background on hover */
        border-radius: 5px; /* Optional: Rounded corners for hover effect */
    }
    .navbar{
        background: linear-gradient(135deg, #004085, #003366);
    }
    .admin-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            font-weight: bolder;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control, .btn {
            margin-top: 10px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
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
                        <a class="nav-link" href="admin_jobs.php">Manage Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="create_job.php">Create Job</a>
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
    <div class="container">
        <h2>Upload Interview Material</h2>

        <!-- Display upload result message -->
        <?php if ($message): ?>
            <?php echo $message; ?>
        <?php endif; ?>

        <!-- Upload Form -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="upload_file" class="form-label">Select File:</label>
                <input type="file" name="upload_file" id="upload_file" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        <!-- Uploaded File Link -->
        <?php if (isset($file_name) && file_exists($target_file)): ?>
            <div class="mt-4">
                <h5>Uploaded File:</h5>
                <a href="<?php echo $target_file; ?>" class="btn btn-link" target="_blank">Open <?php echo htmlspecialchars($file_name); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Your Platform.  Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
