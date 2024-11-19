<?php

require 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customers = $stripe->customers->all();
    $products = $stripe->products->all(['expand' => ['data.default_price']]);
    ?>
    <form method="POST" action="">
        <label for="customer">Select Customer:</label>
        <select name="customer_id" id="customer">
            <?php foreach ($customers->data as $customer): ?>
                <option value="<?= $customer->id ?>"><?= $customer->name ?? $customer->email ?></option>
            <?php endforeach; ?>
        </select>

        <h3>Select Products:</h3>
        <?php foreach ($products->data as $product): ?>
            <label>
                <input type="checkbox" name="products[]" value="<?= $product->default_price->id ?>">
                <?= $product->name ?> (<?= $product->default_price->unit_amount / 100 ?> <?= strtoupper($product->default_price->currency) ?>)
            </label><br>
        <?php endforeach; ?>

        <button type="submit">Generate Invoice</button>
    </form>
    <?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $product_prices = $_POST['products'];


    $invoice = $stripe->invoices->create([
        'customer' => $customer_id
    ]);

    foreach ($product_prices as $price_id) {
        $stripe->invoiceItems->create([
            'customer' => $customer_id,
            'price' => $price_id,
            'invoice' => $invoice->id
        ]);
    }

    $stripe->invoices->finalizeInvoice($invoice->id);

    $invoice = $stripe->invoices->retrieve($invoice->id);

    echo "Invoice created successfully!<br>";
    echo "<a href='{$invoice->hosted_invoice_url}'>Pay Invoice</a><br>";
    echo "<a href='{$invoice->invoice_pdf}' target='_blank'>Download Invoice PDF</a><br>";
}