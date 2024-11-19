<?php

require "init.php";

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Product List</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }";
echo "h1 { text-align: center; color: #333; margin-bottom: 30px; }";
echo ".container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }";
echo ".product { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fff; display: flex; flex-direction: row; justify-content: space-between; align-items: flex-start; transition: box-shadow 0.3s; }";
echo ".product:hover { box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }";
echo ".product img { max-width: 150px; height: auto; border-radius: 8px; margin-right: 20px; }";
echo ".product-details { flex: 1; }";
echo ".product h2 { font-size: 1.6em; color: #333; margin-bottom: 10px; }";
echo ".product p { margin: 5px 0; color: #555; font-size: 1.1em; }";
echo ".price { font-size: 1.4em; font-weight: bold; color: #333; margin-top: 10px; }";
echo "@media (max-width: 768px) {";
echo "  .product { flex-direction: column; align-items: flex-start; }";
echo "  .product img { margin-right: 0; margin-bottom: 15px; }";
echo "  .product-details { max-width: 100%; }";
echo "}";
echo "@media (max-width: 480px) {";
echo "  h1 { font-size: 1.8em; }";
echo "  .price { font-size: 1.2em; }";
echo "  .product h2 { font-size: 1.4em; }";
echo "  .product p { font-size: 1em; }";
echo "}";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>Available Products</h1>";

echo "<div class='container'>";

$products = $stripe->products->all();
foreach ($products as $product) {

    $price = $stripe->prices->retrieve($product->default_price);
    
    $formattedPrice = number_format($price->unit_amount / 100, 2);
    $currency = strtoupper($price->currency);
    $image = array_pop($product->images);

    echo "<div class='product'>";
    
    if ($image) {
        echo "<img src='" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($product->name) . "' />";
    }

    echo "<div class='product-details'>";
    echo "<h2>" . htmlspecialchars($product->name) . "</h2>";
    echo "<p><strong>Product ID:</strong> " . htmlspecialchars($product->id) . "</p>";
    echo "<p class='price'>" . $currency . " " . $formattedPrice . "</p>";
    
    echo "</div>";
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";

?>

