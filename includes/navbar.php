<nav>
    <ul>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="quizzes.php">AttemptQuiz</a></li>
            <li><a href="add_quiz.php">AddQuiz</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
