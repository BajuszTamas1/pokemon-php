<?php
session_start();

$usersData = file_get_contents('users.json');
$users = json_decode($usersData, true);

$current_user = null;
foreach ($users as $user) {
    if ($user['username'] === $_SESSION['username']) {
        $current_user = $user;
        break;
    }
}

$admin = null;
foreach ($users as $user) {
    if ($user['username'] === "admin") {
        $admin = $user;
        break;
    }
}

?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | User panel</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/user.css">
    <script>
        function sellCard(cardName) {
            fetch('sell_card.php?card=' + encodeURIComponent(cardName)).then(response => response.text()).then(data => {
                console.log(data);
                window.location.reload();
            })
        }
    </script>
</head>

<body>
    <header>
        <div>
            <h1><a href="index.php">IKémon</a> > User panel</h1>
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
    <div id="content">
        <div id="userData">
            <div class="users">
                <h2>Your data</h2>
                <table>
                    <tr>
                        <th>Username</th>
                        <th>E-mail</th>
                        <th>Money</th>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $current_user['username']; ?>
                        </td>
                        <td>
                            <?php echo $current_user['email']; ?>
                        </td>
                        <td>
                            <?php echo $current_user['money']; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="card-list">
                <?php foreach ($current_user['cards'] as $card): ?>
                    <div class="pokemon-card">
                        <div class="image clr-<?php echo $card['type']; ?>">
                            <img src="<?php echo $card['image']; ?>" alt="">
                        </div>
                        <div class="details">
                            <?php
                            echo "<h2><a href=\"details.php?name=" . urlencode($card['name']) . "\">" . $card['name'] . "</a><br></h2>";
                            ?>
                            <span class="card-type"><span class="icon">🏷</span>
                                <?php echo $card['type']; ?>
                            </span>
                            <span class="attributes">
                                <span class="card-hp"><span class="icon">❤</span>
                                    <?php echo $card['hp']; ?>
                                </span>
                                <span class="card-attack"><span class="icon">⚔</span>
                                    <?php echo $card['attack']; ?>
                                </span>
                                <span class="card-defense"><span class="icon">🛡</span>
                                    <?php echo $card['defense']; ?>
                                </span>
                            </span>
                        </div>
                        <?php if ($current_user['username'] != 'admin'): ?>
                            <div class="buy" onclick="sellCard('<?= $card['name'] ?>')">
                                <span>Sell for</span>
                                <span class="card-price"><span class="icon">💰</span>
                                    <?php echo $card['price'] * 0.9; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>

</html>