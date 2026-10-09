<?php

session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username == "" || $password == "") {

        $message = "Please enter username and password.";

    } else {

        // Find the user by username
        $stmt = $conn->prepare(
            "SELECT USERID, G_NAME, USERNAME, PASSWORD, TYPE, STATUS
             FROM `user`
             WHERE USERNAME = ?
             LIMIT 1"
        );

        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $row = $result->fetch_assoc();

            // Check hashed password
            if (password_verify($password, $row["PASSWORD"])) {

                // Check user status
                if ($row["STATUS"] != "Active") {

                    $message = "Your account is inactive.";

                } else {

                    // Login successful
                    $_SESSION["user_id"] = $row["USERID"];
                    $_SESSION["username"] = $row["USERNAME"];
                    $_SESSION["gname"] = $row["G_NAME"];
                    $_SESSION["type"] = $row["TYPE"];
                    $_SESSION["status"] = $row["STATUS"];

                    header("Location: showdatas.php");
                    exit();
                }

            } else {

                $message = "Invalid password.";

            }

        } else {

            $message = "Username not found.";

        }

        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>

<body>

    <h2>Login</h2>

    <?php if ($message != ""): ?>

        <p style="color:red;">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <form method="POST" action="">

        <label>Username:</label>
        <input
            type="text"
            name="username"
            required
        >

        <br><br>

        <label>Password:</label>
        <input
            type="password"
            name="password"
            required
        >

        <br><br>

        <input type="submit" value="Login">

        &nbsp;

        <a href="adduser.php">Add New User</a>

    </form>

</body>
</html>