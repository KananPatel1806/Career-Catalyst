<?php
require 'db.php';

// Fetch records from the database
$sql = "SELECT * FROM interview_questions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Interview Questions Material</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .container {
            max-width: 1200px;
            margin-top: 60px;
            margin-bottom: 60px;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .table-responsive {
            margin-top: 30px;
        }
       
        .text-center {
            margin-bottom: 30px;
        }
        
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
    ::-webkit-scrollbar {
            display: none;
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
    .footer {
        background: linear-gradient(135deg, #1d5563, #007b83);
        color: white;
        text-align: center;
        padding: 15px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
      
    }
    </style>
</head>
<body>
    <div class="header">
        <h1>Material</h1>
     
    </div>
    <nav class="navbar d-flex justify-content-center">
        <a href="student_dashboard.php" class="btn btn-extra btn-sm">Dashboard</a>
        <a href="view_jobs.php" class="btn btn-extra btn-sm">Job Opportunities</a>
        <a href="logout.php" class="btn  btn-extra btn-sm">Logout</a>
        <a href="notifications.php" class="position-relative">
            <i class="fas fa-bell"></i>
        </a>
    </nav>
    <!-- Main Content -->
    <div class="container">
        <div class="card">
            
            <div class="card-body">
                <h4 class="text-center mb-4">Download Interview Questions</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">File Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php $file_path = "uploads/questions/" . $row['file_name']; ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                                        <td>
                                            <?php if (file_exists($file_path)): ?>
                                                <a href="<?php echo $file_path; ?>" class="btn btn-primary" download>Download</a>
                                            <?php else: ?>
                                                <span class="text-danger">File Missing</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No questions found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
         <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
