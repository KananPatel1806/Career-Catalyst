<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit();
}

require 'db.php';

// Get logged-in student details
$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];
$student_image = $_SESSION['student_image'];

// Fetch new job notifications
$sql = "SELECT COUNT(*) AS new_jobs_count FROM jobs WHERE notification_status = 0";
$result = $conn->query($sql);
$new_jobs_count = 0;

if ($result) {
    $row = $result->fetch_assoc();
    $new_jobs_count = $row['new_jobs_count'];
}

// Mark notifications as seen after displaying
//if ($new_jobs_count > 0) {
    //$update_sql = "UPDATE jobs SET notification_status = 1";
    //$conn->query($update_sql);
//}

// Check if the notification modal has already been shown in this session
if (!isset($_SESSION['notification_shown']) && $new_jobs_count > 0) {
    $_SESSION['notification_shown'] = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
    ::-webkit-scrollbar {
            display: none;
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
    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 20px;
        border: 5px solid #007b83;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        animation: bounceIn 1.5s;
    }
    @keyframes bounceIn {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-30px);
        }
        60% {
            transform: translateY(-15px);
        }
    }
    .card {
        width: 100%;
        max-width: 500px;
        margin: 20px auto;
        background: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
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
    .badge {
        background-color: #f2b600;
        color: white;
        font-size: 12px;
    }
</style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Student Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($student_name); ?>!</p>
    </div>

    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-center">
        <a href="view_jobs.php" class="btn btn-extra btn-sm">Job Opportunities</a>
        <a href="view_questions.php" class="btn btn-extra btn-sm">Interview Material</a>
        <a href="logout.php" class="btn btn-extra btn-sm">Logout</a>
        <a href="notifications.php" class="btn-sm position-relative" title="New Job Notifications">
            <i class="fas fa-bell"></i>
            <?php if ($new_jobs_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $new_jobs_count; ?>
                </span>
            <?php endif; ?>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="container pt-4 mb-5">
        <div class="row justify-content-center">
        <div class="card-body text-center">
            <a href="aptitude_test.php" class="btn btn-lg btn-custom">Aptitude Exam</a>
        </div>

            <div class="col-lg-12 text-center mt-5">
                <img src="uploads/<?php echo htmlspecialchars($student_image); ?>" alt="Profile Image" class="profile-image">
                <div class="card">
                    <div class="card-header">Your Profile</div>
                    <div class="card-body">
                        <a href="student_profile.php" class="btn btn-lg btn-custom">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div><br/>

   <!-- Footer -->
   <div class="footer">
         <p>© <?php echo date("Y"); ?> Your University. Innovating the future of education and career development.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 