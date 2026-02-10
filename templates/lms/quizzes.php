<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-patch-question"></i> Online Assessments</h2>
        <button class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create New Quiz
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-card p-4">
            <h5 class="mb-4">Recent Quizzes</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Quiz Title</th>
                            <th>Subject</th>
                            <th>Duration</th>
                            <th>Passing Score</th>
                            <th>Status</th>
                            <th>Submissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($quizzes)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <p class="mb-0 text-muted">No quizzes created yet. Use the Quiz Builder to start.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($quizzes as $quiz): ?>
                                <tr>
                                    <td><strong><?php echo Security::clean($quiz['title']); ?></strong></td>
                                    <td><?php echo $quiz['subject_name']; ?></td>
                                    <td><?php echo $quiz['duration_minutes']; ?> mins</td>
                                    <td><?php echo $quiz['passing_score']; ?>%</td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td><span class="badge bg-light text-dark">24 Students</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View Results"><i class="bi bi-graph-up"></i></button>
                                            <button class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
