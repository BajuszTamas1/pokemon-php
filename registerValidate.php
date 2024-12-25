<?php
session_start();
$errors = [];
$input = $_GET;
$data = [];
$money = 2000;
$permission = "user";
$registered = date("Y-m-d");
$cards = [];


$is_valid = validate($input, $errors, $data);

function validate($input, &$errors, &$data)
{
    $users = [];
    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents('users.json'), true);
    }  

    if (existingUser($_POST['username'], $users)) {
        $errors[] = 'Username already exists';
    }

    if (existingEmail($_POST['email'], $users)) {
        $errors[] = 'Email already exists';
    }

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $data['email'] = $_POST['email'];
    } else {
        $errors[] = 'Email is not valid';
    }

    if (isset($_POST['username']) && strlen(trim($_POST['username'])) >= 3) {
        $data['username'] = $_POST['username'];
    } else {
        $errors[] = 'Username must be at least 3 characters long';
    }

    if (isset($_POST['password']) && strlen(trim($_POST['password'])) >= 6) {
        $data['password'] = $_POST['password'];
    } else {
        $errors[] = 'Password must be at least 6 characters long';
    }

    if (isset($_POST['password2']) && $_POST['password'] === $_POST['password2']) {
        $data['password2'] = $_POST['password2'];
    } else {
        $errors[] = 'Passwords do not match';
    }

    return empty($errors);
}

function existingUser($username, $users)
{
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return true;
        }
    }
    return false;
}
function existingEmail($email, $users)
{
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}

if ($is_valid) {
    $users = [];

    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents('users.json'), true);
    }

    $user = [
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => $data['password'],
        'permission' => $permission,
        'money' => $money,
        'registered' => $registered,
        'cards' => $cards
    ];
    $users[] = $user;
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
    $_SESSION['username'] = $user['username'];
    header('Location: index.php');
    exit();
}

$_SESSION['errors'] = $errors;
header('Location: register.php');
exit();
?>
