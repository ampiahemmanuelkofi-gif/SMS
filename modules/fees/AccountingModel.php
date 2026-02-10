<?php
/**
 * Accounting Model
 * Handles general ledger and financial reports
 */
class AccountingModel extends Model {
    
    /**
     * Get Chart of Accounts
     */
    public function getChartOfAccounts() {
        return $this->select("SELECT * FROM chart_of_accounts WHERE is_active = 1 ORDER BY code");
    }
    
    /**
     * Record a double-entry transaction
     */
    public function recordTransaction($data) {
        // Expected data: array of entries (account_id, debit, credit, description, date, ref_type, ref_id)
        $db = getDbConnection();
        $db->beginTransaction();
        
        try {
            $stmt = $db->prepare("INSERT INTO ledger_entries (account_id, transaction_date, description, debit, credit, reference_type, reference_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($data as $entry) {
                $stmt->execute([
                    $entry['account_id'],
                    $entry['date'] ?? date('Y-m-d'),
                    $entry['description'] ?? '',
                    $entry['debit'] ?? 0,
                    $entry['credit'] ?? 0,
                    $entry['reference_type'] ?? null,
                    $entry['reference_id'] ?? null
                ]);
            }
            
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
    
    /**
     * Get Profit & Loss Report
     */
    public function getPnL($startDate, $endDate) {
        $db = getDbConnection();
        
        // Income
        $income = $db->query("
            SELECT coa.name, SUM(le.credit - le.debit) as balance
            FROM ledger_entries le
            JOIN chart_of_accounts coa ON le.account_id = coa.id
            WHERE coa.type = 'income' AND le.transaction_date BETWEEN '$startDate' AND '$endDate'
            GROUP BY coa.id
        ")->fetchAll();
        
        // Expenses
        $expenses = $db->query("
            SELECT coa.name, SUM(le.debit - le.credit) as balance
            FROM ledger_entries le
            JOIN chart_of_accounts coa ON le.account_id = coa.id
            WHERE coa.type = 'expense' AND le.transaction_date BETWEEN '$startDate' AND '$endDate'
            GROUP BY coa.id
        ")->fetchAll();
        
        return [
            'income' => $income,
            'expenses' => $expenses,
            'net_profit' => array_sum(array_column($income, 'balance')) - array_sum(array_column($expenses, 'balance'))
        ];
    }
}
