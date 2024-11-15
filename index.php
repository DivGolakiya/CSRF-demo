<?php
session_start();

function connectDb() {
    try {
        $db = new PDO('sqlite:users.db');
        return $db;
    } catch (PDOException $e) {
        die("Error connecting to database: " . $e->getMessage());
    }
}

function createUsersTable() {
    $db = connectDb();
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        balance INTEGER DEFAULT 1000
    )";
    $db->exec($sql);

    $sql = "INSERT OR IGNORE INTO users (username, password, balance) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 1000]);
    $stmt->execute(['user1', password_hash('user123', PASSWORD_DEFAULT), 1000]);
}

createUsersTable();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $username = $_SESSION['username'];
    $balance = getBalance($username);

    echo "Welcome, $username! Your balance: $$balance<br>";

    // Generate a CSRF token and store it in the session
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    ?>
    <form action="transfer.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        Amount: <input type="number" name="amount"><br>
        Recipient: <input type="text" name="recipient"><br>
        <input type="submit" value="Transfer">
    </form>

    <a href="logout.php">Logout</a>

    <?php
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = connectDb();
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid credentials.";
        }
    }
    ?>
    <form method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
}

function getBalance($username) {
    $db = connectDb();
    $sql = "SELECT balance FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result['balance'];
    } else {
        return 0;
    }
}