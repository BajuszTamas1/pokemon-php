<?php
session_start();
$users = json_decode(file_get_contents('users.json'), true);
$cards = json_decode(file_get_contents('cards.json'), true);

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if ($filter === 'all') {
        $filter = '';
    } else {
        $cards = array_filter($cards, function ($card) use ($filter) {
            return $card['type'] === $filter;

        });
    }
}

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
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Home</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/cards.css">
    <script>
        function buyCard(cardName) {
            fetch('buy_card.php?card=' + encodeURIComponent(cardName)).then(response => response.text()).then(data => {
                console.log(data);
                window.location.reload();
            })
        }
    </script>
</head>

<body>
    <header>
        <div>
            <h1><a href="index.php">IKémon</a> > Home</h1>
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
                                <?php echo round($current_user['money'], 2); ?>
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
        <h2 id="available">Available cards</h2>
        <form id="sort" method="get" action="">
            <select name="filter" onchange="this.form.submit()">
                <option value="">Filter by type...</option>
                <option value="all">all</option>
                <option value="normal">normal</option>
                <option value="fire">fire</option>
                <option value="water">water</option>
                <option value="electric">electric</option>
                <option value="grass">grass</option>
                <option value="ice">ice</option>
                <option value="fighting">fighting</option>
                <option value="poison">poison</option>
                <option value="ground">ground</option>
                <option value="psychic">psychic</option>
                <option value="bug">bug</option>
                <option value="rock">rock</option>
                <option value="ghost">ghost</option>
                <option value="dark">dark</option>
                <option value="steel">steel</option>
            </select>
        </form>
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
        <div id="card-list">
            <?php foreach ($cards as $card): ?>
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
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] != 'admin' && in_array($card, $admin['cards'])): ?>
                        <div class="buy" onclick="buyCard('<?= $card['name'] ?>')">
                            <span class="card-price"><span class="icon">💰</span>
                                <?php echo $card['price']; ?>
                            </span>
                        </div>
                    <?php elseif (isset($_SESSION['username']) && $_SESSION['username'] != 'admin'): ?>
                        <div class="buy; nfs">
                            <span>Card not for sale</span>
                        </div>
                    <?php elseif (isset($_SESSION['username']) && $_SESSION['username'] === 'admin'): ?>
                        <div class="buy; nfs">
                            <span>Locked for admins</span>
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