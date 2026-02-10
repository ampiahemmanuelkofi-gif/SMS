<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-person-plus-fill"></i> Recruitment & Applicants</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicantModal">
            <i class="bi bi-plus-circle"></i> New Applicant
        </button>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Applicant Name</th>
                    <th>Position</th>
                    <th>Date Applied</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $app): ?>
                    <tr>
                        <td>
                            <strong><?php echo Security::clean($app['first_name'] . ' ' . $app['last_name']); ?></strong><br>
                            <small class="text-muted"><?php echo Security::clean($app['email']); ?> | <?php echo Security::clean($app['phone']); ?></small>
                        </td>
                        <td><?php echo Security::clean($app['position_applied']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($app['applied_at'])); ?></td>
                        <td>
                            <?php 
                            $badges = [
                                'new' => 'bg-info',
                                'shortlisted' => 'bg-primary',
                                'interviewed' => 'bg-warning',
                                'offered' => 'bg-success',
                                'hired' => 'bg-success',
                                'rejected' => 'bg-danger'
                            ];
                            ?>
                            <span class="badge <?php echo $badges[$app['status']] ?? 'bg-secondary'; ?>">
                                <?php echo ucfirst($app['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Update Status
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach (['shortlisted', 'interviewed', 'offered', 'hired', 'rejected'] as $st): ?>
                                        <li>
                                            <form action="<?php echo BASE_URL; ?>hr/recruitment" method="POST">
                                                <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                                <input type="hidden" name="status" value="<?php echo $st; ?>">
                                                <button type="submit" class="dropdown-item"><?php echo ucfirst($st); ?></button>
                                            </form>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($applicants)): ?>
                    <tr><td colspan="5" class="text-center py-4">No applicants found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
