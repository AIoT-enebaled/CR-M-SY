<?php
include('./database/connect.php');
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Police Crime Report - </title>
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="./assets/vendor/css/pages/cards-advance.css" />
  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>

  <script src="./assets/js/config.js"></script>
</head>

<body>
  <?php

  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $police_station_id = $_POST['police_station_id'];
    $image = $_FILES['image'];

    // Save the image with a unique name
    $target_dir = "uploads/";
    $unique_filename = uniqid() . "-" . basename($image["name"]);
    $image_path = $target_dir . $unique_filename;
    if (move_uploaded_file($image["tmp_name"], $image_path)) {
      // Insert the report
      $stmt = $conn->prepare("INSERT INTO reports (description, location, timestamp, status, police_station_id) VALUES (?, POINT(?, ?), NOW(), 'pending', ?)");
      $stmt->bind_param("sddi", $description, $latitude, $longitude, $police_station_id);

      if ($stmt->execute()) {
        // Redirect to avoid resubmission on reload and show success message
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
      } else {
        $message = "Error: " . $stmt->error;
        $messageType = "danger";
      }

      $stmt->close();
    } else {
      $message = "Error uploading image.";
      $messageType = "danger";
    }
  }

  $police_stations = [];
  $sql = "SELECT id, name, latitude, longitude FROM police_stations";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $police_stations[] = $row;
    }
  }

  $conn->close();
  ?>
  <!-- Layout wrapper -->
  <div class="wrapper">
    <div class="container">
      <!-- Layout container -->
      <div class="page">
        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
              <div class="card-body">
                <div class="form-box">
                  <center>
                    <img src="./assets/img/logo.jpg" alt="Logo" class="form-logo" width="100" height="100">
                  </center>
                  <center>
                    <h3 class="mb-4">Report a Crime</h3>
                  </center>
                  <?php if (isset($_GET['success'])) : ?>
                    <div class="alert alert-success">Report submitted successfully!</div>
                  <?php endif; ?>
                  <form action="" method="post" enctype="multipart/form-data" id="report-form">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="name" class="form-label">Your Name:</label>
                        <input placeholder="Enter Your name" type="text" id="name" name="name" class="form-control" required>
                      </div>
                      <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number:</label>
                        <input placeholder="Enter Your number" type="text" id="phone" name="phone" class="form-control" required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="latitude" class="form-label">Latitude:</label>
                        <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
                      </div>
                      <div class="col-md-6">
                        <label for="longitude" class="form-label">Longitude:</label>
                        <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="police_station_id" class="form-label">Select Police Station:</label>
                        <select id="police_station_id" name="police_station_id" class="form-control" required>
                          <option value="">Select Police Station</option>
                          <?php foreach ($police_stations as $station) : ?>
                            <option value="<?php echo $station['id']; ?>" data-latitude="<?php echo $station['latitude']; ?>" data-longitude="<?php echo $station['longitude']; ?>">
                              <?php echo $station['name']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="image" class="form-label">Upload Image Of what is happening:</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label for="description" class="form-label">Crime Description:</label>
                      <textarea placeholder="Enter The crime" rows="5" id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
                  </form>

                </div>
              </div>
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
      <!-- / Layout container -->
    </div>
    <!-- / Layout wrapper -->

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <script src=" ./assets/vendor/libs/jquery/jquery.js"></script>
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
  <script src="./assets/vendor/libs/datatables/jquery.dataTables.js"></script>
  <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="./assets/vendor/libs/datatables-responsive/datatables.responsive.js"></script>
  <script src="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js"></script>
  <script src="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>
  <!-- Page JS -->
  <script src="./assets/js/dashboards-analytics.js"></script>
  <script src="./assets/js/form.js"></script>

  <!-- Include the Geolocation API and Logic to Find Nearest Police Station -->
  <script>
    // Function to calculate the distance between two coordinates using Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Radius of the Earth in kilometers
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      const distance = R * c; // Distance in kilometers
      return distance;
    }

    // Function to find the nearest police station
    function findNearestPoliceStation(userLat, userLon) {
      const policeStationSelect = document.getElementById('police_station_id');
      const options = policeStationSelect.options;
      let nearestStationIndex = 0;
      let minDistance = Infinity;

      for (let i = 1; i < options.length; i++) { // Start from 1 to skip the placeholder option
        const stationLat = parseFloat(options[i].getAttribute('data-latitude'));
        const stationLon = parseFloat(options[i].getAttribute('data-longitude'));
        const distance = calculateDistance(userLat, userLon, stationLat, stationLon);

        if (distance < minDistance) {
          minDistance = distance;
          nearestStationIndex = i;
        }
      }
      policeStationSelect.selectedIndex = nearestStationIndex;
    }

    // Get user's location and find the nearest police station
    function getUserLocationAndSetNearestStation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const userLat = position.coords.latitude;
          const userLon = position.coords.longitude;

          document.getElementById('latitude').value = userLat;
          document.getElementById('longitude').value = userLon;

          findNearestPoliceStation(userLat, userLon);
        }, (error) => {
          console.error("Error getting the user's location:", error);
        });
      } else {
        console.error("Geolocation is not supported by this browser.");
      }
    }

    // Execute the function on page load
    window.onload = getUserLocationAndSetNearestStation;
  </script>
</body>

</html>