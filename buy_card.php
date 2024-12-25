<?php
session_start();

$errors = [];

$cardName = $_GET['card'];
$username = $_SESSION['username'];

$users = json_decode(file_get_contents('users.json'), true);


$admin = null;
$currentUser = null;
foreach ($users as &$user) {
    if ($user['username'] === 'admin') {
        $admin = &$user;
    } else if ($user['username'] === $username) {
        $currentUser = &$user;
    }
}

if ($admin === null || $currentUser === null) {
    $errors[] = 'User or Admin not found';
    exit;
}

$card = null;
$cardKey = null;

foreach ($admin['cards'] as $key => $adminCard) {
    if ($adminCard['name'] === $cardName) {
        $card = $adminCard;
        $cardKey = $key;
        unset($admin['cards'][$key]);
        break;
    }
}

if ($card === null) {
    $errors[] = 'Card not found';
} elseif ($currentUser['money'] < $card['price']) {
    $errors[] = 'Not enough money';
} elseif (count($currentUser['cards']) == 5) {
    $errors[] = 'You have too many cards';
} else {
    $currentUser['money'] -= $card['price'];
    $currentUser['cards'][$cardKey] = $card;
    file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
}

$_SESSION['errors'] = $errors;
sleep(1);
header('Location: index.php');
exit();
