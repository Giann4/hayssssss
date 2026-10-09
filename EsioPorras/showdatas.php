<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$gname = $_SESSION['gname'] ?? '';
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>

<html>

<head>

    <title>View Users</title>

</head>

<body>

    <h2>Welcome, <?php echo htmlspecialchars($gname); ?>!</h2>

    <h2>User List</h2>

    <a href="adduser.php">Add New User</a>

    <br><br>

    <table border="1" cellpadding="10">

        <tr>

            <th>User ID</th>
            <th>Given Name</th>
            <th>Username</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>

        </tr>

        <?php

        $sql = "SELECT USERID, G_NAME, USERNAME, TYPE, STATUS FROM `user`";

        $result = $conn->query($sql);


        if ($result && $result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                echo "<tr>";

                echo "<td>" . $row['USERID'] . "</td>";

                echo "<td>" . $row['G_NAME'] . "</td>";

                echo "<td>" . $row['USERNAME'] . "</td>";

                echo "<td>" . $row['TYPE'] . "</td>";

                echo "<td>" . $row['STATUS'] . "</td>";

                echo "<td>

                    <a href='edit.php?id=" . $row['USERID'] . "'>
                        Edit
                    </a>

                    |

                    <a href='delete.php?id=" . $row['USERID'] . "'
                    onclick=\"return confirm('Delete this user?');\">
                        Delete
                    </a>

                </td>";

                echo "</tr>";

            }

        } else {

            echo "<tr>";

            echo "<td colspan='6'>
                    No users found.
                  </td>";

            echo "</tr>";

        }

        ?>

    </table>

    <br>

    <a href="logout.php">Logout</a>

</body>

</html>