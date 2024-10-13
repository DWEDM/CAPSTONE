<?php
session_start();

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>

<?php include "../app/views/partials/adminheader.php" ?>
<style>
  .notification {
    position: fixed;
    top: 20px;
    right: -300px; /* Initially hidden on the right side */
    width: 250px;
    padding: 15px;
    background-color: #28a745; /* Success color */
    color: white;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    z-index: 1000;
    transition: right 0.5s ease-in-out;
  }

  .notification.error {
      background-color: #dc3545; /* Error color */
  }
  .table {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
  }

  .table th, .table td {
      text-align: center;
      vertical-align: middle;
      padding: 15px;
  }

  .table tr:hover {
      background-color: rgba(0, 123, 255, 0.1); /* Highlight on hover */
  }

  .table img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
  }


</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

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
            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal<?= $row->user_id ?>" title="Delete">
              <i class="bi bi-trash"></i> <!-- Bootstrap trash icon -->
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
              <form action="<?= SERVER ?>/edit/<?= $row->user_id ?>" method="POST" enctype="multipart/form-data">
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

        <!-- Modal for Deleting User -->
        <div class="modal fade" id="deleteUserModal<?= $row->user_id ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="<?= SERVER ?>/delete/<?= $row->user_id ?>" method="POST">
                <div class="modal-body text-center">
                  <img src="<?= !empty($row->profile) ? $row->profile : '../assets/images/default_profile/default.png' ?>" alt="Profile Image" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #000;">
                  <p>Are you sure you want to delete this user?</p>
                  <p><strong>Username:</strong> <?= $row->username ?></p>
                  <p><strong>Email:</strong> <?= $row->email ?></p>
                  <p><strong>Role:</strong> <?= $row->role ?></p>
                  <p><strong>Date Created:</strong> <?= $row->date_created ?></p>
                  <input type="hidden" name="id" value="<?= $row->user_id ?>">
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
      <form action="<?= SERVER ?>/create" method="POST" enctype="multipart/form-data">
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
            <small id="usernameFeedback" class="text-danger" style="display: none;"></small>
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
              <option value="User">Cat</option>
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
  function removeProfileImage(previewElementId) {
    const imagePreview = document.getElementById(previewElementId);
    imagePreview.src = '../assets/images/default_profile/default.png'; // Set to default image
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
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notificationMessage');

    notificationMessage.innerText = message;

    // Set success or error based on the type
    if (type === 'error') {
        notification.classList.add('error');
    } else {
        notification.classList.remove('error');
    }

    // Show notification (slide in)
    notification.style.right = '20px';

    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.style.right = '-300px';
    }, 3000);
}

</script>

<?php include "../app/views/partials/footer.php" ?>
