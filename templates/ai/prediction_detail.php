<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>ai">AI Hub</a></li>
                <li class="breadcrumb-item active">Prediction</li>
            </ol>
        </nav>
        <h2 class="page-title fw-bold"><i class="bi bi-graph-up-arrow text-primary"></i> Academic Outcome Prediction</h2>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-4 h-100">
            <div class="mb-3">
                <img src="<?php echo ASSETS_URL; ?>img/avatar.png" class="rounded-circle border" width="100">
            </div>
            <h4 class="fw-bold"><?php echo Security::clean($student['full_name']); ?></h4>
            <p class="text-muted small">Student ID: <?php echo $student['username']; ?></p>
            <hr>
            <div class="d-flex justify-content-around">
                <div>
                    <h6 class="text-muted small fw-bold">Role</h6>
                    <span class="badge bg-light text-dark">Student</span>
                </div>
                <div>
                    <h6 class="text-muted small fw-bold">Status</h6>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <?php if ($prediction): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Prediction Model: Trend Analysis</h5>
                    
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 bg-light text-center">
                                <h1 class="display-3 fw-bold <?php echo ($prediction['risk_level'] == 'High') ? 'text-danger' : 'text-primary'; ?>">
                                    <?php echo number_format($prediction['latest_score'], 1); ?>%
                                </h1>
                                <p class="mb-0 text-muted fw-bold">Latest Projected Average</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <span>Risk Level</span>
                                    <span class="badge <?php echo ($prediction['risk_level'] == 'High') ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo $prediction['risk_level']; ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                    <span>Trend</span>
                                    <span class="fw-bold <?php echo ($prediction['trend'] < 0) ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo ($prediction['trend'] > 0) ? '+' : ''; ?><?php echo number_format($prediction['trend'], 1); ?>%
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light border-start border-4 border-primary rounded">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i> AI Insight</h6>
                        <p class="mb-0 italic">"<?php echo $prediction['comment']; ?>"</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                <i class="bi bi-lightbulb fs-3 me-3"></i>
                <div class="small">
                    <strong>Suggested Action:</strong> Based on the performance trend, this student would benefit from 
                    <?php if ($prediction['risk_level'] == 'High'): ?>
                        immediate parent conferencing and remedial sessions.
                    <?php else: ?>
                        continued encouragement and advanced enrichment materials.
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm p-5 text-center">
                <i class="bi bi-database-exclamation fs-1 text-muted mb-3"></i>
                <h5>Insufficient Data</h5>
                <p class="text-muted">The AI requires at least 2 terminal examination results to generate a trend prediction for this student.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
