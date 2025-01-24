<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/variabel_default.php";
	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas "));

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $instansi = $_POST['instansi'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $bertemu = $_POST['bertemu'];
    $keperluan = $_POST['keperluan'];

    // Mendapatkan foto yang dikirim dalam format base64
    if (isset($_POST['foto'])) {
        $fotoBase64 = $_POST['foto'];
        $fotoData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64));
        
        // Menyimpan gambar ke folder bukutamu
        $imageName = 'tamu_' . time() . '.png';  // Menggunakan timestamp untuk nama file
        $imagePath = 'bukutamu/' . $imageName;
        file_put_contents($imagePath, $fotoData);

        // Menyimpan data ke dalam database
        $stmt = $conn->prepare("INSERT INTO tamu (nama, instansi, no_telp, alamat, jenis_kelamin, bertemu, keperluan, foto,tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nama, $instansi, $no_telp, $alamat, $jenis_kelamin, $bertemu, $keperluan, $imageName, $waktu_sekarang );
        if ($stmt->execute()) {
            $status = 'sukses'; // Jika berhasil
        } else {
            $status = 'gagal'; // Jika gagal
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Menambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Menambahkan Google Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

    <style>
       body {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 0;
    background-image: url('gambar/bg.png'); /* Ganti dengan path gambar Anda */
    background-size: cover;
    background-position: center center;
    background-attachment: fixed; /* Untuk membuat gambar tetap saat di-scroll */
    }

        /* Styling untuk tabel header */
        .header {
            background-color: #5f1f67;
            padding: 30px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan */
            border-radius: 8px; /* Memberikan sudut melengkung */
        }


.header h3, .header h4 {
    color: white; /* Mengubah warna teks menjadi putih */
}

/* Styling untuk kolom tengah dan waktu */
.time-display {
    font-weight: bold;
}


    </style>
</head>

<body class="bg-gray-50 py-12 px-4">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
       <table width="100%" class="header">
        <tr>
            <td width="100px" align="center">
                <img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px">
            </td>
            <td>
                <h3 align="center" style="margin-bottom:8px; font-size:25px; color: white;">
                    <?php echo $idt['nmSekolah']; ?>
                </h3>
            </td>
            <!-- Timer Waktu Terkini di sebelah kanan -->
            <td width="150px" align="center" style="font-size:20px;">
                <h4 id="currentTime" class="time-display" style="color: white;"> </h4>
            </td>
        </tr>
    </table>


        <script>
            // Fungsi untuk memperbarui waktu setiap detik
            function updateTime() {
                const currentTimeElement = document.getElementById('currentTime');

                // Ambil waktu saat ini
                const now = new Date();

                // Format waktu (Jam:Menit:Detik)
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                // Menampilkan waktu di elemen dengan id 'currentTime'
                currentTimeElement.textContent = hours + ':' + minutes + ':' + seconds + ' WIB';
            }

            // Panggil fungsi updateTime setiap detik
            setInterval(updateTime, 1000);

            // Panggil sekali agar waktu tampil saat pertama kali halaman dimuat
            updateTime();
        </script>

        <br>
        <h1 class="text-2xl font-bold text-center mb-6">REGISTER TAMU</h1>

        <form action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
            <!-- Kolom Kiri (Form) -->
            <div class="space-y-2">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Kolom Kiri (Form - Kiri) -->
                    <div>
                        <!-- Input Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" id="nama" name="nama" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <!-- Input Asal Instansi -->
                        <div>
                            <label for="instansi" class="block text-sm font-medium text-gray-700">Asal Instansi</label>
                            <input type="text" id="instansi" name="instansi" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                       
                       
                        
                    </div>

                    <!-- Kolom Kanan (Form - Kanan) -->
                    <div>
                         <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700">No Telp</label>
                            <input type="tel" id="no_telp" name="no_telp" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                         <!-- Input Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                      

                       
                    </div>
                    
                </div>
               <div>
                            <label for="bertemu" class="block text-sm font-medium text-gray-700">Bertemu dengan</label>
                            <input type="text" id="bertemu" name="bertemu" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                         <div class="grid grid-cols-2 gap-4">
                    <!-- Kolom Kiri (Form - Kiri) -->
                    <div>
                       
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                        </div>
                          
                       
                        
                    </div>

                    <!-- Kolom Kanan (Form - Kanan) -->
                    <div>
                         

                        <!-- Input Keperluan -->
                        <div>
                            <label for="keperluan" class="block text-sm font-medium text-gray-700">Keperluan</label>
                            <textarea id="keperluan" name="keperluan" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                        </div>
                    </div>
                     
                </div>
            </div>

            <!-- Kolom Kanan (Tombol Foto) -->
            <div class="flex justify-center items-center">
                <div class="space-y-4 text-center">
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                    <div class="relative">
                        <!-- Tombol Ambil Foto (Awal, tersembunyi setelah diklik pertama kali) -->
                        <button type="button" id="startCameraBtn" class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" onclick="startCamera()">
                            <i class="fas fa-camera"></i> Mulai Ambil Foto
                        </button>

                        <!-- Video stream dari kamera -->
                        <video id="video" class="hidden" width="320" height="240" autoplay></video>
                        <!-- Canvas untuk menampilkan foto -->
                        <canvas id="canvas" class="hidden" width="320" height="240"></canvas>
                        <input type="hidden" id="foto" name="foto" />
                    </div>
                    <!-- Tombol Ambil Foto (kedua, hanya muncul setelah yang pertama diklik) -->
                    <div class="mt-4">
                        <button type="button" id="takePhotoBtn" class="px-6 py-2 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-green-500 hidden" onclick="takePhoto()"> <i class="fas fa-camera"></i> Ambil Foto</button>
                    </div>
                    <!-- Tempat untuk menampilkan foto yang diambil -->
                    <div id="photoPreview" class="mt-4">
                        <img id="photoImg" class="hidden" width="320" height="240" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-span-2 text-center mt-6">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Simpan</button>
            </div>
        </form>
    </div>
<script>
    // Mengecek status yang dikirim dari PHP
    <?php if ($status == 'sukses') { ?>
        Swal.fire({
            icon: 'success',
            title: 'Data Berhasil Disimpan!',
            text: 'Terima kasih telah mengisi buku tamu.',
            confirmButtonText: 'OK'
        }).then(function() {
            // Melakukan refresh halaman setelah menekan tombol OK
            window.location.href = 'bukutamu';
        });
    <?php } elseif ($status == 'gagal') { ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menyimpan Data!',
            text: 'Terjadi kesalahan saat menyimpan data, silakan coba lagi.',
            confirmButtonText: 'OK'
        }).then(function() {
            // Melakukan refresh halaman setelah menekan tombol OK
            window.location.href = 'bukutamu';
        });
    <?php } ?>
