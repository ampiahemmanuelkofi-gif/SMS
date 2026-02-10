<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Feature Requests</h2>
            <p class="text-muted">Vote for features you'd like to see or suggest your own.</p>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Suggest a New Feature</h6>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="I wish the system could...">
                    <button class="btn btn-primary px-4">Post Idea</button>
                </div>
            </div>
        </div>

        <div class="list-group card border-0 shadow-sm overflow-hidden p-0">
            <?php foreach ($requests as $req): ?>
                <div class="list-group-item p-4 d-flex justify-content-between align-items-start border-0 border-bottom">
                    <div class="me-3 text-center" style="width: 60px;">
                        <a href="?vote=<?php echo $req['id']; ?>" class="btn <?php echo ($req['has_voted']) ? 'btn-primary' : 'btn-outline-primary'; ?> w-100 px-0">
                            <i class="bi bi-caret-up-fill d-block fs-5"></i>
                            <span class="fw-bold"><?php echo $req['votes']; ?></span>
                        </a>
                        <small class="text-muted x-small d-block mt-1">VOTES</small>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold mb-0"><?php echo $req['title']; ?></h6>
                            <span class="badge bg-soft-<?php 
                                echo ($req['status'] == 'completed') ? 'success' : (($req['status'] == 'in_development') ? 'info' : 'secondary'); 
                            ?> text-dark border-0">
                                <?php echo strtoupper(str_replace('_', ' ', $req['status'])); ?>
                            </span>
                        </div>
                        <p class="text-muted small mb-0"><?php echo $req['description']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Mock items if empty -->
            <?php if (empty($requests)): ?>
                <div class="list-group-item p-4 d-flex justify-content-between align-items-start">
                    <div class="me-3 text-center" style="width: 60px;">
                        <button class="btn btn-outline-primary w-100 px-0">
                            <i class="bi bi-caret-up-fill d-block fs-5"></i>
                            <span class="fw-bold">12</span>
                        </button>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold mb-0">Offline Assignment Submission</h6>
                            <span class="badge bg-soft-info text-info border-0">IN DEVELOPMENT</span>
                        </div>
                        <p class="text-muted small mb-0">Allow students to upload assignments while offline and sync later.</p>
                    </div>
                </div>
                <div class="list-group-item p-4 d-flex justify-content-between align-items-start">
                    <div class="me-3 text-center" style="width: 60px;">
                        <button class="btn btn-outline-primary w-100 px-0">
                            <i class="bi bi-caret-up-fill d-block fs-5"></i>
                            <span class="fw-bold">8</span>
                        </button>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold mb-0">Student Portfolio Export</h6>
                            <span class="badge bg-soft-secondary text-secondary border-0">PLANNED</span>
                        </div>
                        <p class="text-muted small mb-0">Generate a comprehensive PDF of all student achievements across terms.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 10px; }
    .bg-soft-success { background-color: #e8f5e9; color: #2e7d32 !important; }
    .bg-soft-info { background-color: #e1f5fe; color: #0288d1 !important; }
</style>
