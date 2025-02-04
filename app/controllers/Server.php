<?php

class Server extends Controller
{
  private $model;
  private $user;
  public function __construct()
  {
    // Initialize the model (e.g., User model)
    $this->model = $this->model('User');
    $this->user = $this->model('User'); 
  }
  
  public function index()
  {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Redirect to dashboard if user is already logged in
    if (isset($_SESSION['username'])) {
        $this->view('server/dashboard');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $text_username = $_POST['username'];
        $text_password = $_POST['password'];

        // Use the User model to find the user by username
        $userModel = new User();  // Assuming you have a User model extending the Model class
        $user = $userModel->first(['username' => $text_username]);

        // Check if user exists
        if ($user) {
            // Access the password as an object property
            if (password_verify($text_password, $user->password)) {
                // Password is correct
                $_SESSION['username'] = $text_username;
                $_SESSION['role'] = $user->role; // Assuming 'role' is the column name in your users table

                // Check the user's role
                if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Editor') {
                    // Set user as online
                    $userModel->update($user->user_id, ['is_online' => 1], 'user_id'); // Update is_online field to 1
                    $this->view('server/dashboard');
                    exit();
                } else {
                    echo '<script type="text/javascript">';
                    echo 'alert("Access denied: Not Permitted");';
                    echo '</script>';
                    $this->view('server/login');
                    exit();
                }
            } else {
                // Invalid password
                echo '<script type="text/javascript">';
                echo 'alert("Invalid Username or Password");';
                echo '</script>';
                $this->view('server/login');
                exit();
            }
        } else {
            // User not found
            echo '<script type="text/javascript">';
            echo 'alert("Invalid Username or Password");';
            echo '</script>';
            $this->view('server/login');
            exit();
        }
    }
    $this->view('server/login');
   }


  public function dashboard()
  {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['username'])) {
        $this->view('server/login');  // Redirect to login if not logged in
        exit();
    }
    
    // Get the logged-in user's data
    $user = $this->model->findUserByUsername();

    $users = new User();
    $data = $users->findAllUsers();
    $articles = new Article();
    $dataA =$articles->findAllArticles();

    $totalUsers = is_array ($data) ? count($data): 0;
    $totalArticles = is_array($dataA) ? count($dataA) : 0;

    
    $this->view('server/dashboard', [
      'users' => $data,
      'totalUsers' => $totalUsers,
      'totalArticles' => $totalArticles,
      'profile' => $user->profile
    ]); 
  }
  public function users()
  {
    $users = new User();
    $data = $users->findAllUsers();
    
    $this->view('server/users', [
      'users' => $data
    ]);
  }

  public function create()
  {
    $x = new User();

    if (count($_POST) > 0) {
        // Check if the profile image was uploaded without errors
        if ($_FILES['input_profile']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/users_profile/';
            $uniqueFilename = uniqid('image_') . '_' . $_FILES['input_profile']['name'];
            $uploadFile = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['input_profile']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $_POST['profile'] = $relativeFilePath; // Store the relative path of the profile image
            } else {
                echo "Error uploading file.";
                exit;
            }
        }

        // Hash the password before inserting into the database
        if (isset($_POST['password'])) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // Insert user data into the database
        $x->addUser($_POST);
        redirect('server/users');
    }

    $this->view('server/create');
  }
  public function edit($user_id)
  {
    $x = new User();
    $arr['user_id'] = $user_id;
    $data = $x->findUser($arr); // Fetch user data

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // Check if a new profile image is uploaded
        if (isset($_FILES['edit_profile']) && $_FILES['edit_profile']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/users_profile/'; // Specify your upload directory
            $uniqueFilename = uniqid('image_') . '_' . basename($_FILES['edit_profile']['name']);
            $uploadFile = $uploadDir . $uniqueFilename;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['edit_profile']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $postData['profile'] = $relativeFilePath; // Update profile image path
            } else {
                // Handle the error of moving the file (optional logging or user feedback)
            }
        }

        // Check if the profile image should be removed
        if (isset($postData['remove_profile']) && $postData['remove_profile'] === 'remove') {
            $postData['profile'] = '../assets/images/default_profile/default.png'; // Set to default image
        }

        // Hash the password if it's provided
        if (!empty($postData['password'])) {
            $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
        } else {
            unset($postData['password']);
        }

        $x->updateUser($user_id, $postData); // Update user data
        redirect('server/users'); // Redirect to users list
    }

    $this->view('server/edit', [
        'row' => $data // Pass user data to the view
    ]);
  }
  public function delete($user_id)
  {
    $x = new User();
    $arr['user_id'] = $user_id;
    $data = $x->first($arr);

    // Check if a user is logged in
    if (isset($_SESSION['username'])) {
        // Compare the logged-in user's ID with the user to be deleted
        if ($_SESSION['user_id'] == $user_id) {
            // Destroy the session if the logged-in user is being deleted
            session_destroy();
            redirect('server/login'); // Redirect to login
            exit();
        }
    }

    if (count($_POST) > 0) {
        $x->deleteUser($user_id);
        redirect('server/users');
    }

    $this->view('server/delete', [
        'row' => $data
    ]);
  }
  public function pets()
  {
    $pets = new Pet();
    $data = $pets->findAllPets();
    $breeds = new Breed();
    $datab = $breeds->findAllBreeds();
    $species = new Species();
    $datac = $species->findAllSpecies();

    $this->view('server/pets', [
      'pets' => $data,
      'breeds' => $datab,
      'species' => $datac
    ]);
  }
  public function createPet()
  {
    $c = new Pet();

    if (count($_POST) > 0) {
        $uploadedImages = []; // Array to store the paths of uploaded images

        // Handle profile image
        if ($_FILES['pet_profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/pet_profile/';
            $uniqueProfileFilename = uniqid('profile_') . '_' . basename($_FILES['pet_profile_image']['name']);
            $uploadProfileFile = $uploadDir . $uniqueProfileFilename;

            if (move_uploaded_file($_FILES['pet_profile_image']['tmp_name'], $uploadProfileFile)) {
                $relativeProfilePath = str_replace('/public', '', $uploadProfileFile);
                $_POST['pet_profile'] = $relativeProfilePath; // Store the profile image path
            } else {
                echo "Error uploading pet image.";
                exit;
            }
        }
        
        // Handle related images
        if (isset($_FILES['pet_image_url']) && !empty($_FILES['pet_image_url']['name'][0])) {
            foreach ($_FILES['pet_image_url']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['pet_image_url']['error'][$key] === UPLOAD_ERR_OK) {
                    $uploadDir = '../public/assets/images/pet_images/'; // Ensure this directory exists
                    $uniqueFilename = uniqid('image_') . '_' . basename($_FILES['pet_image_url']['name'][$key]);
                    $uploadFile = $uploadDir . $uniqueFilename;

                    if (move_uploaded_file($tmpName, $uploadFile)) {
                        $relativeFilePath = str_replace('/public', '', $uploadFile);
                        $uploadedImages[] = $relativeFilePath; // Store each image path
                    } else {
                        echo "Error uploading file: " . $_FILES['pet_image_url']['name'][$key];
                        exit;
                    }
                }
            }

            // Store the uploaded image paths in POST as a JSON array
            $_POST['pet_image_url'] = json_encode($uploadedImages); // Convert to JSON
        }

        // Call the insert method with the modified $_POST data
        $c->addPet($_POST);
        redirect('server/pets');
    }

    $this->view('server/createPet');
  }
  public function createBreed()
  {
    $b = new Breed();

    if (count($_POST) > 0) {
      
      $b->addBreed($_POST);

      redirect('server/pets');
    }

    $this->view('server/createbreed');
  }
  public function createSpecies()
  {
    $b = new Species();

    if (count($_POST) > 0) {
      
      $b->addSpecies($_POST);

      redirect('server/pets');
    }

    $this->view('server/createSpecies');
  }
  
  public function editPet($pet_id)
  {
    $x = new Pet(); // Assuming you have a Cat model for handling cat data
    $arr['pet_id'] = $pet_id;
    $data = $x->first($arr);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // Check if a new profile image is uploaded
        if (isset($_FILES['pet_profile_image']) && $_FILES['pet_profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/pet_profile/'; // Specify your upload directory
            $uploadFile = $uploadDir . basename($_FILES['pet_profile_image']['name']);

            // Move the uploaded profile image to the desired directory
            if (move_uploaded_file($_FILES['pet_profile_image']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $postData['pet_profile'] = $relativeFilePath; // Update profile image path in post data
            }
        }

        // Handle additional cat images
        if (isset($_FILES['pet_image_url']) && count($_FILES['pet_image_url']['name']) > 0) {
            $uploadDirImages = '../public/assets/images/pet_images/'; // Specify directory for additional cat images

            $imagePaths = []; // Array to store paths of uploaded images
            foreach ($_FILES['pet_image_url']['name'] as $key => $imageName) {
                if ($_FILES['pet_image_url']['error'][$key] === UPLOAD_ERR_OK) {
                    $uploadFile = $uploadDirImages . basename($imageName);
                    // Move the uploaded file to the desired directory
                    if (move_uploaded_file($_FILES['pet_image_url']['tmp_name'][$key], $uploadFile)) {
                        $relativeFilePath = str_replace('/public', '', $uploadFile);
                        $imagePaths[] = $relativeFilePath; // Store the relative path of the uploaded image
                    }
                }
            }
            $postData['pet_image_url'] = json_encode($imagePaths); // Convert paths to JSON or handle as required
        }

        // Update the cat's information
        $x->updatePet($pet_id, $postData);

        // Redirect to the list of pets
        redirect('server/pets');
    }

    $this->view('server/editPets', [
        'row' => $data
    ]);
  }
  public function editBreed($breed_id)
  {
    $b = new Breed();
    $arr['breed_id'] = $breed_id;
    $data = $b->findBreed($arr);

    if (count($_POST) > 0) {

      $b->updateBreed($breed_id, $_POST);

      redirect('server/pets');
    }

    $this->view('server/editBreed', [
      'row' => $data
    ]);
  }
  public function editSpecies($species_id)
  {
    $s = new Species();
    $arr['species_id'] = $species_id;
    $data = $s->findSpecies($arr);

    if (count($_POST) > 0) {

      $s->updateSpecies($species_id, $_POST);

      redirect('server/pets');
    }

    $this->view('server/editSpecies', [
      'row' => $data
    ]);
  }
  public function deletePet($pet_id)
  {
    $x = new Pet();
    $arr['pet_id'] = $pet_id;
    $data = $x->first($arr);

    if (count($_POST) > 0) {
        $x->deletePet($pet_id);

        redirect('server/pets');
    }

    $this->view('server/deletePet', [
        'row' => $data
    ]);
  }
  public function deleteBreed($breed_id)
  {
    $b = new Breed();
    $arr['breed_id'] = $breed_id;
    $data = $b->first($arr); // Fetch the breed

    if ($data) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Ensure it's a POST request
            $b->deleteBreed($breed_id); // Attempt to delete
            redirect('server/pets'); // Redirect after deletion
        }
        
        // Load view to confirm deletion
        $this->view('server/deleteBreed', [
            'row' => $data
        ]);
    } else {
        echo "Breed not found.";
    }
  }
  public function deleteSpecies($species_id)
  {
    $s = new Species();
    $arr['species_id'] = $species_id;
    $data = $s->first($arr);

    if ($data) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $s->deleteSpecies($species_id);
            redirect('server/pets');
        }
        
        $this->view('server/deleteSpecies', [
            'row' => $data
        ]);
    } else {

    }
  }
  public function articles()
  {
    $posts = new Article();
    $data = $posts->findAllArticles();
    $categories = new Category();
    $category = $categories->findAllCategories();

    $this->view('server/articles', [
      'articles' => $data,
      'categories' => $category
    ]);
  }
  public function createArticle()
  {
    $a = new Article();

    if (count($_POST) > 0) {
        // Check if the thumbnail image was uploaded without errors
        if ($_FILES['input_thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/article_thumbnails/';
            $uniqueFilename = uniqid('image_') . '_' . $_FILES['input_thumbnail']['name'];
            $uploadFile = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['input_thumbnail']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $_POST['article_thumbnail'] = $relativeFilePath; // Store the relative path of the profile image
            } else {
                echo "Error uploading file.";
                exit;
            }
        }

        // Insert user data into the database
        $a->addArticle($_POST);
        redirect('server/articles');
    }

    $this->view('server/createArticle');
  }
  public function editArticle($article_id)
  {
    $a = new Article();
    $arr['article_id'] = $article_id;
    $data = $a->findArticle($arr); // Fetch user data

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // Check if a new profile image is uploaded
        if (isset($_FILES['edit_thumbnail']) && $_FILES['edit_thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/article_thumbnails/';
            $uniqueFilename = uniqid('image_') . '_' . basename($_FILES['edit_thumbnail']['name']);
            $uploadFile = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['edit_thumbnail']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $postData['article_thumbnail'] = $relativeFilePath; 
            } else {

            }
        }

        $a->updateArticle($article_id, $postData);
        redirect('server/articles');
    }

    $this->view('server/editArticle', [
        'row' => $data
    ]);
  }
  public function deleteArticle($article_id)
  {
    $a = new Article();
    $arr['article_id'] = $article_id;
    $data = $a->first($arr);

    if ($data) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $a->deleteArticle($article_id);
            redirect('server/articles');
        }
        
        $this->view('server/deleteArticle', [
            'row' => $data
        ]);
    } else {

    }
  }
  public function createCategory()
  {
    $c = new Category();

    if (count($_POST) > 0) {
      
      $c->addCategory($_POST);

      redirect('server/articles');
    }

    $this->view('server/createCategory');
  }
  public function editCategory($category_id)
  {
    $c = new Category();
    $arr['category_id'] = $category_id;
    $data = $c->findCategory($arr);

    if (count($_POST) > 0) {

      $c->updateCategory($category_id, $_POST);

      redirect('server/articles');
    }

    $this->view('server/editCategory', [
      'row' => $data
    ]);
  }
  public function deleteCategory($category_id)
  {
    $c = new Category();
    $arr['category_id'] = $category_id;
    $data = $c->first($arr);

    if ($data) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $c->deleteCategory($category_id);
            redirect('server/articles');
        }
        
        $this->view('server/deleteCategory', [
            'row' => $data
        ]);
    } else {

    }
  }
  public function profile()
  {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['username'])) {
        $this->view('server/login');  // Redirect to login if not logged in
        exit();
    }
    
    // Get the logged-in user's data
    $user = $this->model->findUserByUsername();

    // If user is not found or not logged in, redirect to login page
    if (!$user) {
        $this->view('server/login'); // Redirect to login page
        exit();
    }

    // Pass the user data to the profile view
    $this->view('server/profile', [
        'username' => $user->username,
        'role' => $user->role,
        'profile' => $user->profile,
        'email' => $user->email
    ]);
  }
  public function updateProfile()
  {
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // Start the session if it's not already started
    }

    if (!isset($_SESSION['username'])) {
        $this->view('server/login'); // Redirect to login if user is not logged in
        exit();
    }

    // Fetch the current user's data based on the session
    $username = $_SESSION['username'];
    $user = $this->model->findUserByUsername($username);

    if (!$user) {
        $this->view('server/login'); // If user not found, redirect to login
        exit();
    }

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/assets/images/users_profile/';
            $uniqueFilename = uniqid('profile_') . '_' . basename($_FILES['profile_image']['name']);
            $uploadFile = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
                $relativeFilePath = str_replace('/public', '', $uploadFile);
                $postData['profile'] = $relativeFilePath; // Update profile image path
            }
        }

        // Check if the profile image should be removed
        if (isset($postData['remove_profile']) && $postData['remove_profile'] === 'remove') {
            $postData['profile'] = '../assets/images/default_profile/default.png'; // Set to default image
        }

        // Hash the password if it's provided
        if (!empty($postData['password'])) {
            $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
        } else {
            unset($postData['password']); // Do not update password if not provided
        }

        // Update the user profile in the database
        $this->model->updateUser($user->user_id, $postData); // Assuming `updateUser` exists

        // Update session data if the username was changed
        if (isset($postData['username']) && $postData['username'] !== $_SESSION['username']) {
            $_SESSION['username'] = $postData['username'];
        }

        // Redirect to the profile page or a success page after update
        redirect('server/profile');
    }

    // Pass the current user's data to the view
    $this->view('server/updateProfile', [
        'user' => $user
    ]);
  }
  public function updatePassword()
{
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if any of the fields are empty
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            // Handle error: All fields must be filled
            $this->view('server/updatePassword', ['error' => 'All fields are required.']);
            return;
        }

        // Check if the new password and confirm password match
        if ($new_password !== $confirm_password) {
            // Handle error: Passwords do not match
            $this->view('server/updatePassword', ['error' => 'Passwords do not match.']);
            return;
        }

        // Assuming you have a method to get the logged-in user's details
        $user = $this->user->getUserByUsername($_SESSION['username']);
        
        if (!$user) {
            // Handle error: User not found
            $this->view('server/updatePassword', ['error' => 'User not found.']);
            return;
        }

        // Verify the current password (hash comparison)
        if (!password_verify($current_password, $user['password'])) {
            // Handle error: Incorrect current password
            $this->view('server/updatePassword', ['error' => 'Current password is incorrect.']);
            return;
        }

        // Hash the new password before storing it
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $updated = $this->user->updatePassword($_SESSION['username'], $hashed_new_password);

        if ($updated) {
            // Redirect or show a success message
            $_SESSION['success'] = 'Password updated successfully!';
            header('Location: ' . SERVER . '/profile');
            exit();
        } else {
            // Handle error: Update failed
            $this->view('server/updatePassword', ['error' => 'Failed to update password. Please try again.']);
        }
    } else {
        // If the form is not submitted, just load the page
        $this->view('server/updatePassword');
    }
  }
  public function logout()
  {
      session_start();
  
      if (isset($_SESSION['user_id'])) {
          $user_id = $_SESSION['user_id'];
  
          $this->model->updateUser($user_id, ['is_online' => 0]);

          session_unset();
 
          session_destroy();
  
          if (ini_get("session.use_cookies")) {
              $params = session_get_cookie_params();
              setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
          }
  
          if (!isset($_SESSION['user_id'])) {
              echo "Session destroyed successfully.";
          } else {
              echo "Session not destroyed.";
          }
  
          header("Location: " . SERVER . "/login");
          exit();
      } else {
          header("Location: " . SERVER . "/login");
          exit();
      }
  }
      
}