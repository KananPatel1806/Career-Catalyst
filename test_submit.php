<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure session variable exists
if (!isset($_SESSION['aptitude_questions']) || !is_array($_SESSION['aptitude_questions'])) {
    echo "<p style='color: red;'>Error: No questions were found. Please start the test again.</p>";
    exit;
}

// Ensure form submission contains answers
if (!isset($_POST['answers']) || empty($_POST['answers'])) {
    echo "<p style='color: red;'>Error: No answers received. Please select at least one answer and submit again.</p>";
    exit;
}

$correct_count = 0;
$incorrect_count = 0;
$user_answers = $_POST['answers'];
$question_results = [];

// Loop through each question and check answers
foreach ($_SESSION['aptitude_questions'] as $question) {
    $question_id = $question['id'];
    $user_answer = isset($user_answers[$question_id]) ? trim(strtoupper($user_answers[$question_id])) : 'Not Answered';

    // Fetch the correct answer from the database
    $query = "SELECT correct_option FROM aptitude_questions WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $question_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $correct_option = trim(strtoupper($row['correct_option']));

        $is_correct = ($user_answer === $correct_option);
        if ($is_correct) {
            $correct_count++;
        } else {
            $incorrect_count++;
        }

        // Store question details for display
        $question_results[] = [
            'question' => $question['question'],
            'options' => [
                'A' => $question['option_a'],
                'B' => $question['option_b'],
                'C' => $question['option_c'],
                'D' => $question['option_d']
            ],
            'user_answer' => $user_answer,
            'correct_answer' => $correct_option,
            'is_correct' => $is_correct
        ];
    }
    $stmt->close();
}

// Clear session questions after test submission
unset($_SESSION['aptitude_questions']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aptitude Test Results</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f8ff, #e6f7ff);
            text-align: center;
            padding: 50px;
        }
        .result-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: auto;
        }
        h2 {
            color: #007bff;
        }
        .summary {
            font-size: 18px;
            margin: 15px 0;
        }
        .question-container {
            text-align: left;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .wrong {
            color: red;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="result-container">
    <h2>Aptitude Test Results</h2>
    <p class="summary"><strong>Correct Answers:</strong> <?= $correct_count ?></p>
    <p class="summary"><strong>Incorrect Answers:</strong> <?= $incorrect_count ?></p>

    <h3>Question Review</h3>
    <?php foreach ($question_results as $index => $question): ?>
        <div class="question-container">
            <p><strong><?= ($index + 1) . ". " . htmlspecialchars($question['question']) ?></strong></p>
            <?php foreach ($question['options'] as $key => $option): ?>
                <p <?= ($key === $question['correct_answer']) ? 'class="correct"' : '' ?>>
                    <?= "$key. " . htmlspecialchars($option) ?>
                </p>
            <?php endforeach; ?>
            <p>
                Your Answer: <span class="<?= $question['is_correct'] ? 'correct' : 'wrong' ?>">
                    <?= htmlspecialchars($question['user_answer']) ?>
                </span>
            </p>
            <?php if (!$question['is_correct']): ?>
                <p>Correct Answer: <span class="correct"><?= htmlspecialchars($question['correct_answer']) ?></span></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <a href="student_dashboard.php" class="btn">Take Another Test</a>
</div>

</body>
</html>
