<?php
// シンプルなテストファイル
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "File exists test:<br>";
echo "- data/config.php: " . (file_exists(__DIR__ . '/data/config.php') ? 'YES' : 'NO') . "<br>";
echo "- includes/header.php: " . (file_exists(__DIR__ . '/includes/header.php') ? 'YES' : 'NO') . "<br>";
echo "<br>If you see this, PHP is working!";
