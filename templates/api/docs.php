<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-book"></i> API Documentation</h2>
        <p class="text-muted">Welcome to the School Management System REST API documentation. Use these endpoints to integrate your applications with our core services.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="list-group list-group-flush">
                <a href="#auth" class="list-group-item list-group-item-action fw-bold">Authentication</a>
                <a href="#students" class="list-group-item list-group-item-action fw-bold">Students API</a>
                <a href="#staff" class="list-group-item list-group-item-action fw-bold">Staff API</a>
                <a href="#exams" class="list-group-item list-group-item-action fw-bold">Exams & Grading</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card border-0 shadow-sm p-4">
            <section id="auth" class="mb-5">
                <h4 class="fw-bold border-bottom pb-2">Authentication</h4>
                <p>All API requests must include the following headers:</p>
                <div class="bg-light p-3 rounded">
                    <code>
                        X-API-KEY: your_api_key<br>
                        X-API-SECRET: your_api_secret
                    </code>
                </div>
            </section>

            <section id="students" class="mb-5">
                <h4 class="fw-bold border-bottom pb-2">Students API</h4>
                
                <div class="endpoint mb-4">
                    <h6 class="fw-bold"><span class="badge bg-success me-2">GET</span> <code>/api/students</code></h6>
                    <p class="small text-muted">Retrieve a list of all active students.</p>
                    <div class="bg-dark text-white p-3 rounded small">
                        <pre class="mb-0">
{
  "status": "success",
  "count": 150,
  "data": [
    { "id": 1, "full_name": "John Doe", "email": "john@example.com" }
  ]
}</pre>
                    </div>
                </div>

                <div class="endpoint">
                    <h6 class="fw-bold"><span class="badge bg-success me-2">GET</span> <code>/api/student_profile/{id}</code></h6>
                    <p class="small text-muted">Retrieve detailed profile information for a specific student.</p>
                </div>
            </section>

            <section id="staff" class="mb-5">
                <h4 class="fw-bold border-bottom pb-2">Staff API</h4>
                <div class="endpoint mb-4">
                    <h6 class="fw-bold"><span class="badge bg-success me-2">GET</span> <code>/api/staff</code></h6>
                    <p class="small text-muted">Retrieve a list of all staff members.</p>
                </div>
            </section>

            <section id="exams" class="mb-5">
                <h4 class="fw-bold border-bottom pb-2">Exams & Grading</h4>
                <div class="endpoint mb-4">
                    <h6 class="fw-bold"><span class="badge bg-success me-2">GET</span> <code>/api/exams</code></h6>
                    <p class="small text-muted">Retrieve all examination sessions.</p>
                    <div class="bg-dark text-white p-3 rounded small">
                        <pre class="mb-0">
{
  "status": "success",
  "data": [
    { "id": 1, "type_name": "Mid-Term", "subject_name": "Mathematics", "max_marks": 100 }
  ]
}</pre>
                    </div>
                </div>
            </section>

            <section id="rate-limits">
                <div class="alert alert-warning border-0">
                    <h6 class="fw-bold"><i class="bi bi-clock-history me-2"></i> Rate Limiting</h6>
                    Our API is limited to <strong>100 requests per minute</strong> per API Key.
                </div>
            </section>
        </div>
    </div>
</div>
