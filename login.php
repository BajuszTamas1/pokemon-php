<?php
session_start();
$users = json_decode(file_get_contents('users.json'), true);


$admin = null;
foreach ($users as $user) {
    if ($user['username'] === "admin") {
        $admin = $user;
        break;
    }
}

$current_user = null;
foreach ($users as $user) {
    if (isset($_SESSION['username']) && $user['username'] === $_SESSION['username']) {
        $current_user = $user;
        break;
    }
}

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Login</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/login.css">
    <script>
        window.onload = function () {
            var usernameInput = document.getElementById('username');
            var loginButton = document.getElementById('login');

            usernameInput.value = sessionStorage.getItem('username') || '';

            usernameInput.oninput = function () {
                sessionStorage.setItem('username', usernameInput.value);
            }

            loginButton.onclick = function () {
                sessionStorage.removeItem('username', usernameInput.value);
            }
        }
    </script>
</head>
<header>
    <div>
        <h1><a href="index.php">IKémon</a> > Login</h1>
        <nav id="menu">
            <ul>
                <li><a href="index.php">
                        <p>Home</p>
                    </a></li>
                <?php if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin'): ?>
                    <li><a href="admin.php">
                            <p>Admin panel</p>
                        </a></li>
                <?php else: ?>
                <?php endif; ?>
                <?php if (isset($current_user['username'])): ?>
                    <li>
                        <a href="logout.php">
                            <p>Logout</p>
                        </a>
                    </li>
                    <li class="name">
                        <p>
                            <a href="user.php">
                                <?php echo $current_user['username']; ?>
                            </a>
                        </p>
                    </li>
                    <li class="bal">
                        <p>💰
                            <?php echo round($current_user['money'],2); ?>
                        </p>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<body>
    <div id="loginpanel">
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
        <h2>Login</h2>
        <form id="login-form" method="POST" action="loginValidate.php" nofiltervalidate>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value=""><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br><br>
            <div id="loginbtn">
                <input id="login" type="submit" value="Login">
                <button><a href="register.php">Register</a></button>
            </div>
        </form>
    </div>
</body>
<footer>
    <p>IKémon | ELTE IK Webprogramozás</p>
</footer>

</html>