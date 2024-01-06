<?php

require_once 'dbmanager.php';

if (isset($_POST['submit'])) {

    $dbConn = new DbManager();

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];

    $errors = array();

    if (empty($firstName)) {
        $errors[] = "First Name is required";
    }
    if (empty($lastName)) {
        $errors[] = "Last Name is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if (empty($confirmPassword)) {
        $errors[] = "Password confirmation is required'";
    }

    if (isset($_POST["email"]) && !(empty($email))) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } else {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        }
    }

    if ($password != $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    if (!empty($errors)) {
        echo "<div style='color: red; margin-top: 45px;'>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        echo "</div>";
    } else {
        $query = "INSERT INTO users (name, password, email) VALUES ('$firstName:$lastName', '$password', '$email')";
        $dbConn->executeQuery($query);
        $dbConn->closeConnection();

        header("Location: login.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body style="background-image: url('media/backgroundImage.jpg');">
    <main class="main">
        <h1>Registration</h1>
        <form method="post">
            <div class="name-container">
                <input type="text" id="firstName" name="firstName" placeholder="First Name">
                <input type="text" id="lastName" name="lastName" placeholder="Last Name">
            </div>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password">
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
            </div>
            <div class="email-container">
                <input type="email" id="email" name="email" placeholder="Email">
            </div>
            <div class="submit-container">
                <input type="submit" id="submit" name="submit" value="Register">
            </div>
        </form>
        <a href="login.php">Have an account already? Click here to log in!</a>
    </main>
</body>

</html>