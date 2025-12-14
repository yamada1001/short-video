<?php
/**
 * Test file upload functionality
 */

require_once __DIR__ . '/config.php';

// Create test PDF file
$testPdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/Resources <<\n/Font <<\n/F1 <<\n/Type /Font\n/Subtype /Type1\n/BaseFont /Helvetica\n>>\n>>\n>>\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n4 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 12 Tf\n100 700 Td\n(Test PDF) Tj\nET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000314 00000 n \ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n407\n%%EOF";

$testPdfPath = sys_get_temp_dir() . '/test_presentation.pdf';
file_put_contents($testPdfPath, $testPdfContent);

echo "=== Testing Main Presenter File Upload ===\n\n";

// Test 1: Read action
echo "Test 1: Read action (no data)\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/main_presenter_crud.php?action=read&week_date=2024-12-20");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response: $response\n\n";

// Test 2: Create with PDF
echo "Test 2: Create new presentation with PDF\n";
$ch = curl_init();
$postData = [
    'action' => 'create',
    'member_id' => '1',
    'week_date' => '2024-12-20',
    'presentation_type' => 'extended',
    'pdf_file' => new CURLFile($testPdfPath, 'application/pdf', 'test.pdf')
];
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/main_presenter_crud.php");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test 3: Read action (with data)
echo "Test 3: Read action (with data)\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/main_presenter_crud.php?action=read&week_date=2024-12-20");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response: $response\n\n";

// Test 4: Update with different member
echo "Test 4: Update presentation\n";
$ch = curl_init();
$postData = [
    'action' => 'update',
    'member_id' => '2',
    'week_date' => '2024-12-20',
    'presentation_type' => 'simple',
    'youtube_url' => 'https://www.youtube.com/watch?v=test123'
];
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/api/main_presenter_crud.php");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo "Response: $response\n\n";

// Cleanup
unlink($testPdfPath);

echo "=== Test Complete ===\n";
echo "\nTo run this test:\n";
echo "1. Start PHP server: cd slides_v2 && php -S localhost:8000\n";
echo "2. Run test: php test_file_upload.php\n";
