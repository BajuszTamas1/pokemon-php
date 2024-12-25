<?php
session_start();

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

$usersData = file_get_contents('users.json');

$users = json_decode($usersData, true);

$admin = null;
foreach ($users as $user) {
    if ($user['username'] === "admin") {
        $admin = $user;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_username'])) {
    $delete_username = $_POST['delete_username'];

    $users = json_decode(file_get_contents('users.json'), true);

    $index = array_search($delete_username, array_column($users, 'username'));

    if ($index !== false) {
        unset($users[$index]);

        $users = array_values($users);

        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
    }
}


?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Admin panel</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/admin.css">
</head>

<body>
    <header>
        <div>
            <h1><a href="index.php">IKémon</a> > Admin panel</h1>
            <nav id="menu">
                <ul>
                    <li><a href="index.php">
                            <p>Home</p>
                        </a></li>
                    <li><a href="cards.php">
                            <p>Cards</p>
                        </a></li>
                    <?php if (isset($admin['username']) && $admin['username'] == 'admin'): ?>
                        <li><a href="admin.php">
                                <p>Admin panel</p>
                            </a></li>
                    <?php else: ?>
                    <?php endif; ?>
                    <?php if (isset($admin['username'])): ?>
                        <li>
                            <a href="logout.php">
                                <p>Logout</p>
                            </a>
                        </li>
                        <li class="name">
                            <p>
                                <a href="user.php">
                                    <?php echo $admin['username']; ?>
                                </a>
                            </p>
                        </li>
                        <li class="bal">
                            <p>💰
                                <?php echo round($admin['money'],2); ?>
                            </p>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div id="content">
        <div id="add">
            <form id="add-card-form" method="POST" action="addcardValidate.php" nofiltervalidate>
                <h2>Add Card</h2>
                <label for="name">Name: </label>
                <input type="text" id="name" name="name" placeholder="Pokemon Name"><br><br>
                <label for="type">Type: </label>
                <input type="text" id="type" name="type" placeholder="Pokemon Type"><br><br>
                <label for="hp">HP: </label>
                <input type="number" id="hp" name="hp" placeholder="Pokemon HP"><br><br>
                <label for="atk">Attack: </label>
                <input type="number" id="atk" name="atk" placeholder="Pokemon Attack"><br><br>
                <label for="def">Defense: </label>
                <input type="number" id="def" name="def" placeholder="Pokemon Defense"><br><br>
                <label for="price">Price: </label>
                <input type="number" id="price" name="price" placeholder="Pokemon Price"><br><br>
                <label for="description">Description: </label>
                <textarea name="description" id="description" placeholder="Card Description"></textarea><br><br>
                <label for="img">Image: </label>
                <input type="url" id="img" name="img" placeholder="Pokemon Image"><br><br>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p>
                                <?php echo $error; ?>
                            </p>
                        <?php endforeach; ?>
                        <?php
                        unset($_SESSION['errors']);
                        ?>
                    </div>
                <?php endif; ?>
                <div id="buttonbox">
                    <input id="addCard" type="submit" value="Submit">
                </div>
            </form>
        </div>
        <div id="list">
            <h2>Registered accounts</h2>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Permission</th>
                    <th>Money</th>
                    <th>Registered</th>
                    <th></th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php echo $user['username']; ?>
                        </td>
                        <td>
                            <?php echo $user['email']; ?>
                        </td>
                        <td>
                            <?php echo $user['permission']; ?>
                        </td>
                        <td>
                            <?php echo $user['money']; ?>
                        </td>
                        <td>
                            <?php echo $user['registered']; ?>
                        </td>
                        <td>
                            <?php if ($user['username'] !== 'admin'): ?>
                                <form method="post" action="admin.php">
                                    <input type="hidden" name="delete_username" value="<?php echo $user['username']; ?>">
                                    <input type="submit" value="❌">
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>