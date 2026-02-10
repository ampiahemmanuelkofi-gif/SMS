<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admissions">Admissions</a></li>
                    <li class="breadcrumb-item active">Waitlist Management</li>
                </ol>
            </nav>
            <h2 class="page-title">Waitlist Management</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i> Use this page to manage the priority order of waitlisted students. Lower rank numbers indicate higher priority.
        </div>
        
        <div class="table-card">
            <form action="<?php echo BASE_URL; ?>admissions/waitlist" method="POST">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Rank</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Application Date</th>
                                <th>Guardian Contact</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x display-4 d-block mb-3"></i>
                                        No students currently on the waitlist.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>
                                            <input type="number" name="ranks[<?php echo $student['id']; ?>]" class="form-control form-control-sm" value="<?php echo $student['waiting_list_rank'] ? $student['waiting_list_rank'] : 99; ?>" style="width: 70px;">
                                        </td>
                                        <td>
                                            <strong><?php echo Security::clean($student['first_name'] . ' ' . $student['last_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo Security::clean($student['application_number']); ?></small>
                                        </td>
                                        <td><?php echo Security::clean($student['class_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                        <td>
                                            <?php echo Security::clean($student['guardian_name']); ?><br>
                                            <small class="text-muted"><?php echo Security::clean($student['guardian_phone']); ?></small>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?php echo BASE_URL; ?>admissions/view_application/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                View Profile
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (!empty($students)): ?>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Rankings
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
