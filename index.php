<!DOCTYPE html>
<html>
<head>
    <title>Online Quiz System</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
</head>
<body style="background-color:white;">
    <?php session_start(); ?>
    <?php include('includes/navbar.php'); ?>
    <div class="container">
        <img src="images/image.gif">
    </div>
    <div class="container">
    <?php if (isset($_SESSION['user_id'])): ?>
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>, to the Online Quiz System</h1>
            <a class="button" href="add_quiz.php">Add New Quiz</a>
            <a class="button" href="quizzes.php">Attempt Quiz</a>
        <?php else: ?>
            <h1>Welcome to the Online Quiz System</h1>
            <a class="button" href="register.php">Register</a>
            <a class="button" href="login.php">Login</a>
        <?php endif; ?>
    </div>
</body>
</html>


