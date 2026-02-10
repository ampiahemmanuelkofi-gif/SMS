<div class="row justify-content-center">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4 bg-primary text-white p-4">
            <h4 class="fw-bold mb-1">Module Activation Matrix</h4>
            <p class="mb-0 opacity-75">Select your school type to instantly activate the recommended modules and features.</p>
        </div>
    </div>

    <!-- Profile Cards -->
    <?php 
    $profiles = [
        'small_private' => [
            'title' => 'Small Private (Primary)',
            'icon' => 'bi-house-heart',
            'desc' => 'Essential SIS features for private primary schools including Parent Portal and Fees.',
            'modules' => ['Core SIS', 'Attendance', 'Fees', 'Communication', 'Library', 'Transport']
        ],
        'medium_basic' => [
            'title' => 'Medium Basic (JHS)',
            'icon' => 'bi-building',
            'desc' => 'Adds Assessment, Discipline, and basic resource management for Junior High levels.',
            'modules' => ['All Primary +', 'Assessments', 'Library', 'Discipline', 'Hostel', 'Cafeteria']
        ],
        'large_boarding' => [
            'title' => 'Large SHS/Boarding',
            'icon' => 'bi-building-fill-check',
            'desc' => 'Comprehensive management for large secondary and boarding institutions.',
            'modules' => ['All Basic +', 'Medical', 'HR & Payroll', 'Inventory', 'Asset Tracking']
        ],
        'school_group' => [
            'title' => 'School Group/Chain',
            'icon' => 'bi-diagram-3-fill',
            'desc' => 'Multi-branch support and advanced integrations for educational groups.',
            'modules' => ['All Boarding +', 'Multi-Branch', 'API Platform', 'AI Automation', 'LMS']
        ],
        'international' => [
            'title' => 'International School',
            'icon' => 'bi-globe',
            'desc' => 'Specialized management for international curriculum and global standardized workflows.',
            'modules' => ['All Group +', 'Admissions CRM', 'Agent Management', 'Multi-Curriculum']
        ]
    ];
    ?>

    <?php foreach ($profiles as $key => $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm <?php echo ($currentProfile === $key) ? 'ring-primary' : ''; ?>">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar bg-soft-primary text-primary me-3 p-3 rounded">
                            <i class="bi <?php echo $p['icon']; ?> fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-0"><?php echo $p['title']; ?></h6>
                    </div>
                    <p class="small text-muted mb-4"><?php echo $p['desc']; ?></p>
                    
                    <div class="mb-4">
                        <h7 class="fw-bold x-small text-uppercase text-muted d-block mb-2">Key Modules</h7>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($p['modules'] as $mod): ?>
                                <span class="badge bg-light text-dark border x-small"><?php echo $mod; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <form action="<?php echo BASE_URL; ?>admin/profiles" method="POST">
                        <input type="hidden" name="school_profile" value="<?php echo $key; ?>">
                        <button type="submit" class="btn <?php echo ($currentProfile === $key) ? 'btn-success disabled' : 'btn-outline-primary'; ?> w-100" 
                            <?php echo ($currentProfile === $key) ? 'disabled' : ''; ?>>
                            <?php echo ($currentProfile === $key) ? '<i class="bi bi-check-circle me-1"></i> Active Profile' : 'Apply Configuration'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .ring-primary { border: 2px solid var(--bs-primary) !important; }
    .bg-soft-primary { background-color: #f0f4ff; }
    .x-small { font-size: 10px; }
</style>
