<?php
    include '../../config/koneksi.php';

    
    $idKelas=$_POST['idKelas'];
    $tipe_kelas=$_POST['tipe_kelas'];
    echo '<option disabled selected value="">- Pilih Kelas -</option>';

    if ($tipe_kelas == 'semuaKelas'){
        if($idKelas == 'all'){
            echo '<option value="all" selected> Semua Kelas </option>';
        }else{
             echo '<option value="all"> Semua Kelas </option>';
        }
    }

    $query = mysqli_query($conn,"SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
    while ($q = mysqli_fetch_array($query)) {
    	if ($idKelas == $q['idKelas']){
    		echo '<option value="'.$q['idKelas'].'" selected>'.$q['nmKelas'].'</option>';
    	}else{
    		echo '<option value="'.$q['idKelas'].'">'.$q['nmKelas'].'</option>';
    	}
    	
    }
?>