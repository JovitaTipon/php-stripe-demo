<?php
// Include Stripe PHP SDK
require 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $line1 = htmlspecialchars($_POST['line1']);
    $line2 = htmlspecialchars($_POST['line2']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $country = htmlspecialchars($_POST['country']);
    $postal_code = htmlspecialchars($_POST['postal_code']);

    try {
        // Create a customer using the Stripe API
        $customer = $stripe->customers->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => [
                'line1' => $line1,
                'line2' => $line2,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'postal_code' => $postal_code,
            ],
        ]);

        echo "<p>Customer created successfully! Customer ID: " . $customer->id . "</p>";
    } catch (Exception $e) {
        echo "<p>Error creating customer: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Stripe Customer</title>
</head>
<body>
    <h1>Create a Stripe Customer</h1>
    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="line1">Address Line 1:</label><br>
        <input type="text" id="line1" name="line1" required><br><br>

        <label for="line2">Address Line 2:</label><br>
        <input type="text" id="line2" name="line2"><br><br>

        <label for="city">City:</label><br>
        <input type="text" id="city" name="city" required><br><br>

        <label for="state">State:</label><br>
        <input type="text" id="state" name="state"><br><br>

        <label for="country">Country:</label><br>
        <input type="text" id="country" name="country" required><br><br>

        <label for="postal_code">Postal Code:</label><br>
        <input type="text" id="postal_code" name="postal_code" required><br><br>

        <button type="submit">Create Customer</button>
    </form>
</body>
</html>