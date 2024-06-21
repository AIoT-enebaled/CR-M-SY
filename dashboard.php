<!DOCTYPE html>
<?php
session_start();
include('./database/connect.php');
ob_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard </title>

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
                    <?php
                    $station_id =   $_SESSION['police_station_id'];
                    $role =   $_SESSION['role'];
                    ?>
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?php
                        if ($_SESSION["role"] == 'admin') {
                        ?>
                            <h4>WELCOME ADMIN</h4>
                        <?php
                        } else {
                            $sql_stations = mysqli_query($conn, "SELECT * FROM police_stations  WHERE id ='$station_id'");
                            $fetch_station = mysqli_fetch_array($sql_stations);
                        ?>
                            <h4><?php echo $fetch_station['name'];?></h4>
                        <?php
                        }
                        ?>
                        <div class="row g-4 mb-4">

                            <?php
                            if ($_SESSION["role"] == 'admin') {
                                // Example queries to fetch counts from your database
                                $sql_admins = "SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'";
                                $sql_police_members = "SELECT COUNT(*) as police_member_count FROM users WHERE role = 'police'";
                                $sql_police_stations = "SELECT COUNT(*) as police_station_count FROM police_stations";
                                $sql_crime_reports = "SELECT COUNT(*) as crime_report_count FROM reports";

                                // Execute queries with error handling
                                $result_admins = mysqli_query($conn, $sql_admins);
                                $result_police_members = mysqli_query($conn, $sql_police_members);
                                $result_police_stations = mysqli_query($conn, $sql_police_stations);
                                $result_crime_reports = mysqli_query($conn, $sql_crime_reports);

                                // Fetch counts with error handling
                                $admin_count = ($result_admins) ? mysqli_fetch_assoc($result_admins)['admin_count'] : 0;
                                $police_member_count = ($result_police_members) ? mysqli_fetch_assoc($result_police_members)['police_member_count'] : 0;
                                $police_station_count = ($result_police_stations) ? mysqli_fetch_assoc($result_police_stations)['police_station_count'] : 0;
                                $crime_report_count = ($result_crime_reports) ? mysqli_fetch_assoc($result_crime_reports)['crime_report_count'] : 0;
                            ?>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $admin_count; ?></h4>
                                                    </div>
                                                    <span>Admins</span>
                                                </div>
                                                <span class="badge bg-label-primary rounded p-2">
                                                    <i class="ti ti-user ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $police_member_count; ?></h4>
                                                    </div>
                                                    <span>Police Members</span>
                                                </div>
                                                <span class="badge bg-label-danger rounded p-2">
                                                    <i class="ti ti-user-plus ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $police_station_count; ?></h4>
                                                    </div>
                                                    <span>Police Stations</span>
                                                </div>
                                                <span class="badge bg-label-success rounded p-2">
                                                    <i class="ti ti-user-check ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $crime_report_count; ?></h4>
                                                    </div>
                                                    <span>Crime Reports</span>
                                                </div>
                                                <span class="badge bg-label-warning rounded p-2">
                                                    <i class="ti ti-user ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                // Example queries to fetch counts from your database

                                $sql_police_members = "SELECT COUNT(*) as police_member_count FROM users WHERE role = 'police' AND police_station_id = '$station_id'";

                                $sql_crime_reports = "SELECT COUNT(*) as crime_report_count FROM reports WHERE police_station_id	 = '$station_id'";

                                // Execute queries with error handling

                                $result_police_members = mysqli_query($conn, $sql_police_members);
                                $result_crime_reports = mysqli_query($conn, $sql_crime_reports);

                                // Fetch counts with error handling

                                $police_member_count = ($result_police_members) ? mysqli_fetch_assoc($result_police_members)['police_member_count'] : 0;

                                $crime_report_count = ($result_crime_reports) ? mysqli_fetch_assoc($result_crime_reports)['crime_report_count'] : 0;
                            ?>


                                <div class="col-sm-6 col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $police_member_count; ?></h4>
                                                    </div>
                                                    <span>Police Members</span>
                                                </div>
                                                <span class="badge bg-label-danger rounded p-2">
                                                    <i class="ti ti-user-plus ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-6 col-xl-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between">
                                                <div class="content-left">
                                                    <div class="d-flex align-items-center my-1">
                                                        <h4 class="mb-0 me-2"><?php echo $crime_report_count; ?></h4>
                                                    </div>
                                                    <span>Crime Reports</span>
                                                </div>
                                                <span class="badge bg-label-warning rounded p-2">
                                                    <i class="ti ti-user ti-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!-- / Content -->

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