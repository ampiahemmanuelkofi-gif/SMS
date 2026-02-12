<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Alumni Registry</h5>
        <div class="d-flex gap-2">
            <form action="<?= BASE_URL ?>alumni/registry" method="GET" class="d-flex">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Search alumni..." value="<?= $search ?? '' ?>">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Alumni</th>
                        <th>Student ID</th>
                        <th>Class of</th>
                        <th>Contact</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alumni)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="<?= BASE_URL ?>assets/img/empty-students.svg" alt="Empty" style="width: 100px; opacity: 0.3;" class="mb-3 d-block mx-auto">
                                <p class="text-muted">No alumni found in the registry.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alumni as $alum): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-soft-primary text-primary p-2 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <?= strtoupper(substr($alum['first_name'], 0, 1) . substr($alum['last_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?= $alum['first_name'] . ' ' . $alum['last_name'] ?></div>
                                            <div class="small text-muted"><?= $alum['gender'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= $alum['student_id'] ?></span></td>
                                <td><?= $alum['class_name'] ?></td>
                                <td>
                                    <div class="small text-muted"><i class="bi bi-envelope me-1"></i> <?= $alum['email'] ?? 'N/A' ?></div>
                                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i> <?= $alum['phone'] ?? 'N/A' ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>students/view/<?= $alum['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Profile</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
</style>
