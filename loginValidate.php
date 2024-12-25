<?php
session_start();
$errors = [];
$input = $_GET;
$data = [];

$users = [];
if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}

$is_valid = validate($input, $errors, $data);

function validate($input, &$errors, &$data)
{
    $users = [];
    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents('users.json'), true);
    }
    if (!matching($_POST['username'], $_POST['password'], $users)) {
        $errors[] = 'Invalid login credentials';
    }
    return empty($errors);
}

function matching($username, $password, $users)
{
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            return true;
        }
    }
    return false;
}

if ($is_valid) {
    foreach ($users as $user) {
        if ($user['username'] === $_POST['username']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['password'] = $user['password'];
            $_SESSION['money'] = $user['money'];
            $_SESSION['permission'] = $user['permission'];
            $_SESSION['registered'] = $user['registered'];
            $_SESSION['cards'] = $user['cards'];
            break;
        }
    }
    
    header('Location: index.php');
    exit();
}

$_SESSION['errors'] = $errors;
header('Location: login.php');
exit();
?>