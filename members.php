<!DOCTYPE html>
<?php
session_start();
include('./database/connect.php');
          ob_start();
          if (!isset($_SESSION['loggedin'])) {
            header("Location: login.php");
            exit();
          }

// Function to sanitize input data
function sanitizeData($conn, $data)
{
  return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
}

// Handle add new member
if (isset($_POST['addMember'])) {
  $names = sanitizeData($conn, $_POST['names']);
  $phone = sanitizeData($conn, $_POST['phone']);
  $email = sanitizeData($conn, $_POST['email']);
  $username = sanitizeData($conn, $_POST['username']);
  $password = md5($_POST['password']); // Hash password

  $sql = "INSERT INTO users (names, phone, email, username, password) 
            VALUES ('$names', '$phone', '$email', '$username', '$password')";

  if ($conn->query($sql) === TRUE) {
    $message = "New member added successfully!";
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  } else {
    $message = "Error: " . $conn->error;
  }
}

// Handle edit member
if (isset($_POST['editMember'])) {
  $id = sanitizeData($conn, $_POST['id']);
  $names = sanitizeData($conn, $_POST['names']);
  $phone = sanitizeData($conn, $_POST['phone']);
  $email = sanitizeData($conn, $_POST['email']);
  $username = sanitizeData($conn, $_POST['username']);

  $sql = "UPDATE users 
            SET names = '$names', phone = '$phone', email = '$email', username = '$username' 
            WHERE id = '$id'";

  if ($conn->query($sql) === TRUE) {
    $message = "Member updated successfully!";
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  } else {
    $message = "Error: " . $conn->error;
  }
}

// Handle delete member
if (isset($_POST['deleteMember'])) {
  $id = sanitizeData($conn, $_POST['id']);

  $sql = "DELETE FROM users WHERE id = '$id'";

  if ($conn->query($sql) === TRUE) {
    $message = "Member deleted successfully!";
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  } else {
    $message = "Error: " . $conn->error;
  }
}
?>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Members</title>
  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/swiper/swiper.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/pages/cards-advance.css" />
  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>

  <script src="./assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <?php include('includes/sidebar.php'); ?>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <?php include('./includes/navbar.php'); ?>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">users /</span> View</h4>

            <!-- Basic Bootstrap Table -->
            <div class="card">
              <h5 class="card-header">users
                <button style="float:right;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                  Add Member
                </button>
              </h5>
              <div class="table-responsive text-nowrap">
                <?php if (isset($_GET['success'])) : ?>
                  <div class="alert alert-success">Action successful!</div>
                <?php endif; ?>
                <table id="tables" class="table nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Names</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Role</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php
                    $n = 0;
                    $sql_users = mysqli_query($conn, "SELECT * FROM users");
                    while ($fetch_member = mysqli_fetch_array($sql_users)) {
                      $n++;
                      $memberId = $fetch_member['id'];
                    ?>
                      <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $fetch_member['names']; ?></td>
                        <td><?php echo $fetch_member['phone']; ?></td>
                        <td><?php echo $fetch_member['email']; ?></td>
                        <td><?php echo $fetch_member['username']; ?></td>
                        <td><?php echo $fetch_member['role']; ?></td>
                        <td>
                          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editMemberModal<?php echo $memberId; ?>">Edit</button>
                          <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteMemberModal<?php echo $memberId; ?>">Delete</button>

                          <!-- Edit Member Modal -->
                          <div class="modal fade" id="editMemberModal<?php echo $memberId; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-simple modal-edit-user">
                              <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  <div class="text-center mb-4">
                                    <h3 class="mb-2">Edit Member</h3>
                                  </div>
                                  <form method="POST" class="row g-3">
                                    <input type="hidden" name="id" value="<?php echo $memberId; ?>">
                                    <div class="col-md-6">
                                      <label class="form-label">Names</label>
                                      <input required type="text" class="form-control" name="names" value="<?php echo $fetch_member['names']; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Phone</label>
                                      <input required type="text" class="form-control" name="phone" value="<?php echo $fetch_member['phone']; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Email</label>
                                      <input required type="email" class="form-control" name="email" value="<?php echo $fetch_member['email']; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Username</label>
                                      <input required type="text" class="form-control" name="username" value="<?php echo $fetch_member['username']; ?>" />
                                    </div>
                                    <div class="col-md-12">
                                      <label class="form-label">Role</label>
                                      <select required type="text" class="form-control" name="role">
                                        <option>Select Role</option>
                                        <option <?php if ($fetch_member['role'] == 'admin') {
                                                  echo 'selected';
                                                } ?> value="admin">Admin</option>
                                        <option <?php if ($fetch_member['role'] == 'police') {
                                                  echo 'selected';
                                                } ?> value="police">Police</option>
                                      </select>
                                    </div>
                                    <div class="col-12">
                                      <button type="submit" name="editMember" class="btn btn-primary">Save Changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--/ Edit Member Modal -->

                          <!-- Delete Member Modal -->
                          <div class="modal fade" id="deleteMemberModal<?php echo $memberId; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-simple modal-edit-user">
                              <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  <div class="text-center mb-4">
                                    <h3 class="mb-2">Delete Member</h3>
                                  </div>
                                  <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $memberId; ?>" />
                                    <p>Are you sure you want to delete member <?php echo $fetch_member['names']; ?>?</p>
                                    <div class="form-group mt-3">
                                      <button type="submit" name="deleteMember" class="btn btn-primary me-sm-3 me-1">Delete</button>
                                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--/ Delete Member Modal -->
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- / Content -->

          <!-- Add Member Modal -->
          <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
              <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  <div class="text-center mb-4">
                    <h3 class="mb-2">Add New Member</h3>
                  </div>
                  <form method="POST" class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Names</label>
                      <input required type="text" class="form-control" name="names" placeholder="Enter names" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Phone</label>
                      <input required type="text" class="form-control" name="phone" placeholder="Enter phone" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input required type="email" class="form-control" name="email" placeholder="Enter email" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Username</label>
                      <input required type="text" class="form-control" name="username" placeholder="Enter username" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Role</label>
                      <select required type="text" class="form-control" name="role">
                        <option>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="police">Police</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Password</label>
                      <input required type="password" class="form-control" name="password" placeholder="Enter password" />
                    </div>
                    <div class="col-12">
                      <button type="submit" name="addMember" class="btn btn-primary">Add Member</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!--/ Add Member Modal -->

          <!-- Footer -->
          <?php include('includes/footer.php'); ?>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <script src="./assets/vendor/libs/popper/popper.js"></script>
  <script src="./assets/vendor/js/bootstrap.js"></script>
  <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>

  <script src="./assets/vendor/libs/hammer/hammer.js"></script>
  <script src="./assets/vendor/libs/i18n/i18n.js"></script>
  <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>

  <script src="./assets/vendor/js/menu.js"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="./assets/vendor/libs/swiper/swiper.js"></script>
  <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="./assets/js/dashboards-analytics.js"></script>
</body>

</html>