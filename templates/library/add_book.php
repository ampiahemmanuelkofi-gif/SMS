<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-plus-circle-fill"></i> Add New Book</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="<?php echo BASE_URL; ?>library/add_book" method="POST" class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Author(s)</label>
                        <input type="text" name="author" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Publisher</label>
                        <input type="text" name="publisher" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo Security::clean($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Resource Type</label>
                        <select name="type" class="form-select">
                            <option value="book">Physical Book</option>
                            <option value="ebook">E-Book</option>
                            <option value="journal">Journal / Periodical</option>
                            <option value="media">Media (CD/DVD)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Initial Copies</label>
                        <input type="number" name="copies" class="form-control" value="1" min="1">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Storage Location (Shelf / Room)</label>
                        <input type="text" name="location_shelf" class="form-control" placeholder="e.g., Shelf A-102">
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <hr>
                        <a href="<?php echo BASE_URL; ?>library" class="btn btn-light px-4 me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">Add to Catalog</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
