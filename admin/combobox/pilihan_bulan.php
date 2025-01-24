<?php
    include '../../config/koneksi.php';

    
    $idBulan=$_POST['idBulan'];
    echo '<option  selected value="all">- Semua Bulan -</option>';

    $query = mysqli_query($conn,"SELECT * FROM bulan ORDER BY idBulan ASC");
    while ($q = mysqli_fetch_array($query)) {
    	if ($idBulan == $q['idBulan']){
    		echo '<option value="'.$q['idBulan'].'" selected>'.$q['nmBulan'].'</option>';
    	}else{
    		echo '<option value="'.$q['idBulan'].'">'.$q['nmBulan'].'</option>';
    	}
    	
    }
?>