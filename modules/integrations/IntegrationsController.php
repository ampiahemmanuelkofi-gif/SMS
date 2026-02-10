<?php
/**
 * Integrations Controller
 * Admin interface for API & Webhook management
 */

class IntegrationsController extends Controller {
    
    public function index() {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('integrations');
        
        $data = [
            'pageTitle' => 'API & Integrations',
            'keys' => $model->getKeys(),
            'webhooks' => $model->getWebhooks(),
            'logs' => $model->getRecentLogs(20)
        ];
        
        $this->view('api/management', $data);
    }

    public function generateKey() {
        $this->requireRole(['super_admin', 'admin']);
        if ($this->isPost()) {
            $model = $this->model('integrations');
            $model->generateKey($_POST['client_name'], $_POST['permissions'] ?? 'all');
            $this->setFlash('success', "New API Key generated successfully.");
        }
        $this->redirect('integrations');
    }

    public function revokeKey($id) {
        $this->requireRole(['super_admin', 'admin']);
        $model = $this->model('integrations');
        $model->revokeKey($id);
        $this->setFlash('success', "API Key revoked.");
        $this->redirect('integrations');
    }

    public function docs() {
        $this->requireRole(['super_admin', 'admin']);
        $data = ['pageTitle' => 'Developer Documentation'];
        $this->view('api/docs', $data);
    }
}