</script>

    <script>
        // Mengaktifkan kamera
        function startCamera() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const startCameraBtn = document.getElementById('startCameraBtn');
            const takePhotoBtn = document.getElementById('takePhotoBtn');
            const fotoInput = document.getElementById('foto');
            const photoPreview = document.getElementById('photoPreview');
            const photoImg = document.getElementById('photoImg');

            // Meminta akses ke kamera perangkat
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    // Sembunyikan tombol mulai ambil foto
                    startCameraBtn.classList.add('hidden');
                    // Tampilkan tombol ambil foto setelah kamera aktif
                    takePhotoBtn.classList.remove('hidden');
                })
                .catch(function (err) {
                    console.log("Error: " + err);
                });
        }

        // Menangkap foto dari video
        window.takePhoto = function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            const fotoInput = document.getElementById('foto');
            const photoPreview = document.getElementById('photoPreview');
            const photoImg = document.getElementById('photoImg');

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Menyimpan foto sebagai base64 untuk dikirim ke server
            fotoInput.value = canvas.toDataURL('image/png');

            // Menampilkan foto di kotak preview
            const imageUrl = fotoInput.value;
            photoImg.src = imageUrl;
            photoImg.classList.remove('hidden');

            // Menghentikan stream setelah foto diambil
            let stream = video.srcObject;
            let tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
            video.classList.add('hidden');
        }
    </script>
</body>
</html>
