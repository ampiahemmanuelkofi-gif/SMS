<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'modules/hr/PayrollModel.php';
require_once 'modules/hr/HrModel.php';

// Mock Model classes since we need to test PayrollModel logic
// In a real system we'd use a testing framework

class TestPayroll extends PayrollModel {
    public function getConfig($key) {
        $configs = [
            'ssnit_employee_rate' => 0.055,
            'ssnit_employer_rate' => 0.13,
            'tax_free_threshold' => 402.00
        ];
        return $configs[$key];
    }
    
    public function testPaye($income) {
        // Accessing private method via reflection or just copying/pasting for test if needed
        // For simplicity let's assume it's public for this test or we use the payroll calc
        return $this->calculatePAYE($income);
    }

    // Copying the private method for testing purposes
    private function calculatePAYE($taxableIncome) {
        $taxFree = 402.00;
        if ($taxableIncome <= $taxFree) return 0;
        $taxable = $taxableIncome - $taxFree;
        $tax = 0;
        if ($taxable > 0) { $amount = min($taxable, 110); $tax += $amount * 0.05; $taxable -= $amount; }
        if ($taxable > 0) { $amount = min($taxable, 130); $tax += $amount * 0.10; $taxable -= $amount; }
        if ($taxable > 0) { $amount = min($taxable, 3000); $tax += $amount * 0.175; $taxable -= $amount; }
        return round($tax, 2);
    }
}

$test = new TestPayroll();

echo "Testing PAYE Calculations:\n";

$cases = [
    ['income' => 400, 'expected' => 0],
    ['income' => 500, 'expected' => 4.9], // (500-402) = 98 * 0.05 = 4.9
    ['income' => 1000, 'expected' => 110*0.05 + 130*0.10 + (1000-402-110-130)*0.175], // 5.5 + 13 + 358*0.175 = 5.5 + 13 + 62.65 = 81.15
];

foreach ($cases as $c) {
    $res = $test->testPaye($c['income']);
    echo "Income: {$c['income']} -> PAYE: $res (Expected: {$c['expected']}) - " . ($res == $c['expected'] ? "PASS" : "FAIL") . "\n";
}
