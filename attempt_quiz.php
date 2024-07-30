<?php
include('config/config.php'); // Include your database configuration

$quiz_id = $_GET['quiz_id'];

// Fetch quiz details
$query = "SELECT * FROM quizzes WHERE id = :quiz_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch quiz questions and options
$query = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attempt Quiz</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('includes/navbar.php'); ?>

    <div class="container">
        <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
        <form action="submit_quiz.php" method="POST">
            <input type="hidden" name="quiz_id" value="<?php echo $quiz['id']; ?>">
            <?php foreach ($questions as $index => $question): ?>
                <div class="question-block">
                    <h4><?php echo ($index + 1) . '. ' . htmlspecialchars($question['question_text']); ?></h4>
                    <?php 
                    $options_query = "SELECT * FROM options WHERE question_id = :question_id";
                    $options_stmt = $pdo->prepare($options_query);
                    $options_stmt->execute(['question_id' => $question['id']]);
                    $options = $options_stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($options as $option): ?>
                        <div class="option-block">
                            <label>
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>" required>
                                <?php echo htmlspecialchars($option['option_text']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Quiz</button>
        </form>
    </div>
</body>
</html>
