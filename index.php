<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "myuser";
$password = "mypassword";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Status message
$status = $_GET['status'] ?? null;

// Handle Add User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country = $_POST['country'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, country) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $country);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF'] . "?status=added");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My EC2 Project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #1e1e2f;
            color: #f0f0f0;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #66ccff;
        }

        .flex-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            margin: 20px auto;
            max-width: 1200px;
        }

        .form-box, .message-box {
            background-color: #2e2e40;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }

        .form-box {
            flex: 1 1 300px;
            max-width: 500px;
        }

        .message-box {
            flex: 1 1 200px;
            max-width: 400px;
            color: #bafac7;
            background-color: #2e5731;
            border-left: 5px solid #00cc66;
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }

        .message-box.hidden {
            opacity: 0;
        }

        .form-box h2 {
            color: #66ccff;
            margin-bottom: 20px;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }

        input[type="text"], input[type="email"] {
            width: 96%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #555;
            border-radius: 4px;
            background-color: #1a1a2a;
            color: #fff;
        }

        input[type="submit"] {
            background-color: #66ccff;
            color: #1e1e2f;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4db8e6;
        }

        .table-box {
            background-color: #2e2e40;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 100%;
            max-width: 1200px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            overflow-x: auto;
        }

        .table-box h2 {
            color: #66ccff;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
            word-break: break-word;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #444;
        }

        @media (max-width: 768px) {
            .flex-container {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <h1>Welcome to My EC2 Web Server!</h1>
<p style="text-align:center;">Simple PHP + MySQL App</p>
    <!-- Add Form and Message -->
    <div class="flex-container">
        <div class="form-box">
            <h2>Add User</h2>
            <form method="POST" action="">
                <input type="text" name="name" required placeholder="Name">
                <input type="email" name="email" required placeholder="Email">
                <input type="text" name="country" required placeholder="Country">
                <input type="submit" name="submit" value="Add User">
            </form>
        </div>

        <?php if ($status == 'added'): ?>
        <div class="message-box" id="status-message">
            <h3>Status</h3>
            <p>✅ User added successfully.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- User List -->
    <div class="table-box">
        <h2>User List</h2>
        <?php
        $sql = "SELECT name, email, country FROM users ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Name</th><th>Email</th><th>Country</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["country"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Auto-hide success message -->
    <script>
        setTimeout(() => {
            const msg = document.getElementById('status-message');
            if (msg) {
                msg.classList.add('hidden');
                setTimeout(() => msg.style.display = 'none', 1000); // Hide after fade
            }
        }, 4000);
    </script>
</body>
</html>
