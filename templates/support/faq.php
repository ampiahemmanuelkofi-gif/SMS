<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Knowledge Base</h2>
            <p class="text-muted">Find quick answers to your questions about the platform.</p>
        </div>

        <div class="accordion accordion-flush card border-0 shadow-sm overflow-hidden" id="faqAccordion">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq-<?php echo $faq['id']; ?>">
                            <div class="me-3">
                                <span class="badge bg-soft-primary text-primary"><?php echo $faq['category']; ?></span>
                            </div>
                            <span class="fw-bold"><?php echo $faq['question']; ?></span>
                        </button>
                    </h2>
                    <div id="faq-<?php echo $faq['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body py-4 bg-light">
                            <p class="mb-0 text-muted lh-lg"><?php echo nl2br($faq['answer']); ?></p>
                            <div class="mt-4 pt-3 border-top d-flex align-items-center">
                                <span class="small text-muted me-3">Was this helpful?</span>
                                <button class="btn btn-sm btn-outline-success me-2 px-3"><i class="bi bi-hand-thumbs-up me-1"></i> Yes</button>
                                <button class="btn btn-sm btn-outline-danger px-3"><i class="bi bi-hand-thumbs-down me-1"></i> No</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Video Tutorials Section (Placeholder) -->
        <div class="mt-5 pt-4">
            <h5 class="fw-bold mb-4 d-flex align-items-center">
                <i class="bi bi-play-circle-fill text-danger fs-3 me-2"></i> Video Tutorial Library
            </h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="bg-dark rounded-top text-center py-5">
                            <i class="bi bi-play-fill text-white fs-1"></i>
                        </div>
                        <div class="card-body">
                            <h7 class="fw-bold d-block mb-1">Getting Started Guide</h7>
                            <small class="text-muted">Duration: 4:32</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="bg-secondary rounded-top text-center py-5">
                            <i class="bi bi-play-fill text-white fs-1"></i>
                        </div>
                        <div class="card-body">
                            <h7 class="fw-bold d-block mb-1">Marking Attendance Hub</h7>
                            <small class="text-muted">Duration: 2:15</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="bg-dark rounded-top text-center py-5">
                            <i class="bi bi-play-fill text-white fs-1"></i>
                        </div>
                        <div class="card-body">
                            <h7 class="fw-bold d-block mb-1">Exam & Grading Setup</h7>
                            <small class="text-muted">Duration: 5:40</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: #e8eaf6; }
    .accordion-button:not(.collapsed) { background-color: #fff; color: var(--bs-primary); box-shadow: none; }
    .accordion-button:focus { box-shadow: none; }
</style>
