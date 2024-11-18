<?php
// Database connection settings
$host = 'localhost'; // Your database host
$db_name = 'ip'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

// Create a connection
$conn = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form data is set
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Prepare the SQL statement
    $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Your message has been sent successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
   

// Close the connection
mysqli_close($conn);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
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

        .contact-container {
            max-width: 400px;
            width: 100%; /* Make it responsive */
            background-color: transparent;
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column; /* Use flexbox for even alignment */
        }

        h1 {
            text-align: center;
            color: whitesmoke;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0; /* Consistent margin for all input fields */
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.9);
            box-sizing: border-box; /* Ensure padding doesn’t affect width */
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            width: 100%;
            background-color: #ff5733;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px; /* Space between the last input and button */
        }

        button:hover {
            background-color: #e74c3c;
        }

        /* Style for the "Back to Menu" link */
        .back-to-menu {
            margin-top: 15px; /* Space above the link */
            text-align: center;
        }

        .back-to-menu a {
            color: orange; /* Orange color for the link */
            text-decoration: none;
            font-weight: bold;
        }

        .back-to-menu a:hover {
            text-decoration: underline; /* Optional: underline on hover */
        }
    </style>
</head>
<body>

<div class="contact-container">
    <h1>Contact Us</h1> <!-- Main title added -->
    <form id="contactForm" action="contact_us.php" method="POST" onsubmit="return showAlert()">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>

        <button type="submit">Send Message</button>
    </form>

    <!-- Back to Menu link -->
    <div class="back-to-menu">
        <a href="menu_page.html">Back to Menu</a> <!-- Replace with the actual URL of your menu page -->
    </div>
</div>

<script>
    function showAlert() {
        alert('Your message has been sent successfully!'); // Alert message on form submission
        return true; // Allow form submission to proceed (you can also add further logic if needed)
    }
</script>

</body>
</html>


