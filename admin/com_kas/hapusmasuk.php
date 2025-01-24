<?php 

$id = $_GET['id'];
$sql = mysqli_query($conn, "DELETE FROM kas WHERE kode = '$id' ");
if($sql) {
	?>
    <script type="text/javascript">
        alert("Data Berhasil Dihapus");
        window.location.href = "?module=masuk";
    </script>
    <?php		
}

?>