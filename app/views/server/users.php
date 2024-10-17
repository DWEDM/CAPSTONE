<?php
session_start();

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>

<?php include "../app/views/partials/adminheader.php" ?>
<style>
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body style="background-color:gray;">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center">
    <h2>Users</h2>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">Add New</button>
  </div>
  <div class="input-group" style="width: 250px;">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by username or email" aria-label="Search" onkeyup="searchUsers()">
  </div>

  <table class="table table-striped mt-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border-radius: 5px; overflow: hidden;">
    <tr>
      <th>Profile</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Date Created</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php if (empty($users)) { ?>
      <tr>
        <td colspan="6" class="text-center">No users found! Please Add User!</td>
      </tr>
    <?php } else { ?>
      <?php foreach ($users as $row) { ?>
        <tr>
          <td>
            <img src="<?= !empty($row->profile) ? $row->profile : '../assets/images/default_profile/default.png' ?>" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #000; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
          </td>
          <td><?= $row->username ?></td>
          <td><?= $row->email ?></td>
          <td><?= $row->role ?></td>
          <td><?= $row->date_created ?></td>
          <td>
            <?php if ($row->is_online == 1): ?>
              <span class="text-success">Online</span>
            <?php else: ?>
              <span class="text-danger">Offline</span>
            <?php endif; ?>
          </td>
          <td>
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#editUserModal<?= $row->user_id ?>" title="Edit">
              <i class="bi bi-pencil-square"></i> <!-- Bootstrap edit icon -->
            </button>
            <button class="btn btn-danger btn-sm" onclick="confirmDelete('<?= $row->user_id ?>')" title="Delete">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal<?= $row->user_id ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="editUserForm<?= $row->user_id ?>" action="<?= SERVER ?>/edit/<?= $row->user_id ?>" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); editUser(<?= $row->user_id ?>);">
                <div class="modal-body">
                  <div class="mb-2 text-center">
                    <img id="editImagePreview<?= $row->user_id ?>" src="<?= !empty($row->profile) ? $row->profile : '../assets/images/default_profile/default.png' ?>" alt="Profile Image" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
                    <br>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProfileImage('editImagePreview<?= $row->user_id ?>', 'editProfileImageRemove<?= $row->user_id ?>')">Remove</button>
                  </div>
                  <input type="hidden" id="editProfileImageRemove<?= $row->user_id ?>" name="profile" value="">
                  <div>
                    <label for="">Profile Image</label>
                    <input type="file" name="edit_profile" class="form-control" accept="image/*" onchange="previewEditImage(event, 'editImagePreview<?= $row->user_id ?>')">
                  </div>
                  <div class="mb-2">
                    <label for="">Username</label>
                    <input type="text" name="username" value="<?= $row->username ?>" class="form-control">
                  </div>
                  <div class="mb-2">
                    <label for="">Email</label>
                    <input type="text" name="email" value="<?= $row->email ?>" class="form-control">
                  </div>
                  <div class="mb-2">
                    <label for="">Role</label>
                    <select name="role" id="role" class="form-control">
                      <option value="Select" disabled <?= $row->role ? '' : 'selected' ?>>--Select--</option>
                      <option value="Admin" <?= $row->role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                      <option value="Editor" <?= $row->role == 'Editor' ? 'selected' : '' ?>>Editor</option>
                      <option value="User" <?= $row->role == 'User' ? 'selected' : '' ?>>User</option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <label for="">Password</label>
                    <input type="password" name="password" id="editPassword<?= $row->user_id ?>" class="form-control" value="<?= $row->password ?>">
                    <div class="form-check mt-2">
                      <input type="checkbox" class="form-check-input" id="showPasswordEdit<?= $row->user_id ?>" onclick="togglePasswordEdit(<?= $row->user_id ?>)">
                      <label class="form-check-label" for="showPasswordEdit<?= $row->user_id ?>">Show Password</label>
                    </div>
                  </div>
                  <div class="mb-2">
                    <label for="task_due">Date Created</label>
                    <input type="date" name="date_created" value="<?= $row->date_created ?>" class="form-control" readonly>
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

<!-- Modal for Creating a User -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="createUserForm" action="<?= SERVER ?>/create" method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault(); createUser();">
        <div class="modal-body">
          <div class="mb-2 text-center">
            <img id="imagePreview" src="../assets/images/default_profile/default.png" alt="Profile Preview" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
          </div>
          <div>
            <label for="profile_image">Profile Image</label>
            <input type="file" name="input_profile" class="form-control" accept="image/*" onchange="previewImage(event)">
          </div>
          <div class="mb-2">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" required>
          </div>
          <div class="mb-2">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-2">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <div class="form-check mt-2">
              <input type="checkbox" class="form-check-input" id="showPasswordCreate" onclick="togglePasswordCreate()">
              <label class="form-check-label" for="showPasswordCreate">Show Password</label>
            </div>
          </div>
          <div class="mb-2">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
              <option value="" disabled selected>--Select Role--</option>
              <option value="Admin">Admin</option>
              <option value="Editor">Editor</option>
              <option value="User">User</option>
            </select>
          </div>
          <div class="mb-2">
            <label for="date_created">Date Created</label>
            <input type="date" name="date_created" class="form-control" required value="<?= date('Y-m-d') ?>" readonly>
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

</body>
<script>
  function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    if (event.target.files && event.target.files[0]) {
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.onload = function() {
            URL.revokeObjectURL(imagePreview.src);
        };
    } else {
        // If no file is selected, revert to default
        imagePreview.src = '../assets/images/default_profile/default.png';
    }
  }

  function previewEditImage(event, previewElementId) {
    const imagePreview = document.getElementById(previewElementId);
    if (event.target.files && event.target.files[0]) {
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.onload = function() {
            URL.revokeObjectURL(imagePreview.src);
        };
    } else {
        // If no file is selected, revert to default
        imagePreview.src = '../assets/images/default_profile/default.png';
    }
  }
  function removeProfileImage(previewId, removeInputId) {
    const preview = document.getElementById(previewId);
    const removeInput = document.getElementById(removeInputId);

    // Change the image to default preview and mark the profile for removal
    preview.src = '../assets/images/default_profile/default.png';
    removeInput.value = 'remove'; // Mark the profile for removal
  }


  function togglePasswordCreate() {
    const passwordInput = document.getElementById('password');
    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
  }

  function togglePasswordEdit(userId) {
    const passwordInput = document.getElementById('editPassword' + userId);
    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
  }
  function searchUsers() {
  const input = document.getElementById('searchInput');
  const filter = input.value.toLowerCase();
  const table = document.querySelector('.table');
  const rows = table.getElementsByTagName('tr');

  for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header
    const cells = rows[i].getElementsByTagName('td');
    let found = false;

    // Check username and email columns (index 1 and 2)
    if (cells.length > 1) {
      const username = cells[1].textContent || cells[1].innerText;
      const email = cells[2].textContent || cells[2].innerText;

      if (username.toLowerCase().indexOf(filter) > -1 || email.toLowerCase().indexOf(filter) > -1) {
        found = true;
      }
    }

    rows[i].style.display = found ? "" : "none"; // Show or hide the row based on the search
  }
}
document.getElementById('username').addEventListener('input', function() {
    const username = this.value;
    const feedback = document.getElementById('usernameFeedback');

    if (username.length < 3) {
        feedback.style.display = 'none';
        return; // Early exit if the username is too short
    }

    fetch(`<?= SERVER ?>/check-username?username=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(data => {
            if (data.taken) {
                feedback.style.display = 'block';
                feedback.textContent = 'Username is already taken.';
            } else {
                feedback.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error checking username:', error);
            feedback.style.display = 'none'; // Hide feedback on error
        });
});
function showNotification(message, type = 'success') {
    Swal.fire({
        icon: type === 'error' ? 'error' : 'success',
        title: type === 'error' ? 'Error!' : 'Success!',
        text: message,
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
}

//Created User Successfuly Sweet Alert
function createUser() {
    const form = document.getElementById('createUserForm');
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to create this user?',
        icon: 'warning',
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
                        text: 'User has been created successfully.',
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



//Edit User Successfully Sweet Alert
function editUser(user_id) {
    const form = document.getElementById(`editUserForm${user_id}`);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to update this user?`,
        icon: 'warning',
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
                        text: 'User updated successfully.',
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
                console.error('Error updating user:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}



//Delete User Successfully Sweet Alert
function confirmDelete(user_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you really want to delete this user? This action cannot be undone.`,
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
            form.action = `<?= SERVER ?>/delete/${user_id}`;  // Ensure correct route

            // Optionally add a hidden field if required by your backend
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'user_id';
            hiddenField.value = user_id;
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
                        text: 'User deleted successfully.',
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
                console.error('Error deleting user:', error);
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

<?php include "../app/views/partials/footer.php" ?>
