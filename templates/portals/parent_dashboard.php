<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title text-success"><i class="bi bi-house-door-fill"></i> Parent Dashboard</h2>
        <p class="text-muted">Managing your children's education made easy.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-gradient bg-primary text-white p-4">
            <h6 class="text-uppercase opacity-75 small">Total Fee Balance</h6>
            <h1 class="fw-bold mb-0"><?php echo $fee_balance; ?></h1>
            <div class="mt-3">
                <a href="<?php echo BASE_URL; ?>parent/payments" class="btn btn-light btn-sm fw-bold">Pay Now <i class="bi bi-credit-card ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-gradient bg-info text-white p-4">
            <h6 class="text-uppercase opacity-75 small">Unread Messages</h6>
            <h1 class="fw-bold mb-0"><?php echo count($messages); ?></h1>
            <div class="mt-3">
                <a href="<?php echo BASE_URL; ?>communication/inbox" class="btn btn-light btn-sm fw-bold">Check Inbox <i class="bi bi-chat-dots ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Children Overview -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold">My Children Overview</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student Name</th>
                            <th>Class/Grade</th>
                            <th>Attendance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($children as $child): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle rounded-circle p-2 me-3">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <strong><?php echo Security::clean($child['name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo Security::clean($child['class']); ?></td>
                                <td>
                                    <div class="progress" style="height: 10px; width: 100px;">
                                        <div class="progress-bar bg-success" style="width: <?php echo $child['attendance']; ?>"></div>
                                    </div>
                                    <small class="text-muted"><?php echo $child['attendance']; ?></small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-info">Performance</button>
                                        <button class="btn btn-sm btn-outline-primary">Attendance</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
