

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Assessment Setup</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grading Systems</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($gradingSystems as $system): ?>
                                <tr>
                                    <td><?php echo $system['name']; ?></td>
                                    <td><?php echo $system['description']; ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>assessments/setup/scales/<?php echo $system['id']; ?>" class="btn btn-sm btn-info">
                                            Manage Scales
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="<?php echo BASE_URL; ?>assessments/setup/weights" class="btn btn-primary btn-block mb-3">
                        <i class="bi bi-sliders"></i> Configure Subject Weights
                    </a>
                    <a href="<?php echo BASE_URL; ?>assessments/setup/categories" class="btn btn-secondary btn-block mb-3">
                        <i class="bi bi-tags"></i> Manage Assessment Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
