<?php
session_start();

// Toggle dark mode
if (isset($_GET['toggle'])) {
    $_SESSION['dark_mode'] = !isset($_SESSION['dark_mode']) || !$_SESSION['dark_mode'];
    header('Location: ' . $_SERVER['PHP_SELF']); // Redirect to the same page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Mode Toggle</title>
    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .light {
            background-color: white;
            color: black;
        }

        .dark {
            background-color: #333;
            color: white;
        }

        /* Style the switch container */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Slider style */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        /* Slider before style (circle) */
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        /* When the checkbox is checked, slide the circle to the right */
        input:checked + .slider {
            background-color:rgb(0, 0, 0);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
</head>
<body class="<?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'dark' : 'light'; ?>">
    <div>
        <label class="switch">
            <input type="checkbox" <?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'checked' : ''; ?> onclick="window.location.href = '?toggle=true'">
            <span class="slider"></span>
        </label>
    </div>
</body>
</html>
