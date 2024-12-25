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
function getPokemonDetails($pokemonName) {
    $json = file_get_contents('cards.json');

    $data = json_decode($json, true);

    foreach ($data as $pokemon) {
        if ($pokemon['name'] === $pokemonName) {
            return $pokemon;
        }
    }
    return null;
}
$pokemonName = $_GET['name'];
$pokemon = getPokemonDetails($pokemonName);

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | <?php echo $pokemon['name']; ?></title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <link rel="stylesheet" href="styles/details.css">
</head>

<body class="<?php echo $pokemon['type']; ?>">
<header>
        <div>
            <h1><a href="index.php">IKémon</a> > <?php echo $pokemon['name']; ?> </h1>
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
    <div id="content" class="pos">
        <div id="details">
            <div class="image clr-<?php echo $pokemon['type']; ?>">
                <img src="<?php echo $pokemon['image']; ?>" alt="">
            </div>
            <div class="info">
                <div class="description">
                    <?php echo $pokemon['description']; ?></p>
                </div>
                <span class="card-type"><span class="icon">🏷</span> Type: <?php echo $pokemon['type']; ?></span>
                <div class="attributes">
                    <div class="card-hp"><span class="icon">❤</span> Health: <?php echo $pokemon['hp']; ?></div>
                    <div class="card-attack"><span class="icon">⚔</span> Attack: <?php echo $pokemon['attack']; ?></div>
                    <div class="card-defense"><span class="icon">🛡</span> Defense: <?php echo $pokemon['defense']; ?></div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>IKémon | ELTE IK Webprogramozás</p>
    </footer>
</body>
</html>