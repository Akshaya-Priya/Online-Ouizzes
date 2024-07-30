<?php
include('config/config.php'); // Include your database configuration

$user_id = $_SESSION['user_id'];
$quiz_id = $_POST['quiz_id'];
$answers = $_POST['answers'];

// Fetch correct answers
$query = "SELECT id, correct_answer FROM questions WHERE quiz_id = :quiz_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['quiz_id' => $quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$correct_answers = 0;
$total_questions = count($questions);

foreach ($questions as $question) {
    $correct_option_query = "SELECT id FROM options WHERE question_id = :question_id AND is_correct = 1";
    $correct_option_stmt = $pdo->prepare($correct_option_query);
    $correct_option_stmt->execute(['question_id' => $question['id']]);
    $correct_option = $correct_option_stmt->fetch(PDO::FETCH_ASSOC);

    if ($answers[$question['id']] == $correct_option['id']) {
        $correct_answers++;
    }
}

// Save the result to the database
$query = "INSERT INTO results (user_id, quiz_id, correct_answers, total_questions,score) VALUES (:user_id, :quiz_id, :correct_answers, :total_questions, :score)";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'user_id' => $user_id,
    'quiz_id' => $quiz_id,
    'correct_answers' => $correct_answers,
    'total_questions' => $total_questions,
    'score' =>  ($correct_answers/$total_questions *100)
]);

header('Location: quiz_result.php?quiz_id=' . $quiz_id);
?>
