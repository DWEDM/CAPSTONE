<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    $this->view("server/login");
    exit();
}
?>
<?php include "../app/views/partials/adminheader.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container mt-5">
        <!-- Add shadow-lg for a large shadow effect around the card -->
        <div class="card mx-auto shadow-lg" style="max-width: 600px;">
            <div class="card-body text-center">
                <h1 class="card-title">Welcome, <?= htmlspecialchars($username); ?>!</h1>

                <!-- Display the logged-in user's profile image -->
                <?php if (!empty($profile)): ?>
                    <img src="<?= htmlspecialchars($profile); ?>" alt="Profile Image" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                <?php else: ?>
                    <img src="../assets/images/default_profile/default.png" alt="Default Profile Image" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                <?php endif; ?>
                
                <!-- Display user details -->
                <p><strong>Username:</strong> <?= htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($role); ?></p>

                <!-- Button to open the Edit Profile modal -->
                <button id="editProfileBtn" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>

                <!-- Button to open the Change Password modal -->
                <button id="changePasswordBtn" class="btn btn-warning mt-3" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Profile Form -->
                    <form action="<?= SERVER ?>/updateProfile" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($username); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="profile_image">Profile Image</label>
                            <input type="file" class="form-control" id="profile_image" name="profile_image">
                        </div>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Change Password Form -->
                    <form action="<?= SERVER ?>/updatePassword" method="POST">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Client-side password confirmation validation
        $('#changePasswordModal form').on('submit', function(e) {
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert("Passwords do not match. Please try again.");
            }
        });
    </script>
</body>
</html>
<?php include "../app/views/partials/footer.php" ?>
