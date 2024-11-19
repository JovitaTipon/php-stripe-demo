<?php
require "init.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $products = $stripe->products->all(['expand' => ['data.default_price']]);
    ?>
    <form method="POST" action="">
        <h3>Select Products to Pay For:</h3>
        <?php foreach ($products->data as $product): ?>
            <?php if (isset($product->default_price)): ?>
                <label>
                    <input type="checkbox" name="products[]" value="<?= $product->default_price->id ?>">
                    <?= $product->name ?> (<?= $product->default_price->unit_amount / 100 ?> <?= strtoupper($product->default_price->currency) ?>)
                </label><br>
            <?php endif; ?>
        <?php endforeach; ?>
        <button type="submit">Generate Payment Link</button>
    </form>
    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $selected_products = $_POST['products'] ?? [];

        if (empty($selected_products)) {
            throw new Exception("No products selected. Please select at least one product.");
        }

        $line_items = [];
        foreach ($selected_products as $price_id) {
            $line_items[] = [
                'price' => $price_id,
                'quantity' => 1,
            ];
        }

        $payment_link = $stripe->paymentLinks->create([
            'line_items' => $line_items,
        ]);

        header("Location: " . $payment_link->url);
        exit;
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>Go Back</a>";
    }
}