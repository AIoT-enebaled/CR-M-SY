<?php
session_start();
session_unset();
session_destroy();
// Destroying All Sessions

// Redirecting To Home Page
header("Location: ../login.php");
