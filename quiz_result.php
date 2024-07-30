<?php
include('config/config.php'); // Include your database configuration

$quiz_id = $_GET['quiz_id'];
$user_id = $_SESSION['user_id'];

// Fetch quiz result
$query = "SELECT * FROM results WHERE quiz_id = :quiz_id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'quiz_id' => $quiz_id,
    'user_id' => $user_id
]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch quiz details
$query = "SELECT title FROM quizzes WHERE id = :quiz_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch leaderboard
$query = "SELECT users.username, results.correct_answers, results.total_questions 
          FROM results 
          JOIN users ON results.user_id = users.id 
          WHERE quiz_id = :quiz_id 
          ORDER BY correct_answers DESC 
          LIMIT 10";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quiz_id]);
$leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('includes/navbar.php'); ?>

    <div class="container">
        <h2><?php echo htmlspecialchars($quiz['title']); ?> - Result</h2>
        <p>Correct Answers: <?php echo $result['correct_answers']; ?></p>
        <p>Total Questions: <?php echo $result['total_questions']; ?></p>

        <h3>Leaderboard</h3>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Correct Answers</th>
                    <th>Total Questions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leaderboard as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['username']); ?></td>
                        <td><?php echo htmlspecialchars($entry['correct_answers']); ?></td>
                        <td><?php echo htmlspecialchars($entry['total_questions']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
