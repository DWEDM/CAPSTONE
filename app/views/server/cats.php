<?php
session_start();

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>
<?php include "../app/views/partials/adminheader.php" ?>
<body style="background-color: gray;">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Cats and Breeds</h2>
    <div>
        <button class="btn btn-secondary mr-2" id="showCats">Show Cats</button>
        <button class="btn btn-secondary" id="showBreeds">Show Breeds</button>
    </div>
  <button class="btn btn-primary" data-toggle="modal" data-target="#selectAddModal">Add New</button>
  </div>
  <div class="input-group" style="width: 250px;">
    <input type="text" id="searchInput" class="form-control" placeholder="Search Posts" aria-label="Search" onkeyup="searchUsers()">
  </div>

  <div id="catsTable" class="mt-3">
    <h1>Cats</h1>
    <table class="table table-striped">
      <tr>
        <th>Cat Profile</th>
        <th>Name</th>
        <th>Breed</th>
        <th>Description</th>
        <th>Cat Images</th>
        <th>Created By</th>
        <th>Actions</th>
      </tr>
      <?php if (empty($cats)) { ?>
        <tr>
          <td colspan="7" class="text-center">No Cats found! Please Add!</td>
        </tr>
      <?php } else { ?>
        <?php foreach ($cats as $catr) { ?>
            <tr>
              <td>
                <img src="<?= !empty($catr->cat_profile) ? $catr->cat_profile : '../assets/images/default_profile/defaultcat.png' ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #000; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); margin-bottom: 10px;">
              </td>
              <td><?= $catr->cat_name ?></td>
              <td>
                <?php
                // Find the breed name corresponding to the breed_id
                $breedName = '';
                foreach ($breeds as $breed) {
                    if ($breed->breed_id == $catr->breed_id) {
                        $breedName = $breed->breed_name;
                        break; // Exit the loop once found
                    }
                }
                echo $breedName;
                ?>
              </td>
              <td><?= $catr->cat_description ?></td>
              <td> <!-- For Cat Related Images --></td>
              <td><?= $catr->created_by ?></td>
              <td>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editCatModal<?= $catr->cat_id ?>">Edit</button>
                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCatModal<?= $catr->cat_id ?>">Delete</button>
              </td>
            </tr>
            <!-- Edit Cat Modal -->
            <div class="modal fade" id="editCatModal<?= $catr->cat_id ?>" tabindex="-1" role="dialog" aria-labelledby="editCatModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editCatModalLabel">Edit Cat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="<?= SERVER ?>/editcat/<?= $catr->cat_id ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                      <div class="mb-2 text-center">
                        <img id="editCatImagePreview<?= $catr->cat_id ?>" src="<?= !empty($catr->cat_profile) ? $catr->cat_profile : '../assets/images/default_profile/defaultcat.png' ?>" alt="Cat Profile Image" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
                        <br>
                        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProfileImage('editCatImagePreview<?= $catr->cat_id ?>')">Remove</button>
                      </div>
                      <div>
                        <label for="editCatProfileImage">Cat Profile Image</label>
                        <input type="file" name="cat_profile_image" class="form-control" accept="image/*" onchange="previewEditCatImage(event, 'editCatImagePreview<?= $catr->cat_id ?>')">
                      </div>
                      <div class="mb-2">
                        <label for="catName">Cat Name</label>
                        <input type="text" name="cat_name" value="<?= $catr->cat_name ?>" class="form-control" required>
                      </div>
                      <div class="mb-2">
                        <label for="breedId">Breed</label>
                        <select name="breed_id" class="form-control" required>
                          <option value="">Select Breed</option>
                          <?php foreach ($breeds as $breed) { ?>
                            <option value="<?= $breed->breed_id ?>" <?= $breed->breed_id == $catr->breed_id ? 'selected' : '' ?>><?= $breed->breed_name ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="mb-2">
                        <label for="catDescription">Description</label>
                        <textarea name="cat_description" class="form-control" required><?= $catr->cat_description ?></textarea>
                      </div>
                      <div class="mb-2">
                        <label for="catImages">Cat Related Images</label>
                        <input type="file" name="cat_image_url[]" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
                      </div>
                      <div id="editCatImagePreviews<?= $catr->cat_id ?>" style="display: flex; flex-wrap: wrap; margin-top: 10px;">
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

            <!-- Modal for Deleting Cat -->
            <div class="modal fade" id="deleteCatModal<?= $catr->cat_id ?>" tabindex="-1" role="dialog" aria-labelledby="deleteCatModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCatModalLabel">Delete Cat</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?= SERVER ?>/delete_cat/<?= $catr->cat_id ?>" method="POST">
                            <div class="modal-body text-center">
                                <img src="<?= !empty($catr->cat_profile) ? $catr->cat_profile : '../assets/images/default_profile/defaultcat.png' ?>" alt="Profile Image" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
                                <p><strong>Cat Name:</strong> <?= $catr->cat_name ?></p>
                                <?php
                                // Find the breed name corresponding to the breed_id
                                $deleteBreedName = '';
                                foreach ($breeds as $breed) {
                                    if ($breed->breed_id == $catr->breed_id) {
                                        $deleteBreedName = $breed->breed_name;
                                        break; // Exit the loop once found
                                    }
                                }
                                ?>
                                <p><strong>Breed:</strong> <?= $deleteBreedName ?></p>
                                <p><strong>Description:</strong> <?= $catr->cat_description ?></p>
                                <p><strong>Cat Images:</strong></p>
                                <input type="hidden" name="id" value="<?= $catr->cat_id ?>">
                                <p>Are you sure you want to delete this cat?</p>
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

  <div id="breedsTable" class="mt-3" style="display: none;">
    <h1>Breeds</h1>
    <table class="table table-striped">
      <tr>
        <th>Breed Name</th>
        <th>Description</th>
        <th>Average Lifespan</th>
        <th>Origin</th>
        <th>Actions</th>
      </tr>
      <?php if (empty($breeds)) { ?>
        <tr>
          <td colspan="5" class="text-center">No Breeds found! Please Add!</td>
        </tr>
      <?php } else { ?>
        <?php foreach ($breeds as $breed) { ?>
            <tr>
              <td><?= $breed->breed_name ?></td>
              <td><?= $breed->breed_description ?></td>
              <td><?= $breed->average_lifespan ?></td>
              <td><?= $breed->origin ?></td>
              <td>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editBreedModal<?= $breed->breed_id ?>">Edit</button>
                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteBreedModal<?= $breed->breed_id ?>">Delete</button>
              </td>
            </tr>
            <!-- Edit Breed Modal -->
            <div class="modal fade" id="editBreedModal<?= $breed->breed_id ?>" tabindex="-1" role="dialog" aria-labelledby="editBreedModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBreedModalLabel">Edit Breed</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?= SERVER ?>/editbreed/<?= $breed->breed_id ?>" method="POST">
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label for="breedName">Breed Name</label>
                                    <input type="text" name="breed_name" value="<?= $breed->breed_name ?>" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="breedDescription">Description</label>
                                    <textarea name="breed_description" class="form-control" required><?= $breed->breed_description ?></textarea>
                                </div>
                                <div class="mb-2">
                                    <label for="averageLifespan">Average Lifespan</label>
                                    <input type="text" name="average_lifespan" value="<?= $breed->average_lifespan ?>" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="size">Origin</label>
                                    <input type="text" name="origin" value="<?= $breed->origin ?>" class="form-control" required>
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

            <!-- Delete Breed Modal -->
            <div class="modal fade" id="deleteBreedModal<?= $breed->breed_id ?>" tabindex="-1" role="dialog" aria-labelledby="deleteBreedModalLabel<?= $breed->breed_id ?>" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="deleteBreedModalLabel">Delete Breed</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <form action="<?= SERVER ?>/delete_breed/<?= $breed->breed_id ?>" method="POST">
                          <div class="modal-body text-center">
                              <p><strong>Breed Name:</strong> <?= $breed->breed_name ?></p>
                              <p><strong>Description:</strong> <?= $breed->breed_description ?></p>
                              <p>Are you sure you want to delete this breed?</p>
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

<!-- Cat or Breed Modal -->
<div class="modal fade" id="selectAddModal" tabindex="-1" role="dialog" aria-labelledby="selectAddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="selectAddModalLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Select an option to add:</p>
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#createCatModal">Add New Cat</button>
        <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#createBreedModal">Add New Breed</button>
      </div>
    </div>
  </div>
</div>

<!-- Add New Breed Modal -->
<div class="modal fade" id="createBreedModal" tabindex="-1" role="dialog" aria-labelledby="createBreedModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createBreedModalLabel">Add New Breed</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= SERVER ?>/createbreed" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="breedName">Breed Name</label>
            <input type="text" class="form-control" id="breedName" name="breed_name" required>
          </div>
          <div class="form-group">
            <label for="breedDescription">Description</label>
            <textarea class="form-control" id="breedDescription" name="breed_description"></textarea>
          </div>
          <div class="form-group">
            <label for="averageLifespan">Average Lifespan (years)</label>
            <input type="number" class="form-control" id="averageLifespan" name="average_lifespan">
          </div>
          <div class="form-group">
            <label for="breedOrigin">Origin</label>
            <input type="text" class="form-control" id="breedOrigin" name="origin">
          </div>
          <button type="submit" class="btn btn-primary">Add Breed</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add New Cat Modal -->
<div class="modal fade" id="createCatModal" tabindex="-1" role="dialog" aria-labelledby="createCatModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCatModalLabel">Add New Cat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?= SERVER ?>/createcat" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="catName">Cat Name</label>
            <input type="text" class="form-control" id="catName" name="cat_name" required>
          </div>
          <div class="form-group">
            <label for="catProfileImage">Cat Profile Image</label>
            <input type="file" class="form-control" id="catProfileImage" name="cat_profile_image" accept="image/*" onchange="previewProfileImage(event)">
          </div>
          <div id="profileImagePreview" style="margin-top: 10px;">
            <img id="profileImage" src="../assets/images/default_profile/defaultcat.png" alt="Profile Image Preview" style="width: 100px; height: auto;"/>
          </div>
          <div class="form-group">
            <label for="breedId">Breed</label>
            <select class="form-control" id="breedId" name="breed_id" required>
              <option value="">Select Breed</option>
              <?php foreach ($breeds as $breed) { ?>
                <option value="<?= $breed->breed_id ?>"><?= $breed->breed_name ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="catImage">Cat Related Images</label>
            <input type="file" class="form-control" id="catImage" name="cat_image_url[]" accept="image/*" multiple onchange="previewImages(event)">
          </div>
          <div id="imagePreviews" style="display: flex; flex-wrap: wrap; margin-top: 10px;">
            <img id="imagePreview" src="../assets/images/default_profile/defaultcat.png" alt="Image Preview" style="width: 100px; height: auto; margin-right: 10px;"/>
          </div>
          <div class="form-group">
            <label for="catDescription">Description</label>
            <textarea class="form-control" id="catDescription" name="cat_description" required></textarea>
          </div>
          <!-- Hidden input for created by -->
          <input type="hidden" name="created_by" value="<?= $_SESSION['username'] ?>">
          <button type="submit" class="btn btn-primary">Add Cat</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
<?php include "../app/views/partials/footer.php" ?>

<script>
document.getElementById('showCats').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    document.getElementById('catsTable').style.display = 'block';
    document.getElementById('breedsTable').style.display = 'none';
});

document.getElementById('showBreeds').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    document.getElementById('catsTable').style.display = 'none';
    document.getElementById('breedsTable').style.display = 'block';
});

function previewImages(event) {
    const imagePreviewsContainer = document.getElementById('imagePreviews');
    imagePreviewsContainer.innerHTML = ''; // Clear previous previews

    if (event.target.files) {
        Array.from(event.target.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.width = '100px';
            img.style.height = 'auto';
            img.style.marginRight = '10px';
            imagePreviewsContainer.appendChild(img);
        });
    } else {
        // Show default image if no file selected
        const defaultImg = document.createElement('img');
        defaultImg.src = '../assets/images/default_profile/defaultcat.png';
        defaultImg.style.width = '100px';
        defaultImg.style.height = 'auto';
        imagePreviewsContainer.appendChild(defaultImg);
    }
}

function previewProfileImage(event) {
    const profileImagePreview = document.getElementById('profileImage');
    if (event.target.files && event.target.files[0]) {
        profileImagePreview.src = URL.createObjectURL(event.target.files[0]);
    } else {
        profileImagePreview.src = '../assets/images/default_profile/defaultcat.png'; // Reset to default
    }
}
</script>