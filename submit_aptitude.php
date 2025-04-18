<?php
session_start();
include 'db.php';

// Ensure student ID is set in session
if (!isset($_SESSION['student_id'])) {
    die("<h3 class='text-danger text-center'>⚠️ Unauthorized access. Please log in.</h3>");
}

$student_id = $_SESSION['student_id'];
$submitted_answers = $_POST['answers'];
$questions = $_SESSION['aptitude_questions'];
$score = 0;
$total_questions = count($questions);

// Calculate the aptitude score
foreach ($questions as $question) {
    $question_id = $question['id'];
    $correct_option = $question['correct_option'];
    $user_answer = isset($submitted_answers[$question_id]) ? $submitted_answers[$question_id] : "N/A";

    if ($user_answer === $correct_option) {
        $score++;
    }
}

// ✅ Store the aptitude score in the database AFTER calculating it
$updateQuery = "UPDATE students SET aptitude_score = ? WHERE id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ii", $score, $student_id);

if ($stmt->execute()) {
    echo "<h4 class='text-success text-center'>🎉 Your score has been saved successfully!</h4>";
} else {
    echo "<h4 class='text-danger text-center'>⚠️ Error saving score. Please try again.</h4>";
}

// Close statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Test Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
        }
        .container {
            max-width: 900px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .table th {
            background-color: #007b83;
            color: white;
        }
        .score-section {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
        }
        .btn-custom {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            transition: 0.3s ease-in-out;
        }
        .btn-apply {
            background: linear-gradient(135deg, #17a2b8, #117a8b);
        }
        .btn-apply:hover {
            background: linear-gradient(135deg, #117a8b, #0d5f73);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center text-primary mb-4">Aptitude Test Results</h2>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question): 
                $question_id = $question['id'];
                $correct_option = $question['correct_option'];
                $user_answer = isset($submitted_answers[$question_id]) ? $submitted_answers[$question_id] : "N/A";
                $is_correct = ($user_answer === $correct_option);
            ?>
            <tr>
                <td><?= htmlspecialchars($question['question']) ?></td>
                <td><?= htmlspecialchars($user_answer) ?></td>
                <td><?= htmlspecialchars($correct_option) ?></td>
                <td class="<?= $is_correct ? 'text-success' : 'text-danger' ?>">
                    <?= $is_correct ? "✅ Correct" : "❌ Wrong" ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="score-section">
        Your Score: <?= $score ?> / <?= $total_questions ?>
    </div>

    <div class="text-center mt-4">
        <?php if ($score > 6): ?>
            <h3 class="text-success">✅ Congratulations! You are eligible to apply for jobs.</h3>
            <a href="view_jobs.php" class="btn-custom btn-apply">Apply for Jobs</a>
        <?php else: ?>
            <h3 class="text-danger">❌ You are not eligible to apply for jobs.</h3>
            <a href="student_dashboard.php" class="btn-custom btn-apply">Dashboard</a>
        <?php endif; ?>
    </div>
</div>

<?php unset($_SESSION['aptitude_questions']); ?>

</body>
</html>
