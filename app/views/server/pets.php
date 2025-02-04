<?php
session_start();

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>
<?php include "../app/views/partials/adminheader.php" ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body style="background-color: gray;">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center">
    <div>
        <button class="btn btn-secondary mr-2" id="showPets">Show Pets</button>
        <button class="btn btn-secondary" id="showBreeds">Show Breeds</button>
        <button class="btn btn-secondary" id="showSpecies">Show Species</button>
    </div>
  <button class="btn btn-primary" data-toggle="modal" data-target="#selectAddModal">Add New</button>
  </div>
  <div class="input-group" style="width: 250px;">
    <input type="text" id="searchInput" class="form-control" placeholder="Search Posts" aria-label="Search" onkeyup="searchUsers()">
  </div>

  <div id="petsTable" class="mt-3">
    <h1>Pets</h1>
    <table class="table table-striped">
      <tr>
        <th>Pet Profile</th>
        <th>Name</th>
        <th>Species</th>
        <th>Breed</th>
        <th>Description</th>
        <th>Pet Images</th>
        <th>Created By</th>
        <th>Actions</th>
      </tr>
      <?php if (empty($pets)) { ?>
        <tr>
          <td colspan="8" class="text-center">No Pets found! Please Add!</td>
        </tr>
      <?php } else { ?>
        <?php foreach ($pets as $petr) { ?>
            <tr>
              <td>
                <img src="<?= !empty($petr->pet_profile) ? $petr->pet_profile : '../assets/images/default_profile/defaultcat.png' ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #000; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); margin-bottom: 10px;">
              </td>
              <td><?= $petr->pet_name ?></td>
              <td>
                <?php
                // Find the species corresponding to the species_id
                $speciesName = '';
                foreach ($species as $speciesN) {
                    if ($speciesN->species_id == $petr->species_id) { // Compare with $petr->species instead of $petr->pet_id
                        $speciesName = $speciesN->species_name;
                        break; // Exit the loop once found
                    }
                }
                echo $speciesName;
                ?>
              </td>
              <td>
                <?php
                // Find the breed name corresponding to the breed_id
                $breedName = '';
                foreach ($breeds as $breed) {
                    if ($breed->breed_id == $petr->breed_id) {
                        $breedName = $breed->breed_name;
                        break; // Exit the loop once found
                    }
                }
                echo $breedName;
                ?>
              </td>
              
              <td><?= $petr->pet_description ?></td>
              <td>No Image <!-- For Pet Related Images --></td>
              <td><?= $petr->created_by?></td>
              <td>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editPetModal<?= $petr->pet_id ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeletePet('<?= $petr->pet_id ?>')" title="Delete Pet">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <!-- Edit Cat Modal -->
            <div class="modal fade" id="editPetModal<?= $petr->pet_id ?>" tabindex="-1" role="dialog" aria-labelledby="editCatModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editCatModalLabel">Edit Pet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="editPetForm<?= $petr->pet_id ?>" action="<?= SERVER ?>/editPet/<?= $petr->pet_id ?>" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); editPet(<?= $petr->pet_id ?>);">
                    <div class="modal-body">
                      <div class="mb-2 text-center">
                        <img id="editPetImagePreview<?= $petr->pet_id ?>" src="<?= !empty($petr->pet_profile) ? $petr->pet_profile : '../assets/images/default_profile/defaultcat.png' ?>" alt="Pet Profile Image" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
                        <br>
                        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProfileImage('editPetImagePreview<?= $petr->pet_id ?>')">Remove</button>
                      </div>
                      <div>
                        <label for="editPetProfileImage">Pet Profile Image</label>
                        <input type="file" name="pet_profile_image" class="form-control" accept="image/*" onchange="previewEditPetImage(event, 'editPetImagePreview<?= $petr->pet_id ?>')">
                      </div>
                      <div class="mb-2">
                        <label for="petName">Pet Name</label>
                        <input type="text" name="pet_name" value="<?= $petr->pet_name ?>" class="form-control" required>
                      </div>
                      <div class="mb-2">
                        <label for="breedId">Breed</label>
                        <select name="breed_id" class="form-control" required>
                          <option value="">Select Breed</option>
                          <?php foreach ($breeds as $breed) { ?>
                            <option value="<?= $breed->breed_id ?>" <?= $breed->breed_id == $petr->breed_id ? 'selected' : '' ?>><?= $breed->breed_name ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="mb-2">
                        <label for="SpeciesId">Species</label>
                        <select name="species_id" class="form-control" required>
                          <option value="">Select Species</option>
                          <?php foreach ($species as $speciesN) { ?>
                            <option value="<?= $speciesN->species_id ?>" <?= $speciesN->species_id == $petr->species_id ? 'selected' : '' ?>><?= $speciesN->species_name ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="mb-2">
                        <label for="catDescription">Pet Description</label>
                        <textarea name="pet_description" class="form-control" required><?= $petr->pet_description ?></textarea>
                      </div>
                      <div class="mb-2">
                        <label for="catImages">Pet Related Images</label>
                        <input type="file" name="pet_image_url[]" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
                      </div>
                      <div id="editPetImagePreviews<?= $petr->pet_id ?>" style="display: flex; flex-wrap: wrap; margin-top: 10px;">
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
        <th>Species</th>
        <th>Actions</th>
      </tr>
      <?php if (empty($breeds)) { ?>
        <tr>
          <td colspan="6" class="text-center">No Breeds found! Please Add!</td>
        </tr>
      <?php } else { ?>
        <?php foreach ($breeds as $breed) { ?>
            <tr>
              <td><?= $breed->breed_name ?></td>
              <td>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#descriptionModal<?= $breed->breed_id ?>">
                    View Description
                </button>
              </td>
              <td><?= $breed->average_lifespan ?></td>
              <td><?= $breed->origin ?></td>
              <td>
                <?=
                $speciesName = '';
                foreach ($species as $speciesN) {
                    if ($speciesN->species_id == $breed->species_id) { // Compare with $petr->species instead of $petr->pet_id
                        $speciesName = $speciesN->species_name;
                        break; // Exit the loop once found
                    }
                }
                echo $speciesName;
                ?>
              </td>
              <td>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editBreedModal<?= $breed->breed_id ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteBreed('<?= $breed->breed_id ?>')" title="Delete Breed">
                  <i class="fas fa-trash-alt"></i>
                </button>
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
                        <form id="editBreedForm<?= $breed->breed_id ?>" action="<?= SERVER ?>/editbreed/<?= $breed->breed_id ?>" method="POST" onsubmit="event.preventDefault(); editBreed(<?= $breed->breed_id ?>);">
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
                                <div class="mb-2">
                                  <label for="SpeciesId">Species</label>
                                  <select name="species_id" class="form-control" required>
                                    <option value="">Select Species</option>
                                    <?php foreach ($species as $speciesN) { ?>
                                      <option value="<?= $speciesN->species_id ?>" <?= $speciesN->species_id == $breed->species_id ? 'selected' : '' ?>><?= $speciesN->species_name ?></option>
                                    <?php } ?>
                                  </select>
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
          <!-- Description Modal -->
          <div class="modal fade" id="descriptionModal<?= $breed->breed_id ?>" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel<?= $breed->breed_id ?>" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="descriptionModalLabel<?= $breed->breed_id ?>">Breed Description: <?= $breed->breed_name ?></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <p><?= $breed->breed_description ?></p>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
            <?php } ?>
        <?php } ?>
    </table>
  </div>
  <div id="speciesTable" class="mt-3" style="display: none;">
    <h1>Species</h1>
    <table class="table table-striped">
      <tr>
        <th>Species Name</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
      <?php if (empty($species)) { ?>
        <tr>
          <td colspan="5" class="text-center">No Species found! Please Add!</td>
        </tr>
      <?php } else { ?>
        <?php foreach ($species as $speciesN) { ?>
            <tr>
              <td><?= $speciesN->species_name ?></td>
              <td>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#speciesDescriptionModal<?= $speciesN->species_id ?>">
                    View Description
                </button>
              </td>
              <td>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editSpeciesModal<?= $speciesN->species_id ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="confirmDeleteSpecies('<?= $speciesN->species_id ?>')" title="Delete Species">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
            <!-- Edit Species Modal -->
            <div class="modal fade" id="editSpeciesModal<?= $speciesN->species_id ?>" tabindex="-1" role="dialog" aria-labelledby="editSpeciesModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSpeciesModalLabel">Edit Species</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editSpeciesForm<?= $speciesN->species_id ?>" action="<?= SERVER ?>/editspecies/<?= $speciesN->species_id ?>" method="POST" onsubmit="event.preventDefault(); editSpecies(<?= $speciesN->species_id ?>);">
                            <div class="modal-body">
                                <div class="mb-2">
                                  <label for="speciesName">Species Name</label>
                                  <input type="text" name="species_name" value="<?= $speciesN->species_name ?>" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                  <label for="speciesDescription">Description</label>
                                  <input type="text" name="species_description" value="<?= $speciesN->species_description ?>" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                  <label for="speciesReference">Reference</label>
                                  <input type="text" name="species_reference" value="<?= $speciesN->species_reference ?>" class="form-control" required>
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
          <!-- Description Modal -->
          <div class="modal fade" id="speciesDescriptionModal<?= $speciesN->species_id ?>" tabindex="-1" role="dialog" aria-labelledby="speciesDescriptionModalLabel<?= $speciesN->species_id ?>" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="descriptionModalLabel<?= $speciesN->species_id ?>">Breed Description: <?= $speciesN->species_name ?></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <p><?= $speciesN->species_description ?></p>
                          <hr>
                          <p><?= $speciesN->species_reference ?></p>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
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
        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#createPetModal">Add New Pet</button>
        <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#createSpeciesModal">Add New Species</button>
        <button class="btn btn-secondary btn-block" data-toggle="modal" data-target="#createBreedModal">Add New Breed</button>
      </div>
    </div>
  </div>
</div>

<!-- Add New Species Modal -->
<div class="modal fade" id="createSpeciesModal" tabindex="-1" role="dialog" aria-labelledby="createSpeciesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createSpeciesModalLabel">Add New Breed</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createSpeciesForm" action="<?= SERVER ?>/createSpecies" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); createSpecies();">
          <div class="form-group">
            <label for="speciesName">Species Name</label>
            <input type="text" class="form-control" id="speciesName" name="species_name" required>
          </div>
          <div class="form-group">
            <label for="speciesDescription">Description</label>
            <input type="textarea" class="form-control" id="speciesName" name="species_name" required>
          </div>
          <div class="form-group">
            <label for="speciesReference">Reference</label>
            <input type="textarea" class="form-control" id="speciesReference" name="species_reference" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Species</button>
        </form>
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
        <form id="createBreedForm" action="<?= SERVER ?>/createbreed" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); createBreed();">
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
            <input type="text" class="form-control" id="averageLifespan" name="average_lifespan">
          </div>
          <div class="form-group">
            <label for="breedOrigin">Origin</label>
            <input type="text" class="form-control" id="breedOrigin" name="origin">
          </div>
          <div class="form-group">
            <label for="selectSpeciesId">Select Species</label>
            <select class="form-control" id="selectSpeciesId" name="select_species_id" required>
              <option value="">Select Species</option>
              <?php foreach ($species as $speciesN) { ?>
                <option value="<?= $speciesN->species_id ?>"><?= $speciesN->species_name ?></option>
              <?php } ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Breed</button>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Add New Pet Modal -->
<div class="modal fade" id="createPetModal" tabindex="-1" role="dialog" aria-labelledby="createPetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPetModalLabel">Add New Pet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createPetForm" action="<?= SERVER ?>/createPet" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); createPet();">
          <div class="form-group">
            <label for="petName">Pet Name</label>
            <input type="text" class="form-control" id="petName" name="pet_name" required>
          </div>
          <div class="form-group">
            <label for="petProfileImage">Pet Profile Image</label>
            <input type="file" class="form-control" id="petProfileImage" name="pet_profile_image" accept="image/*" onchange="previewProfileImage(event)">
          </div>
          <div id="profileImagePreview" style="margin-top: 10px;">
            <img id="profileImage" src="../assets/images/default_profile/defaultcat.png" alt="Profile Image Preview" style="width: 100px; height: auto;"/>
          </div>
          <div class="form-group">
            <label for="speciesId">Species</label>
            <select class="form-control" id="speciesId" name="species_id" required onchange="filterBreeds()">
              <option value="">Select Species</option>
              <?php foreach ($species as $speciesN) { ?>
                <option value="<?= $speciesN->species_id ?>"><?= $speciesN->species_name ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="breedId">Breed</label>
            <select class="form-control" id="breedId" name="breed_id" required disabled>
              <option value="">Select Breed</option>
              <?php foreach ($breeds as $breed) { ?>
                <option data-species="<?= $breed->species_id ?>" value="<?= $breed->breed_id ?>"><?= $breed->breed_name ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="petImage">Pet Related Images</label>
            <input type="file" class="form-control" id="petImage" name="pet_image_url[]" accept="image/*" multiple onchange="previewImages(event)">
          </div>
          <div id="imagePreviews" style="display: flex; flex-wrap: wrap; margin-top: 10px;">
            <img id="imagePreview" src="../assets/images/default_profile/defaultcat.png" alt="Image Preview" style="width: 100px; height: auto; margin-right: 10px;"/>
          </div>
          <div class="form-group">
            <label for="petDescription">Description</label>
            <textarea class="form-control" id="petDescription" name="pet_description" required></textarea>
          </div>
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
function resetPetForm() {
  document.getElementById('createPetForm').reset(); // Reset form fields
  document.getElementById('profileImage').src = '../assets/images/default_profile/defaultcat.png'; // Reset image preview
  document.getElementById('breedId').value = ''; // Reset breed dropdown
  document.getElementById('breedId').disabled = true; // Disable breed dropdown
}

// Reset form when modal is hidden (closed)
$('#createPetModal').on('hidden.bs.modal', function () {
  resetPetForm();
});
document.getElementById('showPets').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    document.getElementById('petsTable').style.display = 'block';
    document.getElementById('breedsTable').style.display = 'none';
    document.getElementById('speciesTable').style.display = 'none';
});

document.getElementById('showBreeds').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    document.getElementById('petsTable').style.display = 'none';
    document.getElementById('breedsTable').style.display = 'block';
    document.getElementById('speciesTable').style.display = 'none';
});

document.getElementById('showSpecies').addEventListener('click', function(event){
    event.preventDefault(); // Prevent the default anchor behavior
    document.getElementById('petsTable').style.display = 'none';
    document.getElementById('breedsTable').style.display = 'none';
    document.getElementById('speciesTable').style.display = 'block';
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
//Select species to select breed
function filterBreeds() {
    const speciesId = document.getElementById('speciesId').value;
    const breedSelect = document.getElementById('breedId');
    const breeds = breedSelect.querySelectorAll('option');

    // Enable the breed dropdown only if a species is selected
    if (speciesId) {
        breedSelect.disabled = false;
        breeds.forEach(breed => {
            if (breed.value) {
                breed.style.display = breed.getAttribute('data-species') === speciesId ? 'block' : 'none';
            }
        });
    } else {
        // Reset and disable the breed dropdown if no species is selected
        breedSelect.disabled = true;
        breedSelect.value = ''; // Reset the breed selection
        breeds.forEach(breed => {
            breed.style.display = 'none'; // Hide all breeds
        });
    }
}
//Create Pet Sweet Alert
function createPet() {
    const form = document.getElementById('createPetForm');
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create this Pet?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Pet has been created successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error creating user:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Create Species Sweet Alert
function createSpecies() {
    const form = document.getElementById('createSpeciesForm');
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create this Species?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Species has been created successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error creating species:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Create Breed Sweet Alert
function createBreed() {
    const form = document.getElementById('createBreedForm');
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create this Breed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, create it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Created!',
                        text: 'Breed has been created successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error creating breed:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Edit Pet Successfully Sweet Alert
function editPet(pet_id) {
    const form = document.getElementById(`editPetForm${pet_id}`);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to update this Pet?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Category updated successfully.',
                        showConfirmButton: true,
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error updating Pet:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Edit Species Successfully Sweet Alert
function editSpecies(species_id) {
    const form = document.getElementById(`editSpeciesForm${species_id}`);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to update this Species?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Species updated successfully.',
                        showConfirmButton: true,
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error updating Species:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Edit Breed Successfully Sweet Alert
function editBreed(breed_id) {
    const form = document.getElementById(`editBreedForm${breed_id}`);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to update this Breed?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the form submission asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Breed updated successfully.',
                        showConfirmButton: true,
                    }).then(() => {
                        // Reload the page after "OK" is clicked
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error updating Pet:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
//Delete Pet Successfully Sweet Alert
function confirmDeletePet(pet_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete this Pet? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use a form to submit the deletion request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= SERVER ?>/deletePet/${pet_id}`;  // Ensure correct route

            // Optionally add a hidden field if required by your backend
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'pet_id';
            hiddenField.value = pet_id;
            form.appendChild(hiddenField);

            // Append the form to the body and submit it
            document.body.appendChild(form);

            // Make sure the form is submitted asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    // Show success message only after the deletion process
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Category deleted successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Refresh the page after user clicks "OK"
                        location.reload();
                    });
                } else {
                    // Show error if deletion fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting category:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Please try again.',
                });
            });

            // Remove the form from the document body after submission
            document.body.removeChild(form);
        }
    });
}
//Delete Breed Successfully Sweet Alert
function confirmDeleteBreed(breed_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete this Breed? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use a form to submit the deletion request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= SERVER ?>/deleteBreed/${breed_id}`;  // Ensure correct route

            // Optionally add a hidden field if required by your backend
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'breed_id';
            hiddenField.value = breed_id;
            form.appendChild(hiddenField);

            // Append the form to the body and submit it
            document.body.appendChild(form);

            // Make sure the form is submitted asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    // Show success message only after the deletion process
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Breed deleted successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Refresh the page after user clicks "OK"
                        location.reload();
                    });
                } else {
                    // Show error if deletion fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting category:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Please try again.',
                });
            });

            // Remove the form from the document body after submission
            document.body.removeChild(form);
        }
    });
}
//Delete Species Successfully Sweet Alert
function confirmDeleteSpecies(species_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete this Species? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use a form to submit the deletion request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= SERVER ?>/deleteSpecies/${species_id}`;  // Ensure correct route

            // Optionally add a hidden field if required by your backend
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'species_id';
            hiddenField.value = species_id;
            form.appendChild(hiddenField);

            // Append the form to the body and submit it
            document.body.appendChild(form);

            // Make sure the form is submitted asynchronously
            fetch(form.action, {
                method: form.method,
                body: new FormData(form)
            })
            .then(response => {
                if (response.ok) {
                    // Show success message only after the deletion process
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Species deleted successfully.',
                        showConfirmButton: true
                    }).then(() => {
                        // Refresh the page after user clicks "OK"
                        location.reload();
                    });
                } else {
                    // Show error if deletion fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again.',
                    });
                }
            })
            .catch(error => {
                console.error('Error deleting species:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Please try again.',
                });
            });

            // Remove the form from the document body after submission
            document.body.removeChild(form);
        }
    });
}
</script>