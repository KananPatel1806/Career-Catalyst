<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$student_id = $_SESSION['student_id']; // Assuming student ID is stored in session

// Fetch student's aptitude score
$score_query = "SELECT aptitude_score FROM students WHERE id = ?";
$stmt = $conn->prepare($score_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$score_result = $stmt->get_result();
$student = $score_result->fetch_assoc();

if ($student && $student['aptitude_score'] !== null) {
    // Student has already taken the test, show message instead
    echo "
    <div style='
        max-width: 500px; 
        margin: 50px auto; 
        padding: 30px; 
        background: white; 
        border-radius: 10px; 
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
        text-align: center;'>
        
        <h2 style='color: #d32f2f; font-size: 22px; margin-bottom: 15px;'>❌ You have already completed the Aptitude Test</h2>
        
        <p style='font-size: 18px; color: #333;'>Your Score: <strong>" . htmlspecialchars($student['aptitude_score']) . "</strong></p>
        
        <a href='student_dashboard.php' style='
            display: inline-block; 
            width: 200px; 
            padding: 12px; 
            font-size: 16px; 
            background: linear-gradient(135deg, #007b83, #005f66); 
            color: white; 
            border: none; 
            text-decoration: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: transform 0.3s ease, background-color 0.3s ease; 
            margin-top: 15px;'>
            Go to Dashboard
        </a>
    </div>";
    
    exit(); // Stop the script from displaying the test questions
}

// Fetch 10 Random Questions only if the student hasn't taken the test
$query = "SELECT * FROM aptitude_questions ORDER BY RAND() LIMIT 10";
$result = $conn->query($query);

if (!$result) {
    die("<h3>❌ SQL Error:</h3> " . $conn->error);
}

// Store Questions in Session
$_SESSION['aptitude_questions'] = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Test</title>
    <link rel="stylesheet" href="styles.css">
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
        .header {
            background: linear-gradient(135deg, #1d5563, #007b83);
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid #003b4d;
            animation: slideDown 1s ease-in-out;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s;
        }
        h2 {
            text-align: center;
            color: #007b83;
        }
        h4 {
            color: #004d61;
            margin-bottom: 5px;
        }
        .question {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        ::-webkit-scrollbar {
            display: none;
        }
        .btn-custom {
            display: inline-block;
            width: 200px;
            padding: 12px;
            font-size: 16px;
            text-align: center;
            background: linear-gradient(135deg, #f2b600, #e89b00);
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
            margin: 10px;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #e89b00, #d68200);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transform: scale(1.05);
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
    <h1>Aptitude Test</h1>
</div>

<div class="container">
    <form action="submit_aptitude.php" method="post">
        <?php foreach ($_SESSION['aptitude_questions'] as $index => $question): ?>
            <div class="question">
                <h4>Category: <?= htmlspecialchars($question['category']) ?></h4>
                <p><?= ($index + 1) . ". " . htmlspecialchars($question['question']) ?></p>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="A" required> <?= htmlspecialchars($question['option_a']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="B"> <?= htmlspecialchars($question['option_b']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="C"> <?= htmlspecialchars($question['option_c']) ?></label><br>
                <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="D"> <?= htmlspecialchars($question['option_d']) ?></label>
            </div>
        <?php endforeach; ?>
        
        <div class="btn-container">
            <button type="submit" class="btn-custom">Submit Exam</button>
            <a href="student_dashboard.php" class="btn-custom">Dashboard</a>
        </div>
    </form><br/>
</div><br/>

<div class="footer">
    <p>&copy; <?= date("Y") ?> Career Catalyst | All Rights Reserved</p>
</div>

</body>
</html>
