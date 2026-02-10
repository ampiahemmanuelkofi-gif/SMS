<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-calendar-event"></i> Academic Periods</h2>
    </div>
</div>

<div class="row">
    <!-- Academic Years -->
    <div class="col-md-5 mb-4">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Academic Years</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal">
                    <i class="bi bi-plus"></i> Add Year
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($years as $yr): ?>
                            <tr>
                                <td><?php echo Security::clean($yr['name']); ?></td>
                                <td>
                                    <?php if ($yr['is_current']): ?>
                                        <span class="badge bg-success">Current</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Past</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-link"><i class="bi bi-pencil"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Terms -->
    <div class="col-md-7">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Terms/Semesters</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTermModal">
                    <i class="bi bi-plus"></i> Add Term
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Term Name</th>
                            <th>Status</th>
                            <th>Dates</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($terms as $term): ?>
                            <tr>
                                <td><?php echo Security::clean($term['year_name']); ?></td>
                                <td><?php echo Security::clean($term['name']); ?></td>
                                <td>
                                    <?php if ($term['is_current']): ?>
                                        <span class="badge bg-success">Current</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo date('M d', strtotime($term['start_date'])); ?> - <?php echo date('M d', strtotime($term['end_date'])); ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Year Modal -->
<div class="modal fade" id="addYearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>academics/addYear" method="POST">
                <div class="modal-header"><h5>Add Academic Year</h5></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Name (e.g. 2024-2025)</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" required></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_current" id="cyear"><label class="form-check-label" for="cyear">Make Current Year</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Year</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Add Term Modal -->
<div class="modal fade" id="addTermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>academics/addTerm" method="POST">
                <div class="modal-header"><h5>Add Term</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            <?php foreach ($years as $yr): ?>
                                <option value="<?php echo $yr['id']; ?>"><?php echo $yr['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Term Name (e.g. Term 1)</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" required></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_current" id="cterm"><label class="form-check-label" for="cterm">Make Current Term</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Term</button></div>
            </form>
        </div>
    </div>
</div>
