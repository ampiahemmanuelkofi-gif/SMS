<div class="row mb-4">
    <div class="col-md-12 d-flex justify-content-between align-items-center">
        <h2 class="page-title"><i class="bi bi-book"></i> Library Catalog</h2>
        <?php if (Auth::hasRole(['super_admin', 'admin'])): ?>
            <a href="<?php echo BASE_URL; ?>library/add_book" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Book
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>library" method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search by Title, Author or ISBN..." value="<?php echo Security::clean($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo Security::clean($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($books as $book): ?>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm book-card">
                <div class="row g-0 h-100">
                    <div class="col-4 bg-light d-flex align-items-center justify-content-center border-end">
                        <?php if ($book['cover_image']): ?>
                            <img src="<?php echo ASSETS_URL; ?>img/books/<?php echo $book['cover_image']; ?>" class="img-fluid rounded-start" alt="Cover">
                        <?php else: ?>
                            <i class="bi bi-book fs-1 text-muted"></i>
                        <?php endif; ?>
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h6 class="card-title text-truncate mb-1" title="<?php echo Security::clean($book['title']); ?>">
                                <?php echo Security::clean($book['title']); ?>
                            </h6>
                            <p class="card-text small text-muted mb-2">by <?php echo Security::clean($book['author']); ?></p>
                            <div class="mb-2">
                                <span class="badge bg-secondary-subtle text-secondary small">
                                    <?php echo Security::clean($book['category_name']); ?>
                                </span>
                                <span class="badge <?php echo $book['available_copies'] > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'; ?> small">
                                    <?php echo $book['available_copies']; ?> / <?php echo $book['total_copies']; ?> Available
                                </span>
                            </div>
                            <p class="card-text small mb-0"><strong>ISBN:</strong> <?php echo Security::clean($book['isbn']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($books)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 text-muted"></i>
            <p class="mt-2">No books found matching your criteria.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.book-card {
    transition: transform 0.2s;
}
.book-card:hover {
    transform: translateY(-5px);
}
</style>
