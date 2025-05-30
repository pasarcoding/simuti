<?php
// Upload gambar untuk berita
function UploadImage($fupload_name)
{
  //direktori gambar
  $vdir_upload = "../../../foto_berita/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 110 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 110;
  $dst_height = ($dst_width / $src_width) * $src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width, $dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im, $vdir_upload . "small_" . $fupload_name);


  //Simpan dalam versi medium 360 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width2 = 390;
  $dst_height2 = ($dst_width2 / $src_width) * $src_height;

  //proses perubahan ukuran
  $im2 = imagecreatetruecolor($dst_width2, $dst_height2);
  imagecopyresampled($im2, $im_src, 0, 0, 0, 0, $dst_width2, $dst_height2, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im2, $vdir_upload . "medium_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
  imagedestroy($im2);
}

function UploadBanner($fupload_name)
{
  //direktori banner
  $vdir_upload = "../gambar/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["gambarInformasi"]["tmp_name"], $vfile_upload);
}


// Upload file untuk download file
function UploadFile($fupload_name)
{
  //direktori file
  $vdir_upload = "../../../files/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan file
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}


// Upload gambar untuk album galeri foto
function UploadAlbum($fupload_name)
{
  //direktori gambar
  $vdir_upload = "../../../img_album/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 120 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 120;
  $dst_height = ($dst_width / $src_width) * $src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width, $dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im, $vdir_upload . "kecil_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
}


// Upload gambar untuk galeri foto
function UploadGallery($fupload_name)
{
  //direktori gambar
  $vdir_upload = "../../../img_galeri/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 100 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 100;
  $dst_height = ($dst_width / $src_width) * $src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width, $dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im, $vdir_upload . "kecil_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
}


// Upload gambar untuk sekilas info
function UploadInfo($fupload_name)
{
  //direktori gambar
  $vdir_upload = "../../../foto_info/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 54 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 54;
  $dst_height = ($dst_width / $src_width) * $src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width, $dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im, $vdir_upload . "kecil_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
}

// Upload gambar untuk favicon
function UploadFavicon($fupload_name)
{
  //direktori favicon di root
  $vdir_upload = "../../../";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

//untuk upload logo
// Upload logo kiri
function UploadLogoKiri($fupload_name)
{
  //direktori favicon di root
  $vdir_upload = "./gambar/logo/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogokiri"]["tmp_name"], $vfile_upload);
}

// Upload gambar untuk favicon
function UploadLogoKanan($fupload_name)
{
  //direktori favicon di root
  $vdir_upload = "./gambar/logo/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogokanan"]["tmp_name"], $vfile_upload);
}

function UploadLogoBank($fupload_name)
{
  //direktori favicon di root
  $vdir_upload = "./gambar/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogobank"]["tmp_name"], $vfile_upload);
}
//uploda gambar informasi
function UploadGambar($lokasi_penyimpanan, $tmp_name, $fupload_name)
{
  //direktori gambar
  $vdir_upload = $lokasi_penyimpanan;
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($tmp_name, $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);

  //Simpan dalam versi small 110 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 110;
  $dst_height = ($dst_width / $src_width) * $src_height;

  //proses perubahan ukuran
  $im = imagecreatetruecolor($dst_width, $dst_height);
  imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im, $vdir_upload . "small_" . $fupload_name);


  //Simpan dalam versi medium 360 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width2 = 390;
  $dst_height2 = ($dst_width2 / $src_width) * $src_height;

  //proses perubahan ukuran
  $im2 = imagecreatetruecolor($dst_width2, $dst_height2);
  imagecopyresampled($im2, $im_src, 0, 0, 0, 0, $dst_width2, $dst_height2, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im2, $vdir_upload . "medium_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
  imagedestroy($im);
  imagedestroy($im2);
}
function UploadGambarKartanu( $fupload_name)
{
  //direktori gambar
  $vdir_upload = "./gambar/";
  $vfile_upload = $vdir_upload . $fupload_name;


  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogokanan"]["tmp_name"], $vfile_upload);

  //identitas file asli
  $im_src = imagecreatefromjpeg($vfile_upload);
  $src_width = imageSX($im_src);
  $src_height = imageSY($im_src);


  $dst_width2 = 390;
  $dst_height2 = ($dst_width2 / $src_width) * $src_height;

  //proses perubahan ukuran
  $im2 = imagecreatetruecolor($dst_width2, $dst_height2);
  imagecopyresampled($im2, $im_src, 0, 0, 0, 0, $dst_width2, $dst_height2, $src_width, $src_height);

  //Simpan gambar
  imagejpeg($im2, $vdir_upload . "medium_" . $fupload_name);

  //Hapus gambar di memori komputer
  imagedestroy($im_src);
 
  imagedestroy($im2);
  unlink($vfile_upload);

}

function UploadFotoSelfie($fupload_name){
  //direktori favicon di root
  $vdir_upload = "./foto_absen/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogokiri"]["tmp_name"], $vfile_upload);
}

// Upload gambar untuk favicon
function UploadFotoLampiran($fupload_name){
  //direktori favicon di root
  $vdir_upload = "./foto_absen/";
  $vfile_upload = $vdir_upload . $fupload_name;
  $ekstensi =  array('png', 'jpg', 'jpeg', 'gif');
  $ext = pathinfo($fupload_name, PATHINFO_EXTENSION);
  // Set path folder tempat menyimpan gambarnya
  $path = "foto_absen/" . $fupload_name;
  if (!in_array($ext, $ekstensi)) {
    header("location:?view=absensiswasiswa&gagal");
  } else {
  //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["flogokanan"]["tmp_name"], $vfile_upload);
}
}
