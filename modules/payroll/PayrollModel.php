<?php
/**
 * Payroll Model
 * Handles salary calculations based on Ghana (SSNIT, PAYE)
 */

class PayrollModel extends Model {
    
    public function getConfig($key) {
        $res = $this->selectOne("SELECT setting_value FROM payroll_configs WHERE setting_key = ?", [$key]);
        return $res ? (float)$res['setting_value'] : 0;
    }

    public function calculatePayroll($employeeId, $month, $year) {
        $hr = new HrModel();
        $emp = $hr->getEmployeeById($employeeId);
        if (!$emp) return null;

        $basicSalary = (float)$emp['base_salary'];
        
        // SSNIT Rates (Usually 5.5% employee, 13% employer)
        $ssnitEmpRate = $this->getConfig('ssnit_employee_rate');
        $ssnitEmployerRate = $this->getConfig('ssnit_employer_rate');
        
        $ssnitEmployee = $basicSalary * $ssnitEmpRate;
        $ssnitEmployer = $basicSalary * $ssnitEmployerRate;
        
        // Income Tax (PAYE) - Simplified Progressive Tax Logic for demonstration
        // 0 to 402 - 0%
        // Next 110 - 5%
        // Next 130 - 10%
        // etc...
        $taxableIncome = $basicSalary - $ssnitEmployee;
        $incomeTax = $this->calculatePAYE($taxableIncome);
        
        $netSalary = $taxableIncome - $incomeTax;

        return [
            'employee_id' => $employeeId,
            'month' => $month,
            'year' => $year,
            'basic_salary' => $basicSalary,
            'allowances' => 0,
            'deductions' => 0,
            'ssnit_employee' => $ssnitEmployee,
            'ssnit_employer' => $ssnitEmployer,
            'income_tax' => $incomeTax,
            'net_salary' => $netSalary,
            'status' => 'generated'
        ];
    }

    /**
     * Simplified Ghana PAYE calculation
     */
    private function calculatePAYE($taxableIncome) {
        $taxFree = 402.00;
        if ($taxableIncome <= $taxFree) return 0;

        $taxable = $taxableIncome - $taxFree;
        $tax = 0;

        // Band 1: Next 110 at 5%
        if ($taxable > 0) {
            $amount = min($taxable, 110);
            $tax += $amount * 0.05;
            $taxable -= $amount;
        }

        // Band 2: Next 130 at 10%
        if ($taxable > 0) {
            $amount = min($taxable, 130);
            $tax += $amount * 0.10;
            $taxable -= $amount;
        }

        // Band 3: Next 3000 at 17.5%
        if ($taxable > 0) {
            $amount = min($taxable, 3000);
            $tax += $amount * 0.175;
            $taxable -= $amount;
        }

        // Band 4: Next 16358 at 25%
        if ($taxable > 0) {
            $amount = min($taxable, 16358);
            $tax += $amount * 0.25;
            $taxable -= $amount;
        }

        // Band 5: Above 20000 at 30%
        if ($taxable > 0) {
            $tax += $taxable * 0.30;
        }

        return round($tax, 2);
    }

    public function savePayroll($data) {
        return $this->insert('payrolls', $data);
    }

    public function getPayrolls($month, $year) {
        return $this->select("
            SELECT p.*, u.full_name, e.employee_id as emp_code
            FROM payrolls p
            JOIN employees e ON p.employee_id = e.id
            JOIN users u ON e.user_id = u.id
            WHERE p.month = ? AND p.year = ?
        ", [$month, $year]);
    }
}
