<?php
include('config/config.php'); // Adjust this to your database configuration file
 // Start the session
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $questions = $_POST['questions'];

    // Insert quiz
    $sql = "INSERT INTO quizzes (title, category, creater_name, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $category, $_SESSION['user_id']]);
    $quiz_id = $pdo->lastInsertId();

    // Insert questions and options
    foreach ($questions as $question) {
        $sql = "INSERT INTO questions (quiz_id, question_text, correct_answer) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quiz_id, $question['question'], $question['correct_answer']]);
        $question_id = $pdo->lastInsertId();

        foreach ($question['options'] as $index => $option) {
            $sql = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $is_correct = $index == $question['correct_answer'] ? 1 : 0;
            $stmt->execute([$question_id, $option, $is_correct]);


            if ($is_correct) {
                $update_question_query = "UPDATE questions SET correct_answer = :correct_answer WHERE id = :question_id";
                $update_question_stmt = $pdo->prepare($update_question_query);
                $update_question_stmt->execute([
                    'correct_answer' => $option,
                    'question_id' => $question_id
                ]);
            }
        }
    }

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#add-question").click(function(){
                var questionCount = $(".question-block").length + 1;
                var questionHtml = `
                    <div class="question-block">
                        <h4>Question ${questionCount}</h4>
                        <div>
                            <label for="question${questionCount}">Question:</label>
                            <input type="text" id="question${questionCount}" name="questions[${questionCount}][question]" required>
                        </div>
                        <br>
                        <h5>Options</h5>
                        <div>
                            <label for="option${questionCount}_1">Option 1:</label>
                            <input type="text" id="option${questionCount}_1" name="questions[${questionCount}][options][]" required>
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="0" required> Correct
                        </div>
                        <br>
                        <div>
                            <label for="option${questionCount}_2">Option 2:</label>
                            <input type="text" id="option${questionCount}_2" name="questions[${questionCount}][options][]" required>
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="1" required> Correct
                        </div>
                        <br>
                        <div>
                            <label for="option${questionCount}_3">Option 3:</label>
                            <input type="text" id="option${questionCount}_3" name="questions[${questionCount}][options][]" required>
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="2" required> Correct
                        </div><br>
                        <div>
                            <label for="option${questionCount}_4">Option 4:</label>
                            <input type="text" id="option${questionCount}_4" name="questions[${questionCount}][options][]" required>
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="3" required> Correct
                        </div><br>
                        <br><br>
                    </div>
                `;
                $("#questions-container").append(questionHtml);
            });
        });
    </script>
</head>
<body>
    <?php include('includes/navbar.php'); ?>
    <div class="container">
        <h2>Create New Quiz</h2>
        <form action="add_quiz.php" method="POST" id="add_quiz">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>
        </div>
            
        <h3>Questions</h3>
        <div id="questions-container">
        <div class="question-block">
            <h4>Question 1</h4>
            <div>
                <label for="question1">Question:</label>
                <input type="text" id="question1" name="questions[1][question]" required>
            </div><br>
            <h5>Options</h5>
            <div>
                <label for="option1_1">Option 1:</label>
                <input type="text" id="option1_1" name="questions[1][options][]" required>
                <input type="radio" name="questions[1][correct_answer]" value="0" required> Correct
            </div><br>
            <div>
                <label for="option1_2">Option 2:</label>
                <input type="text" id="option1_2" name="questions[1][options][]" required>
                <input type="radio" name="questions[1][correct_answer]" value="1" required> Correct
            </div><br>
            <div>
                <label for="option1_3">Option 3:</label>
                <input type="text" id="option1_3" name="questions[1][options][]" required>
                <input type="radio" name="questions[1][correct_answer]" value="2" required> Correct
            </div><br>
            <div>
                <label for="option1_4">Option 4:</label>
                <input type="text" id="option1_4" name="questions[1][options][]" required>
                <input type="radio" name="questions[1][correct_answer]" value="3" required> Correct
            </div><br>
        </div>
        </div>
        <button type="button" id="add-question">Add Another Question</button><br><br>
        <div>
            <button type="submit">Create Quiz</button>
        </div>
        </form>
    </div>
</body>
</html>
