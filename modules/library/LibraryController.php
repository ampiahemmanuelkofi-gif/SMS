<?php
/**
 * Library Controller
 * Manages library operations
 */

class LibraryController extends Controller {
    
    public function index() {
        $this->requirePermission('library');
        $model = $this->model('library');
        
        $search = $this->get('search', '');
        $category = $this->get('category', null);
        
        $data = [
            'pageTitle' => 'Library Catalog',
            'books' => $model->getBooks($search, $category),
            'categories' => $model->getCategories(),
            'search' => $search,
            'category' => $category
        ];
        
        $this->view('library/catalog', $data);
    }

    public function circulation() {
        $this->requirePermission('library');
        $model = $this->model('library');
        
        if ($this->isPost()) {
            $action = $_POST['action'];
            if ($action == 'issue') {
                $barcode = $_POST['barcode'];
                $userId = $_POST['user_id'];
                $dueDate = $_POST['due_date'];
                
                $inventory = $model->getInventoryByBarcode($barcode);
                if ($inventory && $inventory['status'] == 'available') {
                    if ($model->issueBook($inventory['id'], $userId, $_SESSION['user_id'], $dueDate)) {
                        $this->setFlash('success', "Book '{$inventory['title']}' issued successfully.");
                    } else {
                        $this->setFlash('error', "Failed to issue book.");
                    }
                } else {
                    $this->setFlash('error', "Book not available or not found.");
                }
            } elseif ($action == 'return') {
                $circulationId = $_POST['circulation_id'];
                if ($model->returnBook($circulationId, $_SESSION['user_id'], date('Y-m-d H:i:s'))) {
                    $this->setFlash('success', "Book returned successfully.");
                } else {
                    $this->setFlash('error', "Failed to process return.");
                }
            }
            $this->redirect('library/circulation');
        }

        $data = [
            'pageTitle' => 'Circulation Management',
            'activeLoans' => $model->getActiveLoans(),
            'students' => $this->model('users')->getUsersByRole('student') // Assuming this exists
        ];
        
        $this->view('library/circulation', $data);
    }

    public function fines() {
        $this->requirePermission('library');
        $model = $this->model('library');
        
        $data = [
            'pageTitle' => 'Library Fines',
            'fines' => $model->getFines('unpaid')
        ];
        
        $this->view('library/fines', $data);
    }

    public function add_book() {
        $this->requirePermission('library');
        $model = $this->model('library');
        
        if ($this->isPost()) {
            $bookId = $model->addBook([
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'],
                'category_id' => $_POST['category_id'],
                'publisher' => $_POST['publisher'],
                'type' => $_POST['type']
            ]);
            
            if ($bookId) {
                // Add initial inventory copies
                $copies = (int)$_POST['copies'];
                for ($i = 1; $i <= $copies; $i++) {
                    $model->addInventoryItem([
                        'book_id' => $bookId,
                        'barcode' => $_POST['isbn'] . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'location_shelf' => $_POST['location_shelf']
                    ]);
                }
                $this->setFlash('success', "Book and $copies copies added to catalog.");
                $this->redirect('library');
            }
        }

        $data = [
            'pageTitle' => 'Add New Book',
            'categories' => $model->getCategories()
        ];
        
        $this->view('library/add_book', $data);
    }
}
