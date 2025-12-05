<?php
/**
 * Test delete user API
 */

// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Delete User API Test</h1>";

// Simulate POST request
$_POST['username'] = 'yamada1881r@gmail.com';

echo "<h2>Request:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>Response:</h2>";
echo "<pre>";

// Include the API
ob_start();
include 'api_delete_user.php';
$response = ob_get_clean();

echo htmlspecialchars($response);
echo "</pre>";

echo "<h2>Decoded JSON:</h2>";
echo "<pre>";
$decoded = json_decode($response, true);
print_r($decoded);
echo "</pre>";
