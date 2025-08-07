<?php

$conn = new mysqli("localhost", "root", "", "user_project");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $sql = "INSERT INTO users (name, age, status) VALUES ('$name', '$age', 0)";
    $conn->query($sql);
}


if (isset($_GET["toggle"])) {
    $id = $_GET["toggle"];
    $result = $conn->query("SELECT status FROM users WHERE id = $id");
    $row = $result->fetch_assoc();
    $newStatus = $row["status"] == 1 ? 0 : 1;
    $conn->query("UPDATE users SET status = $newStatus WHERE id = $id");
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Table</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        input[type="text"], input[type="number"] { padding: 5px; }
        button { padding: 5px 10px; }
    </style>
</head>
<body>

    <form method="POST">
        Name: <input type="text" name="name" required>
        Age: <input type="number" name="age" required>
        <button type="submit" name="submit">Submit</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Age</th><th>Status</th><th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= $row["age"] ?></td>
            <td><?= $row["status"] ?></td>
            <td><a href="?toggle=<?= $row["id"] ?>"><button>Toggle</button></a></td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
