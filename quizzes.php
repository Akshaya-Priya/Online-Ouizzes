<?php
include('config/config.php'); // Include your database configuration

$current_user_id = $_SESSION['user_id'];

$query = "SELECT * FROM quizzes WHERE creater_name != :current_user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['current_user_id' => $current_user_id]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Quizzes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('includes/navbar.php'); ?>

    <div class="container">
        <h2>Available Quizzes</h2>
        <table>
            <thead>
                <tr>
                    <th>Quiz Title</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                        <td><?php echo htmlspecialchars($quiz['category']); ?></td>
                        <td><a href="attempt_quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="button">Attempt Quiz</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
