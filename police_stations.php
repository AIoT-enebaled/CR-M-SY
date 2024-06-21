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

// Handle add new police station
if (isset($_POST['addPoliceStation'])) {
    $name = sanitizeData($conn, $_POST['name']);
    $address = sanitizeData($conn, $_POST['address']);
    $location = sanitizeData($conn, $_POST['location']);
    $longitude = floatval($_POST['longitude']);
    $latitude = floatval($_POST['latitude']);

    $sql = "INSERT INTO police_stations (name, address, location, longitude, latitude) 
            VALUES ('$name', '$address', '$location', $longitude, $latitude)";

    if ($conn->query($sql) === TRUE) {
        $message = "New police station added successfully!";
           header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    } else {
        $message = "Error: " . $conn->error;
    }
}
// Handle edit police station
if (isset($_POST['editPoliceStation'])) {
    $id = sanitizeData($conn, $_POST['id']);
    $name = sanitizeData($conn, $_POST['name']);
    $address = sanitizeData($conn, $_POST['address']);
    $location = sanitizeData($conn, $_POST['location']);
    $longitude = floatval($_POST['longitude']);
    $latitude = floatval($_POST['latitude']);

    $sql = "UPDATE police_stations 
            SET name = '$name', address = '$address', location = '$location', 
                longitude = $longitude, latitude = $latitude 
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Police station updated successfully!";
           header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Handle delete police station
if (isset($_POST['deletePoliceStation'])) {
    $id = sanitizeData($conn, $_POST['id']);

    $sql = "DELETE FROM police_stations WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        $message = "Police station deleted successfully!";
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
    <title>Police Stations</title>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Police Stations /</span> View</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Police Stations
                                <button style="float:right;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPoliceStationModal">
                                    Add Police Station
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
                                            <th>Address</th>
                                            <th>Location</th>
                                            <th>Longitude</th>
                                            <th>Latitude</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        $n = 0;
                                        $sql_stations = mysqli_query($conn, "SELECT * FROM police_stations");
                                        while ($fetch_station = mysqli_fetch_array($sql_stations)) {
                                            $n++;
                                            $stationId = $fetch_station['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $n; ?></td>
                                                <td><?php echo $fetch_station['name']; ?></td>
                                                <td><?php echo $fetch_station['address']; ?></td>
                                                <td><?php echo $fetch_station['location']; ?></td>
                                                <td><?php echo $fetch_station['longitude']; ?></td>
                                                <td><?php echo $fetch_station['latitude']; ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPoliceStationModal<?php echo $stationId; ?>">Edit</button>
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deletePoliceStationModal<?php echo $stationId; ?>">Delete</button>

                                                    <!-- Edit Police Station Modal -->
                                                    <div class="modal fade" id="editPoliceStationModal<?php echo $stationId; ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-md modal-simple modal-edit-user">
                                                            <div class="modal-content p-3 p-md-5">
                                                                <div class="modal-body">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    <div class="text-center mb-4">
                                                                        <h3 class="mb-2">Edit Police Station</h3>
                                                                    </div>
                                                                    <form method="POST" class="row g-3">
                                                                        <input type="hidden" name="id" value="<?php echo $stationId; ?>">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Name</label>
                                                                            <input required type="text" class="form-control" name="name" value="<?php echo $fetch_station['name']; ?>" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Address</label>
                                                                            <input required type="text" class="form-control" name="address" value="<?php echo $fetch_station['address']; ?>" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Location</label>
                                                                            <input required type="text" class="form-control" name="location" value="<?php echo $fetch_station['location']; ?>" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Longitude</label>
                                                                            <input required type="number" step="any" class="form-control" name="longitude" value="<?php echo $fetch_station['longitude']; ?>" />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label">Latitude</label>
                                                                            <input required type="number" step="any" class="form-control" name="latitude" value="<?php echo $fetch_station['latitude']; ?>" />
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <button type="submit" name="editPoliceStation" class="btn btn-primary">Save Changes</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/ Edit Police Station Modal -->

                                                    <!-- Delete Police Station Modal -->
                                                    <div class="modal fade" id="deletePoliceStationModal<?php echo $stationId; ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-md modal-simple modal-edit-user">
                                                            <div class="modal-content p-3 p-md-5">
                                                                <div class="modal-body">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    <div class="text-center mb-4">
                                                                        <h3 class="mb-2">Delete Police Station</h3>
                                                                    </div>
                                                                    <form method="POST">
                                                                        <input type="hidden" name="id" value="<?php echo $stationId; ?>" />
                                                                        <p>Are you sure you want to delete police station <?php echo $fetch_station['name']; ?>?</p>
                                                                        <div class="form-group mt-3">
                                                                            <button type="submit" name="deletePoliceStation" class="btn btn-primary me-sm-3 me-1">Delete</button>
                                                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/ Delete Police Station Modal -->
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

                    <!-- Add Police Station Modal -->
                    <div class="modal fade" id="addPoliceStationModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">Add New Police Station</h3>
                                    </div>
                                    <form method="POST" class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Name</label>
                                            <input required type="text" class="form-control" name="name" placeholder="Enter name" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Address</label>
                                            <input required type="text" class="form-control" name="address" placeholder="Enter address" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Location</label>
                                            <input required type="text" class="form-control" name="location" placeholder="Enter location" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Longitude</label>
                                            <input required type="number" step="any" class="form-control" name="longitude" placeholder="Enter longitude" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Latitude</label>
                                            <input required type="number" step="any" class="form-control" name="latitude" placeholder="Enter latitude" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="addPoliceStation" class="btn btn-primary">Add Police Station</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Add Police Station Modal -->

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