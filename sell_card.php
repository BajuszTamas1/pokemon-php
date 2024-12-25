<?php
session_start();

$errors = [];

$cardName = $_GET['card'];
$username = $_SESSION['username'];

$users = json_decode(file_get_contents('users.json'), true);

$admin = null;
$currentUser = null;

foreach($users as &$user){
    if($user['username'] === 'admin'){
        $admin = &$user;
    }else if($user['username'] === $username){
        $currentUser = &$user;
    }
}

if($admin === null || $currentUser === null){
    $errors[] = 'User or Admin not found';
    exit;
}

$card = null;

foreach($currentUser['cards'] as $key => $userCard){
    if($userCard['name'] === $cardName){
        $card = $userCard;
        unset($currentUser['cards'][$key]);
        break;
    }
}

if($card === null){
    $errors[] = 'Card not found';
    exit;
}




$currentUser['money'] += $card['price']*0.9;
$admin['cards'][] = $card;

file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
sleep(1);
header('Location: user.php');
exit();
