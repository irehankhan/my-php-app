<?php
$servername = "192.168.100.4"; // Docker service name. when running from docker compose use your db service from docker-compose file.
$username = "rehan"; // MySQL your own username. username must be same used in mysql.sql file . When running from docker compose use root user.
$password = "your_password"; // MySQL password
$dbname = "mydatabase"; // MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new entry
    if (isset($_POST["add"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $contactNumber = $_POST["contactNumber"]; // Added contact number field

        $sqlAdd = "INSERT INTO users (name, email, contactNumber) VALUES ('$name', '$email', '$contactNumber')";
        if ($conn->query($sqlAdd) === TRUE) {
            echo "New record added successfully!";
        } else {
            echo "Error adding record: " . $conn->error;
        }
    }

    // Delete entry
    if (isset($_POST["delete"])) {
        $idToDelete = $_POST["idToDelete"];

        $sqlDelete = "DELETE FROM users WHERE id = $idToDelete";
        if ($conn->query($sqlDelete) === TRUE) {
            echo "Record deleted successfully!";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    // Delete all entries
    if (isset($_POST["deleteAll"])) {
        $sqlDeleteAll = "TRUNCATE TABLE users";
        if ($conn->query($sqlDeleteAll) === TRUE) {
            echo "All records deleted successfully!";
        } else {
            echo "Error deleting all records: " . $conn->error;
        }
    }
}

// Load and execute SQL file only if the users table doesn't exist
$tableCheckQuery = "SHOW TABLES LIKE 'users'";
$tableCheckResult = $conn->query($tableCheckQuery);

if ($tableCheckResult->num_rows == 0) {
    // Load and execute SQL file
    $sqlFile = 'mysql.sql';
    $sqlContent = file_get_contents($sqlFile);

    if ($conn->multi_query($sqlContent)) {
        do {
            // Consume all results
        } while ($conn->next_result());
    } else {
        echo "Error executing SQL file: " . $conn->error;
    }
}

// Fetch users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Display users in a styled HTML table
echo "<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        body {
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        h1 {
            color: green;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin: 10px auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #a9a9a9;
        }

        tr:hover {
            background-color: #d9d9d9;
        }

        form {
            margin: 20px auto;
            width: 50%;
            text-align: left;
        }

        input, button {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<br>
<h1>CONTACTS LIST</h1>";

if ($result) {
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Contact Number</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" . htmlspecialchars($row["name"]) . "</td><td>" . htmlspecialchars($row["email"]) . "</td><td>" . htmlspecialchars($row["contactNumber"]) . "</td><td><form method='post' action=''>
                    <input type='hidden' name='idToDelete' value='" . htmlspecialchars($row["id"]) . "'>
                    <button type='submit' name='delete'>Delete</button>
                </form></td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
} else {
    echo "Error fetching users: " . $conn->error;
}

// Add new entry form
echo "<form method='post' action=''>
        <label for='name'>Name:</label>
        <input type='text' name='name' required>
        <label for='email'>Email:</label>
        <input type='email' name='email'>
        <label for='contactNumber'>Contact Number:*</label>
        <input type='text' name='contactNumber' required>
        <button type='submit' name='add'>Add New Entry</button>
    </form>";

// Delete all entries form
echo "<form method='post' action=''>
        <button type='submit' name='deleteAll'>Delete All Entries</button>
    </form>";

echo "</body></html>";
$conn->close();
?>