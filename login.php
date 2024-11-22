
<?php
session_start();

// Kontrollo nëse është bërë zgjedhja e gjuhës dhe përfshi skedarin përkatës
if (isset($_COOKIE['language'])) {
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

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $mysqli = new mysqli("localhost", "root", "", "travel_db");

        if ($mysqli->connect_error) {
            die("Lidhja me bazën e të dhënave dështoi: " . $mysqli->connect_error);
        }

        if ($_POST['form_type'] == 'visitor_login') {
            if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['password'])) {
                // Login për vizitorët
                $first_name = trim($_POST['first_name']);
                $last_name = trim($_POST['last_name']);
                $password = trim($_POST['password']);

                // Kërko përdoruesin në tabelën visitors
                $sql = "SELECT id, first_name, last_name, password FROM visitors WHERE first_name = ? AND last_name = ?";
                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    die("Gabim në përgatitjen e deklaratës SQL: " . $mysqli->error);
                }

                $stmt->bind_param("ss", $first_name, $last_name);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['visitor_id'] = $row['id'];
                        $_SESSION['first_name'] = $row['first_name'];
                        $_SESSION['last_name'] = $row['last_name'];

                        // Redirekto në faqen e vizitorit pas logimit të suksesshëm
                        header("Location: visitor_dashboard.php");
                        exit();
                    } else {
                        $error_message = $lang_strings['incorrect_credentials'];
                    }
                } else {
                    $error_message = $lang_strings['incorrect_credentials'];
                }

                $stmt->close();
            } else {
                $error_message = $lang_strings['missing_fields'];
            }
        } else if ($_POST['form_type'] == 'manager_admin_login') {
            if (isset($_POST['username']) && isset($_POST['user_password'])) {
                // Login për menaxherët dhe adminët
                $username = trim($_POST['username']);
                $user_password = trim($_POST['user_password']);

                // Kërko përdoruesin në tabelën users
                $sql = "SELECT user_id, username, user_password, user_role, user_city FROM users WHERE username = ?";
                $stmt = $mysqli->prepare($sql);
                if ($stmt === false) {
                    die("Gabim në përgatitjen e deklaratës SQL: " . $mysqli->error);
                }

                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();
                    if (password_verify($user_password, $row['user_password'])) {
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_role'] = $row['user_role'];
                        $_SESSION['user_city'] = $row['user_city'];

                        // Redirekto bazuar në rolin e përdoruesit
                        if ($row['user_role'] == 'admin') {
                            header("Location: admin_dashboard.php");
                        } else if ($row['user_role'] == 'manager') {
                            header("Location: manager_dashboard.php");
                        } else {
                            header("Location: user_dashboard.php");
                        }
                        exit();
                    } else {
                        $error_message = $lang_strings['incorrect_credentials'];
                    }
                } else {
                    $error_message = $lang_strings['incorrect_credentials'];
                }

                $stmt->close();
            } else {
                $error_message = $lang_strings['missing_fields'];
            }
        }

        $mysqli->close();
    } else {
        $error_message = $lang_strings['missing_fields'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lang_strings['title'], ENT_QUOTES, 'UTF-8'); ?></title>
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
        }
        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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
        .error {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($lang_strings['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="form_type" value="visitor_login">
            <h2>Visitor Login</h2>
            <label for="first_name"><?php echo htmlspecialchars($lang_strings['first_name'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name"><?php echo htmlspecialchars($lang_strings['last_name'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="password"><?php echo htmlspecialchars($lang_strings['password'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit"><?php echo htmlspecialchars($lang_strings['login'], ENT_QUOTES, 'UTF-8'); ?></button>
        </form>
        
        <form method="post" action="" style="margin-top: 2rem;">
            <input type="hidden" name="form_type" value="manager_admin_login">
            <h2>Manager/Admin Login</h2>
            <label for="username"><?php echo htmlspecialchars($lang_strings['username'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="text" id="username" name="username" required>
            
            <label for="user_password"><?php echo htmlspecialchars($lang_strings['password'], ENT_QUOTES, 'UTF-8'); ?></label>
            <input type="password" id="user_password" name="user_password" required>
            
            <button type="submit"><?php echo htmlspecialchars($lang_strings['login'], ENT_QUOTES, 'UTF-8'); ?></button>
        </form>
    </div>
</body>
</html>
