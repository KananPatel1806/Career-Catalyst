<?php
require 'db.php';
session_start();

// Ensure the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all jobs
$sql = "SELECT j.*,c.name as category FROM jobs j
inner join category c on c.id = j.category
ORDER BY posted_date DESC";
$result = $conn->query($sql);

// Handle query failure
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
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
            background-image: url('');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            background-attachment: fixed;
            color: #fff;
            font-weight: bolder;
        }
        ::-webkit-scrollbar {
            display: none;
        }
        .container {
            margin-top: 50px;
            background: rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-custom {
            border-radius: 20px;
            padding: 10px 20px;
        }
        .btn-create-job {
            background-color: #28a745;
            color: white;
        }
        .btn-create-job:hover {
            background-color: #218838;
        }
        .btn-edit {
            background-color: #007bff;
            border-radius: 15px;
        }
        .btn-view {
            background-color: #218838;
            border-radius: 15px;
        }
        .btn-delete {
            background-color: #dc3545;
            border-radius: 15px;
        }
        h1{
            color: black;
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
    <div class="container">
        <h1 class="text-center mb-4">Manage Jobs</h1>

        <div class="d-flex justify-content-between mb-4">
            <a href="create_job.php" class="btn btn-create-job btn-custom">Create New Job</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>CTC</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['ctc']); ?></td>
                                <td>
                                    <a href="edit_job.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-sm btn-custom">Edit</a>
                                    <a href="view_applicants.php?id=<?php echo $row['id']; ?>" class="btn btn-view btn-sm btn-custom">View</a>
                                    <a href="delete_job.php?id=<?php echo $row['id']; ?>" class="btn btn-delete btn-sm btn-custom" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No jobs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
     <!-- Footer -->
     <div class="footer">
        <p>&copy; 2025 Your Platform. Delivering innovative solutions for administrators to effectively manage student resources.</p>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
