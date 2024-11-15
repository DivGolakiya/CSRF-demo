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

// // Countermeasure to eliminate CSRF attack. P.S.: Remove below commented code from line number 14 to 16 to check how this works.
// if (!isset($_SESSION['logged_in']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     die("Unauthorized access or invalid CSRF token.");
// }

$db = connectDb();

$username = $_SESSION['username'];
$amount = $_POST['amount'];
$recipient = $_POST['recipient'];

// Update sender's balance
$sql = "UPDATE users SET balance = balance - ? WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$amount, $username]);

// Update recipient's balance
$sql = "UPDATE users SET balance = balance + ? WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$amount, $recipient]);

$balance = $db->prepare('SELECT balance FROM users WHERE username = ?');
$balance->execute([$username]);
$result = $balance->fetch(PDO::FETCH_ASSOC);

echo "Transfer successful!";
if ($result) {
    $newBalance = $result['balance'];
    echo "<br> Username: $username.<br>Your new balance is: $$newBalance<br>";
} else {
    echo "Error fetching balance.";
}

echo '<br><a href="logout.php">Logout</a>';