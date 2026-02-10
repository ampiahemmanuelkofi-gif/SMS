<div class="row mb-4">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>exam">Exams</a></li>
                <li class="breadcrumb-item active">Marks Entry</li>
            </ol>
        </nav>
        <h2 class="page-title text-dark fw-bold"><i class="bi bi-pencil-square text-primary"></i> Enter Student Marks</h2>
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                You are recording marks for <strong><?php echo Security::clean($exam['subject_name']); ?></strong> (<?php echo $exam['type_name']; ?>)
                scheduled on <strong><?php echo $exam['exam_date']; ?></strong>. Max marks: <strong><?php echo $exam['max_marks']; ?></strong>.
            </div>
        </div>
    </div>
</div>

<form action="<?php echo BASE_URL; ?>exam/marks/<?php echo $exam['id']; ?>" method="POST">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="30%">Student Name</th>
                                <th width="20%">Score (<?php echo $exam['max_marks']; ?>)</th>
                                <th width="50%">Teacher Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $s): ?>
                                <?php $mark = $existing_marks[$s['id']] ?? null; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo Security::clean($s['full_name']); ?></strong>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" max="<?php echo $exam['max_marks']; ?>" 
                                               name="marks[<?php echo $s['id']; ?>]" 
                                               class="form-control border-primary-subtle" 
                                               value="<?php echo $mark ? $mark['marks_obtained'] : ''; ?>" 
                                               placeholder="0.00">
                                    </td>
                                    <td>
                                        <input type="text" name="comments[<?php echo $s['id']; ?>]" 
                                               class="form-control border-light" 
                                               value="<?php echo $mark ? Security::clean($mark['teacher_comment']) : ''; ?>" 
                                               placeholder="Enter remarks...">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-end py-3">
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i> Save Marks
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
