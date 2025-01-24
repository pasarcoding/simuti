<?php
include '../config/koneksi.php';

if (isset($_POST['simpan'])) {
    $no = $_POST['simpan'];
    $id_guru = $_POST["id_guru_$no"];
    $id_bulan = $_POST['id_bulan'];
    $jumlah_jam = $_POST["jam_$no"];
    $gaji_pokok = $_POST['gaji_pokok'];
    $idTahunAjaran = $_POST['idTahunAjaran'];

    // Assuming you have a column for each type of tunjangan
    $tunjangan_values = [];
    $jenisGajiQuery = mysqli_query($conn, "SELECT * FROM jenis_gaji WHERE jenis='tunjangan'");
    $i = 1;
    while ($jenisGaji = mysqli_fetch_array($jenisGajiQuery)) {
        $idJenis = $jenisGaji['id'];
        $tunjangan_values[$i] = $_POST["gaji_{$idJenis}_$no"];
        $i++;
    }


    $total_tunjangan = array_sum($tunjangan_values);

    // Assuming you have a column for each jenis_potongan
    // Assuming you have a column for each jenis_potongan
    $potongan_values = [];
    $jenisPotonganQuery = mysqli_query($conn, "SELECT * FROM jenis_potongan");
    $i = 1;

    // Calculate total tunjangan
    $total_tunjangan = array_sum($tunjangan_values);
    $tot_awal =  $gaji_pokok + $total_tunjangan ;
    while ($jenisPotongan = mysqli_fetch_array($jenisPotonganQuery)) {
        $idPotongan = $jenisPotongan['id'];

        $nmPotongan = $jenisPotongan['nmPotongan'];
        if (stripos($nmPotongan, 'infaq') !== false) {
            // Hitung potongan 2.5% dari total tunjangan dan set nilai
            $potonganValue = $_POST["potongan_{$idPotongan}_$no"];
        } else {
            // Set nilai potongan dari input form
            $potonganValue = $_POST["potongan_{$idPotongan}_$no"];
        }

        // Simpan nilai potongan ke dalam array
        $potongan_values[$i] = $potonganValue;

        $i++;
    }


    // Calculate total tunjangan

    // Calculate total potongan
    $total_potongan = array_sum($potongan_values);

    // Calculate total
    $total =  $tot_awal - $total_potongan;

    // Check if the entry already exists
    $checkQuery = "SELECT * FROM bayar_gaji 
                   WHERE id_guru='$id_guru' AND idTahunAjaran='$idTahunAjaran' AND id_bulan='$id_bulan'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Entry already exists, perform update
        $updateQuery = "UPDATE bayar_gaji 
                        SET jumlah_jam='$jumlah_jam', gaji_pokok='$gaji_pokok', total= '$total',total_gaji='$tot_awal',total_potongan='$total_potongan',
                        id_jenis_tunjangan2='{$tunjangan_values[1]}', id_jenis_tunjangan3='{$tunjangan_values[2]}', 
                        id_jenis_tunjangan4='{$tunjangan_values[3]}', id_jenis_tunjangan5='{$tunjangan_values[4]}', 
                        id_jenis_tunjangan6='{$tunjangan_values[5]}', id_jenis_tunjangan7='{$tunjangan_values[6]}', 
                        id_jenis_tunjangan8='{$tunjangan_values[7]}', id_jenis_tunjangan9='{$tunjangan_values[8]}', 
                        id_jenis_tunjangan10='{$tunjangan_values[9]}',
                        id_jenis_potongan1='{$potongan_values[1]}', id_jenis_potongan2='{$potongan_values[2]}', 
                        id_jenis_potongan3='{$potongan_values[3]}', id_jenis_potongan4='{$potongan_values[4]}', 
                        id_jenis_potongan5='{$potongan_values[5]}',id_jenis_potongan6='{$potongan_values[6]}',
                        id_jenis_potongan7='{$potongan_values[7]}',id_jenis_potongan8='{$potongan_values[8]}',
                        id_jenis_potongan9='{$potongan_values[9]}',id_jenis_potongan10='{$potongan_values[10]}'
                        WHERE id_guru='$id_guru' AND idTahunAjaran='$idTahunAjaran' AND id_bulan='$id_bulan'";
        mysqli_query($conn, $updateQuery);
    } else {
        // Entry does not exist, perform insert
        $insertQuery = "INSERT INTO bayar_gaji (id_guru, idTahunAjaran, id_bulan, jumlah_jam, gaji_pokok, total, total_gaji,total_potongan,
                        id_jenis_tunjangan2, id_jenis_tunjangan3, id_jenis_tunjangan4, id_jenis_tunjangan5,
                        id_jenis_tunjangan6, id_jenis_tunjangan7, id_jenis_tunjangan8, id_jenis_tunjangan9, id_jenis_tunjangan10,
                        id_jenis_potongan1, id_jenis_potongan2, id_jenis_potongan3, id_jenis_potongan4, id_jenis_potongan5,
                        id_jenis_potongan6,id_jenis_potongan7,id_jenis_potongan8,id_jenis_potongan9,id_jenis_potongan10)
                        VALUES ('$id_guru', '$idTahunAjaran', '$id_bulan', '$jumlah_jam', '$gaji_pokok', '$total','$tot_awal','$total_potongan',
                        '{$tunjangan_values[1]}', '{$tunjangan_values[2]}', '{$tunjangan_values[3]}', '{$tunjangan_values[4]}',
                        '{$tunjangan_values[5]}', '{$tunjangan_values[6]}', '{$tunjangan_values[7]}', '{$tunjangan_values[8]}',
                        '{$tunjangan_values[9]}',
                        '{$potongan_values[1]}', '{$potongan_values[2]}', '{$potongan_values[3]}', '{$potongan_values[4]}',
                        '{$potongan_values[5]}','{$potongan_values[6]}','{$potongan_values[7]}','{$potongan_values[8]}',
                        '{$potongan_values[9]}','{$potongan_values[10]}')";
        mysqli_query($conn, $insertQuery);
    }

    // Redirect or perform additional actions after saving
   
    header("Location: ../cetakGaji.php?id=$id_guru&idTahunAjaran=$idTahunAjaran&bulan=$id_bulan");
    exit();
}
