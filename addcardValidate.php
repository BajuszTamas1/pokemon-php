<?php
session_start();
$errors = [];
$input = $_GET;
$data = [];

$is_valid = validate($input, $errors, $data);

function validate($input, &$errors, &$data)
{
    if (empty($_POST["name"])) {
        $errors[] = "Name is required";
    } 

    $cards = [];
    if (file_exists('cards.json')) {
        $cards = json_decode(file_get_contents('cards.json'), true);
    }

    if(existingCard($_POST['name'], $cards)){
        $errors[] = 'Card with this name already exists';
    }else {
        $data['name'] = $_POST['name'];
    }

    if (empty($_POST["type"])) {
        $errors[] = "Type is required";
    } else {
        $data['type'] = $_POST['type'];
    }

    if (empty($_POST["hp"]) || $_POST["hp"] <= 0) {
        $errors[] = "HP must be a positive number";
    } else {
        $data['hp'] = $_POST['hp'];
    }

    if (empty($_POST["atk"]) || $_POST["atk"] <= 0) {
        $errors[] = "Attack must be a positive number";
    } else {
        $data['attack'] = $_POST['atk'];
    }

    if (empty($_POST["def"]) || $_POST["def"] <= 0) {
        $errors[] = "Defense must be a positive number";
    } else {
        $data['defense'] = $_POST['def'];
    }

    if (empty($_POST["price"]) || $_POST["price"] <= 0) {
        $errors[] = "Price must be a positive number";
    } else {
        $data['price'] = $_POST['price'];
    }

    if (isset($_POST["description"]) && strlen(trim($_POST['description'])) > 10) {
        $data['description'] = $_POST['description'];
    } else {
        $errors[] = "Description must be at least 10 characters long";
    }

    if (isset($_POST["img"]) && filter_var($_POST['img'], FILTER_VALIDATE_URL)) {
        $data['image'] = $_POST['img'];
    } else {
        $errors[] = "Image URL is required";
    }

    return empty($errors);
}

function existingCard($name, $cards)
{
    foreach ($cards as $card) {
        if ($card['name'] === $name) {
            return true;
        }
    }
    return false;
}

if ($is_valid) {
    $cards = [];

    if (file_exists('cards.json')) {
        $cards = json_decode(file_get_contents('cards.json'), true);
    }

    $newCard = [
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'hp' => $_POST['hp'],
        'attack' => $_POST['atk'],
        'defense' => $_POST['def'],
        'price' => $_POST['price'],
        'description' => $_POST['description'],
        'image' => $_POST['img'],
    ];

     $highestKey = max(array_keys($cards));
     $key = $highestKey + 1;
     $cards[$key] = $newCard;

     file_put_contents('cards.json', json_encode($cards, JSON_PRETTY_PRINT));

     $users = json_decode(file_get_contents('users.json'), true);
 
     foreach ($users as &$user) {
         if ($user['username'] === 'admin') {
             $user['cards'][$key] = $newCard;
             break;
         }
     }
 
     file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

}

$_SESSION['errors'] = $errors;
header('Location: admin.php');
exit();
?>