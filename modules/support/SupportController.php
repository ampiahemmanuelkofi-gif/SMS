<?php
/**
 * Help Desk & Support Controller
 */

class SupportController extends Controller {
    
    protected $supportModel;

    public function __construct() {
        $this->supportModel = $this->model('support');
    }
    
    /**
     * Support Hub Dashboard
     */
    public function index() {
        $this->requirePermission('support');
        $data = [
            'pageTitle' => 'Support Hub',
            'faqs' => $this->supportModel->getFAQs(),
            'tickets' => $this->supportModel->getTickets(Auth::getUserId())
        ];
        $this->view('support/hub', $data);
    }
    
    /**
     * Create Ticket
     */
    public function create_ticket() {
        if ($this->isPost()) {
            $ticketData = [
                'user_id' => Auth::getUserId(),
                'subject' => $this->post('subject'),
                'category' => $this->post('category'),
                'priority' => $this->post('priority'),
                'description' => $this->post('description')
            ];
            $this->supportModel->createTicket($ticketData);
            $this->setFlash('success', 'Support ticket created successfully. Our team will get back to you soon.');
            $this->redirect('support');
        }
    }
    
    /**
     * Knowledge Base
     */
    public function faq() {
        $data = [
            'pageTitle' => 'Knowledge Base',
            'faqs' => $this->supportModel->getFAQs()
        ];
        $this->view('support/faq', $data);
    }
    
    /**
     * Feature Suggestions & Voting
     */
    public function feedback() {
        if ($this->get('vote')) {
            $this->supportModel->voteForFeature($this->get('vote'), Auth::getUserId());
            $this->setFlash('success', 'Thank you for your vote!');
            $this->redirect('support/feedback');
        }

        $data = [
            'pageTitle' => 'Feature Requests',
            'requests' => $this->supportModel->getFeatureRequests(Auth::getUserId())
        ];
        $this->view('support/feedback', $data);
    }
}
