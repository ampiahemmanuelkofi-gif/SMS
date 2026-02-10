<?php
function findFile($dir, $fileName) {
    if (!is_dir($dir)) return;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            findFile($path, $fileName);
        } else if ($item === $fileName) {
            echo "FOUND: $path\n";
        }
    }
}

$root = 'c:/xampp/htdocs/sch';
echo "Searching for PayrollModel.php starting from $root via PHP:\n";
findFile($root, 'PayrollModel.php');
?>
