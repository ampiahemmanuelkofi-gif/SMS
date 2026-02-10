<?php
$modulesDir = 'c:/xampp/htdocs/sch/modules';
$items = scandir($modulesDir);
$output = "Scandir of $modulesDir:\n";
foreach ($items as $index => $item) {
    $output .= "[$index] => $item\n";
}
file_put_contents('c:/xampp/htdocs/sch/debug_info.txt', $output);
echo "Done. Check debug_info.txt\n";
?>
