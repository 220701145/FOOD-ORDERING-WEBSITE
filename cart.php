<?php
// Database connection details
$servername = "localhost";  // or the server address
$username = "root";         // MySQL username (adjust as per your setup)
$password = "";             // MySQL password (adjust as per your setup)
$dbname = "ip";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    
    
    // Validate that the phone number has exactly 10 digits
    if (!preg_match("/^\d{10}$/", $phone)) {
        echo "Invalid phone number. Please enter a 10-digit number.";
    } else {
        // Insert the data into the database
        $sql = "INSERT INTO cart (address, phone) VALUES ('$address', '$phone')";

        if ($conn->query($sql) === TRUE) {
            echo "Order placed successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('bg_image.jpeg'); /* Add your image URL here */
            background-color: #f0f0f0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #ff5733;
        }

        .cart-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #ff5733;
            color: white;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .remove-item {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-item:hover {
            background-color: #c0392b;
        }

        .clear-cart {
            display: block;
            width: 100%;
            background-color: #ff5733;
            color: white;
            text-align: center;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .clear-cart:hover {
            background-color: #e74c3c;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #ff5733;
            text-decoration: underline;
        }

        .back-link:hover {
            color: #e74c3c;
        }

        input[type="number"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Reduce the size of the quantity input field */
        input[type="number"] {
            width: 60px; /* Adjust width to your preference */
        }

        .order-btn {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .order-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <h1>Your Cart</h1>

    <div class="cart-container">
        <table id="cart-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price (₹)</th>
                    <th>Quantity</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <!-- Cart items will be dynamically inserted here -->
            </tbody>
        </table>

        <p class="total">Total: ₹<span id="total-price">0</span></p>
        <button class="clear-cart">Clear Cart</button>

        <!-- Add delivery address and phone number fields -->
        <div class="delivery-form">
        <form id="delivery-form" action="cart.php" method="POST">
            <label for="address">Delivery Address:</label>
            <input type="text" id="address" name="address" placeholder="Enter your delivery address" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required pattern="\d{10}">
        
            <!-- Order button, initially disabled -->
            <button type="submit" class="order-btn" id="order-btn" disabled>Place Order</button>
        </form>
        </div>
       
    <a class="back-link" href="menu_page.html">Back to Menu</a>

    <script>
        // Retrieve cart items from localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Display the cart items
        function displayCart() {
            const cartTable = document.getElementById('cart-table').querySelector('tbody');
            const totalPriceElement = document.getElementById('total-price');
            cartTable.innerHTML = ''; // Clear previous content
            let totalPrice = 0;

            cart.forEach((item, index) => {
                const row = document.createElement('tr');

                // Create item name cell
                const nameCell = document.createElement('td');
                nameCell.textContent = item.name;
                row.appendChild(nameCell);

                // Create price cell
                const priceCell = document.createElement('td');
                priceCell.textContent = item.price;
                row.appendChild(priceCell);

                // Create quantity cell with editable input
                const quantityCell = document.createElement('td');
                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.value = item.quantity || 1;  // Default to 1 if no quantity
                quantityInput.min = 1;
                quantityInput.addEventListener('change', function () {
                    updateQuantity(index, parseInt(quantityInput.value));
                });
                quantityCell.appendChild(quantityInput);
                row.appendChild(quantityCell);

                // Create remove button cell
                const removeCell = document.createElement('td');
                const removeButton = document.createElement('button');
                removeButton.textContent = 'Remove';
                removeButton.classList.add('remove-item');
                removeButton.addEventListener('click', () => {
                    removeItem(index);
                });
                removeCell.appendChild(removeButton);
                row.appendChild(removeCell);

                cartTable.appendChild(row);

                // Add item price * quantity to total
                totalPrice += parseInt(item.price) * (item.quantity || 1);
            });

            // Update total price
            totalPriceElement.textContent = totalPrice;
        }

        // Update the quantity of an item
        function updateQuantity(index, newQuantity) {
            cart[index].quantity = newQuantity; // Update quantity in cart
            localStorage.setItem('cart', JSON.stringify(cart)); // Save updated cart
            displayCart(); // Refresh cart display
        }

        // Remove an item from the cart
        function removeItem(index) {
            cart.splice(index, 1); // Remove item at the given index
            localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
            displayCart(); // Refresh cart display
        }

        // Clear the entire cart
        document.querySelector('.clear-cart').addEventListener('click', function () {
            cart = []; // Empty the cart
            localStorage.setItem('cart', JSON.stringify(cart)); // Update localStorage
            displayCart(); // Refresh cart display
        });

        // Enable or disable the order button based on form inputs
        const addressInput = document.getElementById('address');
        const phoneInput = document.getElementById('phone');
        const orderButton = document.getElementById('order-btn');

        function validateForm() {
            const isAddressValid = addressInput.value.trim() !== '';
            const isPhoneValid = phoneInput.value.trim().match(/^\d{10}$/); // Valid if exactly 10 digits

            if (isAddressValid && isPhoneValid) {
                orderButton.disabled = false;
            } else {
                orderButton.disabled = true;
            }
        }

        // Listen for input changes to validate the form
        addressInput.addEventListener('input', validateForm);
        phoneInput.addEventListener('input', validateForm);

        // Display the cart on page load
        displayCart();
    </script>

</body>
</html>


