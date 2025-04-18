<?php
session_start();
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch category names from the 'category' table
$categoryQuery = "SELECT name FROM category";
$categoryResult = $conn->query($categoryQuery);

// Handle category selection and fetch questions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category']) && !empty($_POST['category'])) {
    $_SESSION['selected_category'] = $_POST['category'];
    $selected_category = $conn->real_escape_string($_POST['category']);
    
    // Fetch 10 random questions
    $query = "SELECT id, question, option_a, option_b, option_c, option_d FROM aptitude_questions WHERE category = '$selected_category' ORDER BY RAND() LIMIT 10";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $_SESSION['aptitude_questions'] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $_SESSION['aptitude_questions'] = [];
    }
} 
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
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f8ff, #e6f7ff);
            margin: 0;
            padding: 0;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .question {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 15px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>


<div class="container">
    <h2>Start Your Aptitude Test</h2>
    <form method="post">
        <label>Select Category:</label>
        <select name="category" required>
            <option value="">-- Select --</option>
            <?php while ($row = $categoryResult->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['name']) ?>" <?= isset($_SESSION['selected_category']) && $_SESSION['selected_category'] == $row['name'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn">Start Test</button>
    </form>

    <?php if (!empty($_SESSION['aptitude_questions'])): ?>
        <form action="test_submit.php" method="post">
            <?php foreach ($_SESSION['aptitude_questions'] as $index => $question): ?>
                <div class="question">
                    <p><strong><?= ($index + 1) . ". " . htmlspecialchars($question['question']) ?></strong></p>
                    <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="A" required> <?= htmlspecialchars($question['option_a']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="B"> <?= htmlspecialchars($question['option_b']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="C"> <?= htmlspecialchars($question['option_c']) ?></label><br>
                    <label><input type="radio" name="answers[<?= $question['id'] ?>]" value="D"> <?= htmlspecialchars($question['option_d']) ?></label>
                </div>
            <?php endforeach; ?>
            <div class="btn-container">
                <button type="submit" class="btn">Submit Exam</button>
            </div>
        </form>
    <?php endif; ?>
</div>



</body>
</html>
