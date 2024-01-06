<?php

require_once 'dbmanager.php';

session_start();

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['rememberMe']) ? $_POST['rememberMe'] : false;

    $errors = [];

    if (empty($firstName)) {
        $errors[] = "First Name is required";
    }
    if (empty($lastName)) {
        $errors[] = "Last Name is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE name = '$firstName:$lastName' AND password = '$password'";

        $dbConn = new DbManager();
        $result = $dbConn->executeQuery($query);
        $dbConn->closeConnection();

        if ($result->num_rows > 0) {
            $fetchedUser = $firstName . " " . $lastName;

            $_SESSION['user'] = ['name' => $fetchedUser];

            if ($rememberMe) {
                setcookie("firstName", $firstName, time() + 3600);
                setcookie("lastName", $lastName, time() + 3600);
                setcookie("password", $password, time() + 3600);
            }

            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid login credentials";
        }
    }

    if (!empty($errors)) {
        echo "<div style='color: red; margin-top: 45px;'>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Authenticate</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body style="background-image: url('media/backgroundImage.jpg');">
    <main class="main">
        <h1>Log In</h1>
        <form method="post">
            <div class="name-container">
                <input type="text" id="firstName" name="firstName" placeholder="First Name">
            </div>
            <div class="name-container">
                <input type="text" id="lastName" name="lastName" placeholder="Last Name">
            </div>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password">
            </div>
            <div class="submit-container">
                <input type="submit" id="submit" name="submit" value="Log In">
                <input type="checkbox" id="rememberMe" name="rememberMe" value="rememberMe">Remember me
            </div>
        </form>
        <a href="register.php">Don't have an account? Click here to register!</a>
    </main>
</body>

</html>