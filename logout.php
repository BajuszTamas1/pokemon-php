<?php
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon | Logging out...</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <header>
        <div>
            <h1>IKémon > Logging out...</h1>
        </div>
    </header>
    <div id="content">
        <div>
            <h2>Logging out...</h2>
            <p>You will be redirected to the main page in 5 seconds.</p>
        </div>
    </div>
</body>
</html>

<?php
session_destroy();
header('Refresh: 5; URL=index.php');
exit;
?>