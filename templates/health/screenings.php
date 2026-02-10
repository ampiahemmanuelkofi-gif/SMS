<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-clipboard2-check"></i> Health Screenings</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScreeningModal">
            <i class="bi bi-plus-circle me-1"></i> Record Screening
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Screening Type</th>
                            <th>Results Summary</th>
                            <th>Conducted By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($screenings as $s): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($s['screening_date'])); ?></td>
                                <td><strong><?php echo Security::clean($s['full_name']); ?></strong></td>
                                <td><span class="badge bg-info-subtle text-info border"><?php echo Security::clean($s['screening_type']); ?></span></td>
                                <td><small><?php echo Security::clean($s['results']); ?></small></td>
                                <td><small class="text-muted"><?php echo Security::clean($s['conducted_by']); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($screenings)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No screening history available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
