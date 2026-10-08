<?php

session_start();

include "database/connection.php";


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {

    $_SESSION['login_error'] = "Please enter both email and password.";

    header("Location: login.php");
    exit();
}

$email = mysqli_real_escape_string($conn, $email);

$query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";

$result = mysqli_query($conn, $query);


if (!$result) {

    $_SESSION['login_error'] = "Something went wrong. Please try again.";

    header("Location: login.php");
    exit();
}

if (mysqli_num_rows($result) == 0) {

    $_SESSION['login_error'] = "Invalid email or password.";

    header("Location: login.php");
    exit();
}


$user = mysqli_fetch_assoc($result);

$login_success = false;

if (password_verify($password, $user['password'])) {

    $login_success = true;

}

elseif ($password === $user['password']) {

    $login_success = true;

    $new_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    $update_query = "
        UPDATE users
        SET password = '$new_password'
        WHERE id = " . intval($user['id']);

    mysqli_query($conn, $update_query);
}

if (!$login_success) {

    $_SESSION['login_error'] = "Invalid email or password.";

    header("Location: login.php");
    exit();
}

$_SESSION['id'] = $user['id'];

$_SESSION['user_id'] = $user['id'];

$_SESSION['name'] = $user['full_name'];

$_SESSION['role'] = $user['role'];

if ($user['role'] == 'admin') {

    header("Location: admin/dashboard.php");
    exit();

}

elseif ($user['role'] == 'alumni') {

    header("Location: alumni/dashboard.php");
    exit();

}

elseif ($user['role'] == 'student') {

    header("Location: student/dashboard.php");
    exit();

}

else {

    $_SESSION['login_error'] = "Invalid user role.";

    header("Location: login.php");
    exit();
}

?>