<?php

// Assuming you have already established a database connection
// Replace 'your_host', 'your_username', 'your_password', and 'your_database' with your actual database credentials
$connection = mysqli_connect('localhost', 'u718878629_sim_rs', 'Programer123##@@l', 'u718878629_sim_rs');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Get current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Calculate datetime one hour from now
$oneHourLaterDateTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Update records where current date time is same with field tanggal_waktu value
$updateSameDateTimeQuery = "UPDATE u_maintenance_schedule_aset SET status = 'berlangsung' WHERE tanggal_waktu = '$currentDateTime'";
mysqli_query($connection, $updateSameDateTimeQuery);

// Update records where current date time is more than one hour after field tanggal_waktu value
$updatePastDateTimeQuery = "UPDATE u_maintenance_schedule_aset SET status = 'selesai' WHERE tanggal_waktu < '$oneHourLaterDateTime'";
mysqli_query($connection, $updatePastDateTimeQuery);

// Close connection
mysqli_close($connection);

?>