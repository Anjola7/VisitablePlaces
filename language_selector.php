<?php
session_start();

// Kontrollo nëse është bërë zgjedhja e gjuhës dhe përfshi skedarin përkatës
if (isset($_POST['language'])) {
    $language = $_POST['language'];
    setcookie('language', $language, time() + (86400 * 30), "/"); // Ruaj zgjedhjen në cookie për 30 ditë
    echo json_encode(['success' => true]);
    exit();
} elseif (isset($_COOKIE['language'])) {
    $language = $_COOKIE['language'];
} else {
    $language = 'en'; // Gjuha e default
}

$lang_file = "lang_" . $language . ".php";
if (file_exists($lang_file)) {
    include $lang_file;
} else {
    include "lang_en.php"; // Përdor gjuhën e default nëse skedari i përkthimit nuk ekziston
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($language, ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(isset($lang_strings['language_selector']) ? $lang_strings['language_selector'] : 'Select Language', ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative; /* Shto për pozicionimin e absolute të container-it */
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: absolute; /* Pozicionim absolute për të vendosur në cepin e djathtë */
            top: 1rem; /* Largësia nga maja e faqes */
            right: 1rem; /* Largësia nga cepi i djathtë i faqes */
        }

        h1 {
            color: #333;
            margin-bottom: 1rem;
        }

        select {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
            width: 100%;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .login-link {
            margin-top: 1rem;
            display: inline-block;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars(isset($lang_strings['language_selector']) ? $lang_strings['language_selector'] : 'Select Language', ENT_QUOTES, 'UTF-8'); ?></h1>
        <form id="languageForm" method="post" action="">
            <label for="language"><?php echo htmlspecialchars(isset($lang_strings['language']) ? $lang_strings['language'] : 'Language', ENT_QUOTES, 'UTF-8'); ?>:</label>
            <select name="language" id="language" onchange="submitLanguage()">
                <option value="en" <?php if ($language == 'en') echo 'selected'; ?>>English</option>
                <option value="sq" <?php if ($language == 'sq') echo 'selected'; ?>>Shqip</option>
            </select>
        </form>
        <a href="login.php" class="login-link"><?php echo htmlspecialchars(isset($lang_strings['title']) ? $lang_strings['title'] : 'Login', ENT_QUOTES, 'UTF-8'); ?></a>
    </div>

    <script>
        function submitLanguage() {
            var form = document.getElementById('languageForm');
            var formData = new FormData(form);

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Opsionalisht, mund të bëni diçka kur ruajtja e gjuhës është e suksesshme
                      console.log('Language saved successfully');
                  }
              }).catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
