<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi QR Code</title>
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.18.6/umd/index.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg p-4 bg-white shadow-xl rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center">Absensi QR Code</h1>

        <!-- Video Preview -->
        <div class="relative rounded-lg overflow-hidden mb-4">
            <video id="qr-video" class="w-full border border-gray-300 rounded-lg" autoplay muted playsinline></video>
            <div id="overlay" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center text-white hidden">
                <p>Memproses...</p>
            </div>
        </div>

        <!-- Output -->
        <div class="mb-4">
            <label for="result" class="block text-gray-700 font-medium mb-2">Hasil Scan</label>
            <input id="result" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
        </div>

        <!-- Actions -->
        <div class="flex justify-between">
            <button id="start-button" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">Mulai</button>
            <button id="stop-button" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">Hentikan</button>
        </div>
    </div>

    <script>
        const video = document.getElementById('qr-video');
        const result = document.getElementById('result');
        const startButton = document.getElementById('start-button');
        const stopButton = document.getElementById('stop-button');
        const overlay = document.getElementById('overlay');

        let codeReader = null;
        let stream = null;

        async function startScanning() {
            try {
                // Periksa apakah browser mendukung API getUserMedia
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert("Browser Anda tidak mendukung kamera atau fitur getUserMedia.");
                    return;
                }

                // Inisialisasi pembaca kode QR
                if (codeReader === null) {
                    codeReader = new ZXing.BrowserQRCodeReader();
                }

                // Dapatkan stream video dari kamera
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                video.srcObject = stream;

                overlay.classList.add('hidden');
                result.value = ""; // Reset hasil sebelumnya

                codeReader.decodeFromVideoDevice(undefined, 'qr-video', (result, error) => {
                    if (result) {
                        overlay.classList.remove('hidden');
                        document.getElementById('result').value = result.text;
                        stopScanning(); // Hentikan scanning setelah hasil ditemukan
                    }
                    if (error) {
                        console.error(error);
                    }
                });
            } catch (error) {
                alert('Tidak dapat mengakses kamera: ' + error.message);
            }
        }

        function stopScanning() {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach((track) => track.stop());
                video.srcObject = null;
                if (codeReader) {
                    codeReader.reset();
                }
                overlay.classList.add('hidden');
            }
        }

        // Event listeners
        startButton.addEventListener('click', startScanning);
        stopButton.addEventListener('click', stopScanning);

        // Bersihkan stream kamera saat halaman ditutup
        window.addEventListener('unload', stopScanning);
    </script>
</body>
</html>
