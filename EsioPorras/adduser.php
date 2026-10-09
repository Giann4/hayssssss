<?php
include 'db.php';

$message = "";

if (isset($_POST['submit'])) {

    $gname = $_POST['gname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $status = $_POST['status'];

    // Hash the password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists
    $check = $conn->prepare(
        "SELECT USERID FROM `user` WHERE USERNAME = ?"
    );

    $check->bind_param("s", $username);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "<p style='color:red;'>
                    Username already exists!
                    </p>";

    } else {

        // Insert new user
        $stmt = $conn->prepare(
            "INSERT INTO `user`
            (G_NAME, USERNAME, PASSWORD, TYPE, STATUS)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $gname,
            $username,
            $hash,
            $type,
            $status
        );

        if ($stmt->execute()) {

            $message = "<p style='color:green;'>
                User added successfully!
                <a href='login.php'>Login here</a>
            </p>";

        } else {

            $message = "<p style='color:red;'>
                Error: " . $stmt->error . "
            </p>";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
</head>

<body>

    <h2>Add User</h2>

    <?php echo $message; ?>

    <form method="POST" action="">

        <label>Given Name:</label><br>
        <input type="text" name="gname" required>

        <br><br>

        <label>Username:</label><br>
        <input
            type="text"
            name="username"
            id="username"
            onkeyup="checkUsername()"
            required
        >

        <span id="user-status"></span>

        <br><br>

        <label>Password:</label><br>
        <input
            id="pw"
            name="password"
            type="password"
            required
            minlength="8"
            placeholder="At least 8 characters"
        >

        <br><br>

        <label>Type:</label><br>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
        </select>

        <br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>

        <br><br>

        <input type="submit" name="submit" value="Add User">

        <a href="login.php">Back</a>
        <a href="showdatas.php">viewusers</a>

    </form>

    <script>
    function checkUsername() {

        const username =
            document.getElementById("username").value;

        const status =
            document.getElementById("user-status");

        if (username.length === 0) {
            status.innerHTML = "";
            return;
        }

        const xhr = new XMLHttpRequest();

        xhr.open("POST", "check_username.php", true);

        xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
        );

        xhr.onreadystatechange = function() {

            if (xhr.readyState === 4 && xhr.status === 200) {
                status.innerHTML = xhr.responseText;
            }

        };

        xhr.send(
            "username=" +
            encodeURIComponent(username)
        );
    }
    </script>

</body>
</html>