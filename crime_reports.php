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

// Handle add new report
if (isset($_POST['addReport'])) {
  $name = sanitizeData($conn, $_POST['name']);
  $phone = sanitizeData($conn, $_POST['phone']);
  $description = sanitizeData($conn, $_POST['description']);
  $latitude = sanitizeData($conn, $_POST['latitude']);
  $longitude = sanitizeData($conn, $_POST['longitude']);
  $police_station_id = sanitizeData($conn, $_POST['police_station_id']);

  // Example handling of file upload (if you have file upload functionality)
  $uploadDir = './uploads/';
  $uploadedFile = '';

  if ($_FILES['image']['error'] == 0) {
    $fileName = basename($_FILES['image']['name']);
    $uploadFile = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
      $uploadedFile = $uploadFile;
    }
  }

  $sql = "INSERT INTO reports (name, phone, description, latitude, longitude, image_path, police_station_id) 
            VALUES ('$name', '$phone', '$description', '$latitude', '$longitude', '$uploadedFile', '$police_station_id')";

  if ($conn->query($sql) === TRUE) {
    $message = "New report added successfully!";
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  } else {
    $message = "Error: " . $conn->error;
  }
}

// Handle edit report
if (isset($_POST['editReport'])) {
  $id = sanitizeData($conn, $_POST['id']);
  $name = sanitizeData($conn, $_POST['name']);
  $phone = sanitizeData($conn, $_POST['phone']);
  $description = sanitizeData($conn, $_POST['description']);
  $latitude = sanitizeData($conn, $_POST['latitude']);
  $longitude = sanitizeData($conn, $_POST['longitude']);
  $police_station_id = sanitizeData($conn, $_POST['police_station_id']);

  $sql = "UPDATE reports 
            SET name = '$name', phone = '$phone', description = '$description', latitude = '$latitude', longitude = '$longitude', police_station_id = '$police_station_id' 
            WHERE id = '$id'";

  if ($conn->query($sql) === TRUE) {
    $message = "Report updated successfully!";
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
  } else {
    $message = "Error: " . $conn->error;
  }
}

