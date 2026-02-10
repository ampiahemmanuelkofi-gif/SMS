<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-heart-pulse text-danger"></i> Health & Medical Dashboard</h2>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-person-plus text-primary fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Clinic Visits Today</h6>
                    <h2 class="fw-bold mb-0"><?php echo $total_visits_today; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4 border-start border-4 border-warning">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-exclamation-triangle text-warning fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Critical Alerts</h6>
                    <h2 class="fw-bold mb-0"><?php echo count($critical_alerts); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 p-4 border-start border-4 border-info">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="bi bi-clipboard2-pulse text-info fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Open Cases</h6>
                    <h2 class="fw-bold mb-0">2</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Recent Clinic Visits</h5>
                <a href="<?php echo BASE_URL; ?>health/visits" class="btn btn-sm btn-primary">Log New Visit</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Visit Date</th>
                            <th>Symptoms</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($recent_visits, 0, 8) as $v): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo Security::clean($v['patient_name']); ?></div>
                                    <small class="text-muted">By: <?php echo Security::clean($v['staff_name']); ?></small>
                                </td>
                                <td><?php echo date('M d, H:i', strtotime($v['visit_date'])); ?></td>
                                <td><small><?php echo substr(Security::clean($v['symptoms']), 0, 50); ?>...</small></td>
                                <td><span class="badge bg-success"><?php echo ucfirst($v['status']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_visits)): ?>
                            <tr><td colspan="4" class="text-center py-4">No visits recorded recently.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm border-danger-subtle">
            <div class="card-header bg-danger text-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-shield-alert me-2"></i>Critical Health Alerts</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($critical_alerts as $alert): ?>
                        <div class="list-group-item p-3">
                            <h6 class="fw-bold mb-1 text-danger"><?php echo Security::clean($alert['full_name']); ?></h6>
                            <?php if ($alert['allergies']): ?>
                                <div class="small mb-1"><strong>Allergies:</strong> <?php echo Security::clean($alert['allergies']); ?></div>
                            <?php endif; ?>
                            <?php if ($alert['chronic_conditions']): ?>
                                <div class="small"><strong>Chronic:</strong> <?php echo Security::clean($alert['chronic_conditions']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($critical_alerts)): ?>
                        <div class="p-4 text-center text-muted">No critical alerts active.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
