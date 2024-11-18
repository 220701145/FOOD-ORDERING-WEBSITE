<?php
// Database connection settings
$servername = "localhost"; // Usually localhost
$db_username = "root"; // Default username for XAMPP or phpMyAdmin
$db_password = ""; // Default password for XAMPP or phpMyAdmin
$dbname = "ip"; // Your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape the input data to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Insert the username and password into the database
    $sql = "INSERT INTO sig(username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to menupage.html after successful login
        echo "<script>alert('Details inserted successfully! Redirecting to menu page.'); window.location.href='menu_page.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('bg_image.jpeg'); /* Add your image URL here */
            background-color: #f0f0f0;
            margin: 0;
            height: 100vh; /* Full height of the viewport */
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }

        .login-container {
            max-width: 400px;
            width: 100%; /* Make it responsive */
            background-color: transparent;
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        h2 {
            text-align: center;
            color: whitesmoke;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background-color: #ff5733;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e74c3c;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
            display: block;
            color: #ff5733;
            text-decoration: underline;
        }

        .forgot-password:hover {
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>
        <form id="login-form" action="login.php" method="POST">
            <input type="text" id="username" name="username" placeholder="Email or Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <a href="#" class="forgot-password">Forgot Password?</a>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(event) {
            // Prevent the form from submitting immediately
            event.preventDefault(); 

            // Retrieve values from input fields
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Validation checks
            if (username === "" || password === "") {
                alert("Username and password are required.");
                return;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return;
            }

            // If the form is valid, submit it
            this.submit();
        });
    </script>

</body>
</html>
