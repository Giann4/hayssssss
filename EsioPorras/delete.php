<?php

include 'db.php';

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM `user` WHERE USERID = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: showdatas.php");
        exit();

    } else {

        echo "Error: " . $stmt->error;

    }

    $stmt->close();

} else {

    header("Location: showdatas.php");
    exit();

}

$conn->close();

?>