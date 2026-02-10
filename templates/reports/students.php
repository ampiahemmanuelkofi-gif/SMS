<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title"><i class="bi bi-people-fill"></i> Student Statistics</h2>
            <p class="text-muted">Demographics and enrollment distribution overview.</p>
        </div>
        <button class="btn btn-outline-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Stats
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="table-card text-center py-4 shadow-sm border-0 bg-primary text-white">
            <h1 class="display-4 fw-bold"><?php echo $stats['total']; ?></h1>
            <p class="mb-0">Total Students</p>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="table-card shadow-sm border-0 h-100">
            <h5>Gender Distribution</h5>
            <div class="progress mt-4" style="height: 35px;">
                <?php 
                $total = $stats['total'];
                foreach ($stats['by_gender'] as $g): 
                    $percent = ($total > 0) ? ($g['count'] / $total) * 100 : 0;
                    $bgClass = $g['gender'] == 'male' ? 'bg-info' : 'bg-danger';
                ?>
                    <div class="progress-bar <?php echo $bgClass; ?>" role="progressbar" style="width: <?php echo $percent; ?>%" 
                         aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo ucfirst($g['gender']); ?> (<?php echo $g['count']; ?>)
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-center gap-4 mt-3 small">
                <span class="text-info"><i class="bi bi-circle-fill"></i> Male</span>
                <span class="text-danger"><i class="bi bi-circle-fill"></i> Female</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="table-card shadow-sm border-0">
            <h5>Enrollment by Class</h5>
            <div class="table-responsive">
                <table class="table table-sm mt-2">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th class="text-end">Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['by_class'] as $c): ?>
                            <tr>
                                <td><?php echo Security::clean($c['name']); ?></td>
                                <td class="text-end fw-bold"><?php echo $c['count']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="table-card shadow-sm border-0">
            <h5>Status Overview</h5>
            <div class="list-group list-group-flush mt-2">
                <?php foreach ($stats['status'] as $s): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-capitalize"><?php echo $s['status']; ?></span>
                        <span class="badge bg-secondary rounded-pill"><?php echo $s['count']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
