<?php
/**
 * Inventory Controller
 * Manages school property, supplies, and maintenance
 */

class InventoryController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin', 'inventory_manager']);
        $model = $this->model('inventory');
        
        $data = [
            'pageTitle' => 'Asset & Inventory Dashboard',
            'total_asset_value' => $model->getInventoryValue(),
            'low_stock_items' => $model->getLowStockItems(),
            'recent_maintenance' => array_slice($model->getMaintenanceLogs(), 0, 5)
        ];
        
        $this->view('inventory/dashboard', $data);
    }

    public function assets() {
        $this->requireRole(['super_admin', 'admin', 'inventory_manager']);
        $model = $this->model('inventory');
        $usersModel = $this->model('users');
        
        if ($this->isPost()) {
            $model->addAsset([
                'asset_name' => $_POST['asset_name'],
                'category_id' => $_POST['category_id'],
                'serial_number' => $_POST['serial_number'],
                'brand' => $_POST['brand'],
                'purchase_date' => $_POST['purchase_date'],
                'purchase_cost' => $_POST['purchase_cost'],
                'location' => $_POST['location'],
                'assigned_to' => $_POST['assigned_to'] ?: null
            ]);
            $this->setFlash('success', "Asset registered successfully.");
            $this->redirect('inventory/assets');
        }

        $data = [
            'pageTitle' => 'Fixed Asset Registry',
            'assets' => $model->getAssets(),
            'categories' => $model->getCategories('asset'),
            'staff' => $usersModel->getUsersByRole('staff')
        ];
        
        $this->view('inventory/asset_registry', $data);
    }

    public function stock() {
        $this->requireRole(['super_admin', 'admin', 'inventory_manager']);
        $model = $this->model('inventory');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'add_item') {
                $model->addConsumable([
                    'item_name' => $_POST['item_name'],
                    'category_id' => $_POST['category_id'],
                    'sku' => $_POST['sku'],
                    'unit_of_measure' => $_POST['unit'],
                    'min_stock_level' => $_POST['min_stock']
                ]);
                $this->setFlash('success', "Inventory item created.");
            } elseif ($action == 'update_stock') {
                $model->updateStock(
                    $_POST['consumable_id'],
                    $_POST['quantity'],
                    $_POST['log_type'],
                    $_POST['reason'],
                    $_SESSION['user_id']
                );
                $this->setFlash('success', "Stock updated successfully.");
            }
            $this->redirect('inventory/stock');
        }

        $data = [
            'pageTitle' => 'Consumable Stock Management',
            'consumables' => $model->getConsumables(),
            'categories' => $model->getCategories('consumable')
        ];
        
        $this->view('inventory/stock_management', $data);
    }

    public function maintenance() {
        $this->requireRole(['super_admin', 'admin', 'inventory_manager']);
        $model = $this->model('inventory');
        
        if ($this->isPost()) {
            $model->addMaintenanceLog([
                'asset_id' => $_POST['asset_id'],
                'maintenance_date' => $_POST['maintenance_date'],
                'maintenance_type' => $_POST['maintenance_type'],
                'description' => $_POST['description'],
                'cost' => $_POST['cost'],
                'conducted_by' => $_POST['conducted_by']
            ]);
            $this->setFlash('success', "Maintenance event logged.");
            $this->redirect('inventory/maintenance');
        }

        $data = [
            'pageTitle' => 'Maintenance Logs',
            'logs' => $model->getMaintenanceLogs(),
            'assets' => $model->getAssets()
        ];
        
        $this->view('inventory/maintenance_log', $data);
    }
}
