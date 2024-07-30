<?php
// Example session start and database connection
include('config/config.php'); // Adjust this to your database configuration file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch quizzes created by the user
$sql = "SELECT * FROM quizzes WHERE creater_name = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Message if no quizzes are found
$message = (count($quizzes) > 0) ? '' : 'You have not created any quizzes yet.';


// Fetch the number of quizzes attempted by the user
$query = "SELECT COUNT(*) AS attempted_count FROM results WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$attempted_count = $stmt->fetch(PDO::FETCH_ASSOC)['attempted_count'];

// Fetch details of quizzes attempted by the user
$query = "SELECT q.title, r.correct_answers, r.total_questions, r.score
          FROM results r 
          JOIN quizzes q ON r.quiz_id = q.id 
          WHERE r.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$attempted_quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('includes/navbar.php'); ?>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        
        <h3>Your Quizzes</h3>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['category']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    
        <h3>Quizzes Attempted</h3>
        <p>You have attempted <strong><?php echo $attempted_count; ?></strong> quizzes.</p>
        <?php if ($attempted_count > 0): ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Quiz Title</th>
                        <th>Correct Answers</th>
                        <th>Total Questions</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attempted_quizzes as $quiz): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['correct_answers']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['total_questions']); ?></td>
                            <td><?php echo htmlspecialchars($quiz['score']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No quizzes attempted yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