// Handle delete report
if (isset($_POST['deleteReport'])) {
  $id = sanitizeData($conn, $_POST['id']);

  $sql = "DELETE FROM reports WHERE id = '$id'";

  if ($conn->query($sql) === TRUE) {
    $message = "Report deleted successfully!";
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
  <title>Reports</title>
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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Reports /</span> View</h4>

            <!-- Basic Bootstrap Table -->
            <div class="card">
              <h5 class="card-header">Reports
                <button style="float:right;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReportModal">
                  Add Report
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
                      <th>Name</th>
                      <th>Phone</th>
                      <th>Description</th>
                      <th>Station</th>
                      <th>Image</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php
                    $n = 0;
                    $station_id =   $_SESSION['police_station_id'];
                    $role =   $_SESSION['role'];
                    if ($_SESSION["role"] == 'admin') {
                      $sql_reports = mysqli_query($conn, "SELECT reports.*, police_stations.name AS station_name FROM reports LEFT JOIN police_stations ON reports.police_station_id = police_stations.id ORDER BY reports.id DESC");
                    } else {
                      $sql_reports = mysqli_query($conn, "SELECT reports.*, police_stations.name AS station_name FROM reports LEFT JOIN police_stations ON reports.police_station_id = police_stations.id WHERE reports.police_station_id = '$station_id' ORDER BY reports.id DESC");
                    }

                    while ($fetch_report = mysqli_fetch_array($sql_reports)) {
                      $n++;
                      $reportId = $fetch_report['id'];
                    ?>
                      <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $fetch_report['name']; ?></td>
                        <td><?php echo $fetch_report['phone']; ?></td>
                        <td><?php echo $fetch_report['description']; ?></td>
                        <td><?php echo $fetch_report['station_name']; ?></td>
                        <td><img src="<?php echo $fetch_report['image_path']; ?>" alt="<?php echo $fetch_report['image_path']; ?>" class="img-thumbnail" style="width: 90px;height:80px;"></td>
                        <td>
                          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editReportModal<?php echo $reportId; ?>">Edit</button>
                          <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteReportModal<?php echo $reportId; ?>">Delete</button>

                          <!-- Edit Report Modal -->
                          <div class="modal fade" id="editReportModal<?php echo $reportId; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-simple modal-edit-report">
                              <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  <div class="text-center mb-4">
                                    <h3 class="mb-2">Edit Report</h3>
                                  </div>
                                  <form method="POST" class="row g-3">
                                    <input type="hidden" name="id" value="<?php echo $reportId; ?>">
                                    <div class="col-md-6">
                                      <label class="form-label">Name</label>
                                      <input required type="text" class="form-control" name="name" value="<?php echo $fetch_report['name']; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Phone</label>
                                      <input required type="text" class="form-control" name="phone" value="<?php echo $fetch_report['phone']; ?>" />
                                    </div>
                                    <div class="col-md-12">
                                      <label class="form-label">Description</label>
                                      <textarea required class="form-control" name="description"><?php echo $fetch_report['description']; ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Latitude</label>
                                      <input required type="text" class="form-control" name="latitude" value="<?php echo $fetch_report['latitude']; ?>" />
                                    </div>
                                    <div class="col-md-6">
                                      <label class="form-label">Longitude</label>
                                      <input required type="text" class="form-control" name="longitude" value="<?php echo $fetch_report['longitude']; ?>" />
                                    </div>
                                    <div class="col-md-12">
                                      <label class="form-label">Police Station</label>
                                      <select required type="text" class="form-control" name="police_station_id">
                                        <option>Select Police Station</option>
                                        <?php
                                        $sql_stations = mysqli_query($conn, "SELECT * FROM police_stations");
                                        while ($station = mysqli_fetch_array($sql_stations)) {
                                          $selected = ($station['id'] == $fetch_report['police_station_id']) ? 'selected' : '';
                                          echo '<option ' . $selected . ' value="' . $station['id'] . '">' . $station['name'] . '</option>';
                                        }
                                        ?>
                                      </select>
                                    </div>
                                    <div class="col-12">
                                      <button type="submit" name="editReport" class="btn btn-primary">Save Changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--/ Edit Report Modal -->

                          <!-- Delete Report Modal -->
                          <div class="modal fade" id="deleteReportModal<?php echo $reportId; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-simple modal-edit-report">
                              <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  <div class="text-center mb-4">
                                    <h3 class="mb-2">Delete Report</h3>
                                  </div>
                                  <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $reportId; ?>" />
                                    <p>Are you sure you want to delete report <?php echo $fetch_report['name']; ?>?</p>
                                    <div class="form-group mt-3">
                                      <button type="submit" name="deleteReport" class="btn btn-primary me-sm-3 me-1">Delete</button>
                                      <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!--/ Delete Report Modal -->
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

          <!-- Add Report Modal -->
          <div class="modal fade" id="addReportModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-report">
              <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  <div class="text-center mb-4">
                    <h3 class="mb-2">Add New Report</h3>
                  </div>
                  <form method="POST" class="row g-3" enctype="multipart/form-data">
                    <div class="col-md-6">
                      <label class="form-label">Name</label>
                      <input required type="text" class="form-control" name="name" placeholder="Enter name" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Phone</label>
                      <input required type="text" class="form-control" name="phone" placeholder="Enter phone" />
                    </div>
                    <div class="col-md-12">
                      <label class="form-label">Description</label>
                      <textarea required class="form-control" name="description" placeholder="Enter description"></textarea>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Latitude</label>
                      <input required type="text" class="form-control" name="latitude" placeholder="Enter latitude" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Longitude</label>
                      <input required type="text" class="form-control" name="longitude" placeholder="Enter longitude" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Police Station</label>
                      <select required type="text" class="form-control" name="police_station_id">
                        <option>Select Police Station</option>
                        <?php
                        $sql_stations = mysqli_query($conn, "SELECT * FROM police_stations");
                        while ($station = mysqli_fetch_array($sql_stations)) {
                          echo '<option value="' . $station['id'] . '">' . $station['name'] . '</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Image</label>
                      <input type="file" class="form-control" name="image" />
                    </div>
                    <div class="col-12">
                      <button type="submit" name="addReport" class="btn btn-primary">Add Report</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!--/ Add Report Modal -->

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