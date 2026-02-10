<?php
echo "Current User: " . shell_exec('whoami') . "\n";
echo "Current Dir: " . getcwd() . "\n";
echo "Realpath of modules: " . realpath('modules') . "\n";
echo "Directory exists: " . (is_dir('c:/xampp/htdocs/sch/modules/payroll') ? "YES" : "NO") . "\n";
?>
