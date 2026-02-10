<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title">Manage Timetable</h2>
        <a href="<?php echo BASE_URL; ?>timetable?class_id=<?php echo $selectedClassId; ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to View
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <label class="form-label">Managing Schedule For:</label>
        <form action="<?php echo BASE_URL; ?>timetable/manage" method="GET">
            <select name="class_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Select Class --</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo $class['id']; ?>" <?php echo $selectedClassId == $class['id'] ? 'selected' : ''; ?>>
                        <?php echo Security::clean($class['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<?php if ($selectedClassId): ?>
    <div class="row">
        <div class="col-md-4">
            <!-- Add Entry Form -->
            <div class="table-card mb-4">
                <h5 class="border-bottom pb-2 mb-3">Add Schedule Entry</h5>
                <form action="<?php echo BASE_URL; ?>timetable/add_entry" method="POST">
                    <input type="hidden" name="class_id" value="<?php echo $selectedClassId; ?>">
                    <input type="hidden" name="academic_year_id" value="1"> <!-- TODO: dynamic -->
                    <input type="hidden" name="term_id" value="1"> <!-- TODO: dynamic -->
                    
                    <div class="mb-3">
                        <label class="form-label">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">Select Subject...</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>"><?php echo Security::clean($subject['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Room (Optional)</label>
                        <select name="room_id" class="form-select">
                            <option value="">No Room Assigned</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['id']; ?>"><?php echo Security::clean($room['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Teacher (Optional)</label>
                        <!-- In a real app, this should be filtered by subject assignment -->
                        <input type="number" name="teacher_id" class="form-control" placeholder="Teacher User ID (Temp)">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Add Entry</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Entries added here will automatically appear on the class timetable. The system checks for room and teacher conflicts.
            </div>
        </div>
    </div>
<?php endif; ?>
