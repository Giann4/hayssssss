<?php

include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: showdatas.php");
    exit();
}

$id = $_GET['id'];

// GET USER DATA
$sql = "SELECT * FROM `user` WHERE USERID = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "User not found.";
    exit();
}

$row = $result->fetch_assoc();


// UPDATE USER
if (isset($_POST['update'])) {

    $gname = $_POST['gname'];
    $username = $_POST['username'];
    $type = $_POST['type'];
    $status = $_POST['status'];

    $sql = "UPDATE `user`
            SET G_NAME = ?,
                USERNAME = ?,
                TYPE = ?,
                STATUS = ?
            WHERE USERID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $gname,
        $username,
        $type,
        $status,
        $id
    );

    if ($stmt->execute()) {

        header("Location: showdatas.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>

<body>

    <h2>Edit User</h2>

    <form method="POST">

        <label>Given Name:</label><br>

        <input
            type="text"
            name="gname"
            value="<?php echo htmlspecialchars($row['G_NAME']); ?>"
            required
        >

        <br><br>


        <label>Username:</label><br>

        <input
            type="text"
            name="username"
            value="<?php echo htmlspecialchars($row['USERNAME']); ?>"
            required
        >

        <br><br>


        <label>Type:</label><br>

        <select name="type">

            <option value="User"
                <?php
                if ($row['TYPE'] == 'User') {
                    echo 'selected';
                }
                ?>
            >
                User
            </option>

            <option value="Admin"
                <?php
                if ($row['TYPE'] == 'Admin') {
                    echo 'selected';
                }
                ?>
            >
                Admin
            </option>

        </select>

        <br><br>


        <label>Status:</label><br>

        <select name="status">

            <option value="Active"
                <?php
                if ($row['STATUS'] == 'Active') {
                    echo 'selected';
                }
                ?>
            >
                Active
            </option>

            <option value="Inactive"
                <?php
                if ($row['STATUS'] == 'Inactive') {
                    echo 'selected';
                }
                ?>
            >
                Inactive
            </option>

        </select>

        <br><br>

        <input
            type="submit"
            name="update"
            value="Update User"
        >

    </form>

    <br>

    <a href="showdatas.php">
        Back
    </a>

</body>
</html>