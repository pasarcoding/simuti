<?php
include('config/koneksi.php');  // Your database connection file

// Check if the id and catatan are passed
if (isset($_POST['id']) && isset($_POST['catatan'])) {
    $idRk = $_POST['id'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    // Update the catatan in the database
    $updateQuery = "UPDATE rb_rencana_kegiatan SET catatan = '$catatan' WHERE idRk = '$idRk'";

    // Execute the query
    if (mysqli_query($conn, $updateQuery)) {
        echo 'Success';
    } else {
        echo 'Error updating catatan';
    }
} else {
    echo 'Invalid input';
}
?>
