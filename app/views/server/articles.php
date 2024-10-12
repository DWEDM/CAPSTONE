<?php
session_start();

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>
<?php include "../app/views/partials/adminheader.php" ?>
<!-- Include Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<body style="background-color: gray;">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Toggle Switch -->
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="toggleSwitch" onclick="toggleView()">
            <label class="custom-control-label" for="toggleSwitch" id="toggleSwitchLabel">Show Articles</label>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="addArticleBtn" class="btn btn-primary" data-toggle="modal" data-target="#createArticleModal">Add New Article</button>
        <button id="addCategoryBtn" class="btn btn-secondary" data-toggle="modal" data-target="#createCategoryModal" style="display: none;">Add New Category</button>
    </div>

    <!-- Articles Table -->
    <div id="articlesTable" style="display: block;">
        <table class="table table-striped mt-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 5px; overflow: hidden;">
            <h2>Articles List</h2>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
            <?php if (empty($articles)) { ?>
                <tr>
                    <td colspan="7" class="text-center">No articles found! Please add an article!</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($articles as $article) { ?>
                    <tr>
                        <td>
                            <?php if (!empty($article->article_thumbnail)) { ?>
                                <img src="<?= htmlspecialchars($article->article_thumbnail) ?>" alt="Thumbnail" style="width: 50px; height: auto;">
                            <?php } else { ?>
                                <span>No Image</span>
                            <?php } ?>
                        </td>
                        <td><?= htmlspecialchars($article->article_title) ?></td>
                        <td><?= htmlspecialchars(substr($article->article_content, 0, 35)) ?>...</td>
                        <td><?= htmlspecialchars($article->created_at) ?></td>
                        <td><?= htmlspecialchars($article->updated_at) ?></td>
                        <td>
                            <?php
                            $categoryName = '';
                            foreach ($categories as $category) {
                                if ($category->category_id == $article->category_id) {
                                    $categoryName = htmlspecialchars($category->category_name);
                                    break;
                                }
                            }
                            echo $categoryName ? $categoryName : 'Unknown Category'; ?>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editArticleModal<?= $article->article_id ?>">
                                <i class="fas fa-edit"></i> <!-- Edit Icon -->
                            </button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteArticleModal<?= $article->article_id ?>">
                                <i class="fas fa-trash-alt"></i> <!-- Delete Icon -->
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Article Modal -->
                    <div class="modal fade" id="editArticleModal<?= $article->article_id ?>" tabindex="-1" role="dialog" aria-labelledby="editArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editArticleModalLabel">Edit Article</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= SERVER ?>/editArticle/<?= $article->article_id ?>" method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label for="">Title</label>
                                            <input type="text" name="article_title" value="<?= htmlspecialchars($article->article_title) ?>" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <label for="">Content</label>
                                            <textarea name="article_content" class="form-control" required><?= htmlspecialchars($article->article_content) ?></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label for="">Category</label>
                                            <select name="category_id" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category) { ?>
                                                    <option value="<?= htmlspecialchars($category->category_id) ?>" <?= $category->category_id == $article->category_id ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($category->category_name) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <label for="">Thumbnail</label>
                                            <input type="file" name="edit_thumbnail" class="form-control" accept="image/*" onchange="previewImage(event, 'editPreview<?= $article->article_id ?>')">
                                            <img id="editPreview<?= $article->article_id ?>" src="<?= htmlspecialchars($article->article_thumbnail) ?>" alt="Thumbnail Preview" style="display: block; margin-top: 10px; width: 100px; height: auto;">
                                        </div>
                                        <div class="mb-2">
                                            <label for="">Created At</label>
                                            <input type="text" value="<?= htmlspecialchars($article->created_at) ?>" class="form-control" readonly>
                                        </div>
                                        <div class="mb-2">
                                            <label for="">Updated At</label>
                                            <input type="text" name="updated_at" value="<?= date('Y-m-d H:i:s') ?>" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Article Modal -->
                    <div class="modal fade" id="deleteArticleModal<?= $article->article_id ?>" tabindex="-1" role="dialog" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteArticleModalLabel">Delete Article</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= SERVER ?>/deleteArticle/<?= $article->article_id ?>" method="POST">
                                    <div class="modal-body text-center">
                                        <p>Are you sure you want to delete this article?</p>
                                        <p><strong>Title:</strong> <?= htmlspecialchars($article->article_title) ?></p>

                                        <!-- Display the thumbnail if it exists -->
                                        <?php if (!empty($article->article_thumbnail)) { ?>
                                            <p><strong>Thumbnail:</strong></p>
                                            <img src="<?= htmlspecialchars($article->article_thumbnail) ?>" alt="Thumbnail" style="width: 150px; height: auto; margin-bottom: 10px;">
                                        <?php } else { ?>
                                            <p><strong>Thumbnail:</strong> No image available</p>
                                        <?php } ?>

                                        <input type="hidden" name="id" value="<?= $article->article_id ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php } ?>
        </table>
    </div>

    <!-- Categories Table -->
    <div id="categoriesTable" style="display: none;">
        <table class="table table-striped mt-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 5px; overflow: hidden;">
            <h2>Categories List</h2>
            <tr>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
            <?php if (empty($categories)) { ?>
                <tr>
                    <td colspan="2" class="text-center">No categories found! Please add a category!</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($categories as $category) { ?>
                    <tr>
                        <td><?= htmlspecialchars($category->category_name) ?></td>
                        <td>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editCategoryModal<?= $category->category_id ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCategoryModal<?= $category->category_id ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Category Modal -->
                    <div class="modal fade" id="editCategoryModal<?= $category->category_id ?>" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= SERVER ?>/editCategory/<?= $category->category_id ?>" method="POST">
                                    <div class="modal-body">
                                        <div class="mb-2">
                                            <label for="">Category Name</label>
                                            <input type="text" name="category_name" value="<?= htmlspecialchars($category->category_name) ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Category Modal -->
                    <div class="modal fade" id="deleteCategoryModal<?= $category->category_id ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="<?= SERVER ?>/deleteCategory/<?= $category->category_id ?>" method="POST">
                                    <div class="modal-body text-center">
                                        <p>Are you sure you want to delete this category?</p>
                                        <p><strong>Category:</strong> <?= htmlspecialchars($category->category_name) ?></p>
                                        <input type="hidden" name="id" value="<?= $category->category_id ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php } ?>
        </table>
    </div>
</div>

<!-- Create Article Modal -->
<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createArticleModalLabel">Create New Article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= SERVER ?>/createArticle" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="">Title</label>
                        <input type="text" name="article_title" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="">Content</label>
                        <textarea name="article_content" class="form-control" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?= htmlspecialchars($category->category_id) ?>">
                                    <?= htmlspecialchars($category->category_name) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="">Thumbnail</label>
                        <input type="file" name="input_thumbnail" class="form-control" accept="image/*" onchange="previewImage(event, 'createPreview')">
                        <img id="createPreview" style="display: none; margin-top: 10px; width: 100px; height: auto;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Create New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= SERVER ?>/createCategory" method="POST">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="">Category Name</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label for="">Created At</label>
                        <input type="text" name="category_date_created" value="<?= date('Y-m-d H:i:s') ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event, previewId) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(previewId);
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
function toggleView() {
    const isChecked = document.getElementById("toggleSwitch").checked;
    const articlesTable = document.getElementById("articlesTable");
    const categoriesTable = document.getElementById("categoriesTable");
    const addArticleBtn = document.getElementById("addArticleBtn");
    const addCategoryBtn = document.getElementById("addCategoryBtn");
    const toggleSwitchLabel = document.getElementById("toggleSwitchLabel");

    if (isChecked) {
        articlesTable.style.display = "none";
        categoriesTable.style.display = "block";
        addArticleBtn.style.display = "none";
        addCategoryBtn.style.display = "block";
        toggleSwitchLabel.textContent = "Show Categories";
    } else {
        articlesTable.style.display = "block";
        categoriesTable.style.display = "none";
        addArticleBtn.style.display = "block";
        addCategoryBtn.style.display = "none";
        toggleSwitchLabel.textContent = "Show Articles";
    }
}
</script>

</body>
<?php include "../app/views/partials/footer.php" ?>