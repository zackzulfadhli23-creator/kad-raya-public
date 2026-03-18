<?php
require 'db_config.php';

$id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';

if (empty($id)) {
    die("Kad tidak dijumpai.");
}

$stmt = $conn->prepare("SELECT * FROM ucapan_raya WHERE unique_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Kad tidak dijumpai.");
}

$kad = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Tema Konfigurasi
$theme_classes = "";
$accent_color = "";
$btn_class = "";

switch ($kad['tema_warna']) {
    case 'gold':
        $theme_classes = "bg-amber-600 text-amber-50";
        $accent_color = "text-emerald-900";
        $btn_class = "bg-emerald-900 text-white hover:bg-emerald-800 shadow-emerald-900/40";
        break;
    case 'purple':
        $theme_classes = "bg-purple-900 text-purple-100";
        $accent_color = "text-amber-300";
        $btn_class = "bg-amber-500 text-purple-900 hover:bg-amber-400 shadow-amber-500/40";
        break;
    case 'emerald':
    default:
        $theme_classes = "bg-emerald-900 text-emerald-100";
        $accent_color = "text-amber-400";
        $btn_class = "bg-amber-500 text-emerald-900 hover:bg-amber-400 shadow-amber-500/40";
        break;
}

$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$whatsapp_text = urlencode("Lihat kad raya dari " . $kad['nama_pengirim'] . " untuk anda di sini:\n\n" . $current_url);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kad Raya dari <?= htmlspecialchars($kad['nama_pengirim']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gifshot/0.4.5/gifshot.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- Open Graph Tags -->
    <meta property="og:title" content="Kad Raya dari <?= htmlspecialchars($kad['nama_pengirim']) ?>">
    <meta property="og:description" content="Selamat Hari Raya Maaf Zahir & Batin! Lihat ucapan istimewa untuk anda.">
    <meta property="og:image" content="<?= !empty($kad['image_path']) ? $current_url . '/../' . $kad['image_path'] : '' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $current_url ?>">
    
    <link rel="icon" href="https://img.icons8.com/color/48/star-and-crescent.png">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #111827; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-vibes { font-family: 'Great Vibes', cursive; font-size: 2.5rem; }
        
        .card-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        .kad-wrapper {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
        }

        /* Falling stars animation */
        .star {
            position: absolute;
            color: rgba(255, 255, 255, 0.4);
            animation: fall linear infinite;
        }
        @keyframes fall {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(110vh) rotate(360deg); opacity: 0; }
        }

        .gold-border {
            border: 2px solid rgba(245, 158, 11, 0.3);
            outline: 1px solid rgba(245, 158, 11, 0.6);
            outline-offset: -6px;
        }

        #loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
            backdrop-filter: blur(4px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(245, 158, 11, 0.2);
            border-top: 4px solid #f59e0b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- CSS Animation Elements -->
    <div id="stars-container" class="absolute inset-0 pointer-events-none overflow-hidden z-0"></div>

    <div id="loading-overlay" class="hidden">
        <div class="spinner mb-4"></div>
        <p class="text-amber-400 font-medium animate-pulse" id="loading-text">Sedang memproses...</p>
    </div>

    <div class="card-container z-10">
        <div class="max-w-md w-full animate__animated animate__fadeInUp">
            <!-- Card -->
            <div id="kad-to-capture" class="<?= $theme_classes ?> kad-wrapper rounded-2xl shadow-2xl p-8 text-center gold-border relative overflow-hidden backdrop-blur-sm">
                
                <!-- Decorative Frame -->
                <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-amber-400/50 rounded-tl-xl pointer-events-none"></div>
                <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-amber-400/50 rounded-tr-xl pointer-events-none"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-amber-400/50 rounded-bl-xl pointer-events-none"></div>
                <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-amber-400/50 rounded-br-xl pointer-events-none"></div>

                <!-- Audio Toggle -->
                <button id="music-toggle" class="absolute top-4 right-4 z-30 p-2 rounded-full bg-black/30 text-amber-400 hover:bg-black/50 transition-all border border-amber-400/30">
                    <svg id="music-on-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    <svg id="music-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                </button>
                <audio id="bg-music" loop>
                    <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg">
                </audio>

                <div class="mb-6 mt-4 opacity-90 animate__animated animate__pulse animate__infinite animate__slower">
                    <svg class="mx-auto w-16 h-16 <?= $accent_color ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" /></svg>
                </div>
                
                <p class="text-sm font-medium tracking-widest uppercase mb-2 opacity-80">Untuk</p>
                <h2 class="text-2xl font-bold mb-8 <?= $accent_color ?>"><?= nl2br(htmlspecialchars($kad['nama_penerima'])) ?></h2>
                
                <h1 class="font-playfair text-3xl md:text-4xl mb-6 leading-tight">Selamat Hari Raya <br><span class="text-lg md:text-xl font-normal opacity-90">Maaf Zahir & Batin</span></h1>
                
                <?php if (!empty($kad['image_path']) && file_exists($kad['image_path'])): ?>
                    <div class="mb-6 rounded-xl overflow-hidden shadow-lg border-2 border-amber-400/30 max-h-64 flex justify-center items-center bg-black/20">
                        <img src="<?= htmlspecialchars($kad['image_path']) ?>" alt="Gambar Kenangan" class="max-w-full max-h-64 object-contain">
                    </div>
                <?php endif; ?>
                
                <div class="relative py-6 hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-current to-transparent opacity-20"></div>
                    <p class="text-base md:text-lg leading-relaxed font-medium"><?= nl2br(htmlspecialchars(stripcslashes($kad['mesej']))) ?></p>
                    <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-current to-transparent opacity-20"></div>
                </div>

                <div class="mt-8">
                    <p class="text-sm opacity-80 mb-1">Ikhlas daripada,</p>
                    <p class="font-vibes <?= $accent_color ?> hover:text-white transition-colors cursor-default"><?= htmlspecialchars($kad['nama_pengirim']) ?></p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 space-y-3 px-2 z-20 relative">
                <a href="https://wa.me/?text=<?= $whatsapp_text ?>" target="_blank" class="w-full flex justify-center items-center gap-2 py-3.5 rounded-xl font-semibold transition-all shadow-lg transform hover:-translate-y-1 bg-[#25D366] text-white hover:bg-[#128C7E]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    Kongsi ke WhatsApp
                </a>
                
                <button onclick="copyLink()" class="w-full flex justify-center items-center gap-2 py-3.5 rounded-xl font-semibold transition-all shadow-lg transform hover:-translate-y-1 <?= $btn_class ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Salin Link Kad
                </button>
                <div id="copy-toast" class="hidden text-center text-sm text-emerald-400 mt-2 font-medium bg-black/50 py-1 rounded-full backdrop-blur-sm">Link berjaya disalin!</div>
                
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button onclick="downloadImage()" class="flex justify-center items-center gap-2 py-3 px-4 rounded-xl font-semibold transition-all shadow-lg transform hover:-translate-y-1 bg-blue-600 text-white hover:bg-blue-500 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Simpan Gambar
                    </button>
                    <button onclick="downloadGif()" class="flex justify-center items-center gap-2 py-3 px-4 rounded-xl font-semibold transition-all shadow-lg transform hover:-translate-y-1 bg-amber-600 text-white hover:bg-amber-500 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Simpan GIF
                    </button>
                </div>
                
                <div class="mt-6 flex flex-col items-center gap-4 border-t border-gray-700/30 pt-6">
                    <p class="text-xs text-gray-400 font-medium">Scan untuk lihat di telefon</p>
                    <div id="qrcode" class="bg-white p-2 rounded-lg shadow-inner"></div>
                </div>

                <a href="index.php" class="block text-center text-gray-400 text-sm hover:text-white mt-6 pt-4 border-t border-gray-800 transition-colors">Bina Kad Anda Sendiri</a>
            </div>
        </div>
    </div>

    <script>
        window.onerror = function(msg, url, line) {
            console.error("Global Error: " + msg + " at " + url + ":" + line);
            // alert("Ralat dikesan: " + msg); // Uncomment for aggressive debugging
            return false;
        };

        function copyLink() {
            navigator.clipboard.writeText("<?= $current_url ?>").then(() => {
                const toast = document.getElementById('copy-toast');
                toast.classList.remove('hidden');
                toast.classList.add('animate__animated', 'animate__fadeIn');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('animate__animated', 'animate__fadeIn');
                }, 3000);
            });
        }

        async function downloadImage() {
            const kad = document.getElementById('kad-to-capture');
            const overlay = document.getElementById('loading-overlay');
            const loadingText = document.getElementById('loading-text');
            
            overlay.classList.remove('hidden');
            loadingText.innerText = "Sila tunggu, sedang menjana gambar...";

            try {
                const canvas = await html2canvas(kad, {
                    scale: 1.5, // Slightly reduced scale for mobile performance
                    useCORS: true,
                    logging: false,
                    allowTaint: true
                });
                
                const link = document.createElement('a');
                link.download = 'KadRaya-<?= $kad['nama_pengirim'] ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            } catch (err) {
                console.error(err);
                alert("Kesalahan berlaku: " + err.message);
            } finally {
                overlay.classList.add('hidden');
            }
        }

        async function downloadGif() {
            const kad = document.getElementById('kad-to-capture');
            const overlay = document.getElementById('loading-overlay');
            const loadingText = document.getElementById('loading-text');
            const audioToggle = document.getElementById('music-toggle');
            
            overlay.classList.remove('hidden');
            loadingText.innerText = "Merakam Animasi (Sila tunggu)...";
            if(audioToggle) audioToggle.style.display = 'none';

            const frames = [];
            const maxFrames = 8; // Reduced for mobile memory
            const captureInterval = 250; 

            try {
                for (let i = 0; i < maxFrames; i++) {
                    loadingText.innerText = `Merakam: ${Math.round(((i + 1) / maxFrames) * 100)}%`;
                    const canvas = await html2canvas(kad, {
                        scale: 0.8, // Reduced scale for GIF speed
                        useCORS: true,
                        allowTaint: true
                    });
                    frames.push(canvas.toDataURL('image/png'));
                    await new Promise(resolve => setTimeout(resolve, captureInterval));
                }

                loadingText.innerText = "Menjana fail GIF...";

                gifshot.createGIF({
                    images: frames,
                    gifWidth: 350, // Fixed width for mobile efficiency
                    gifHeight: (kad.offsetHeight / kad.offsetWidth) * 350,
                    interval: 0.2,
                    numFrames: maxFrames,
                    frameDuration: 1
                }, function(obj) {
                    if(audioToggle) audioToggle.style.display = 'block';
                    if (!obj.error) {
                        const link = document.createElement('a');
                        link.download = 'KadRaya-<?= $kad['nama_pengirim'] ?>.gif';
                        link.href = obj.image;
                        link.click();
                        overlay.classList.add('hidden');
                    } else {
                        overlay.classList.add('hidden');
                        alert("Gagal menjana GIF: " + obj.errorMsg);
                    }
                });
            } catch (err) {
                if(audioToggle) audioToggle.style.display = 'block';
                overlay.classList.add('hidden');
                alert("Ralat: " + err.message);
            }
        }

        // QR Code & Music Logic
        document.addEventListener("DOMContentLoaded", function() {
            // QR Code
            new QRCode(document.getElementById("qrcode"), {
                text: "<?= $current_url ?>",
                width: 80,
                height: 80,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            // Music Toggle
            const audio = document.getElementById('bg-music');
            const musicToggle = document.getElementById('music-toggle');
            const onIcon = document.getElementById('music-on-icon');
            const offIcon = document.getElementById('music-off-icon');

            musicToggle.addEventListener('click', () => {
                if (audio.paused) {
                    audio.play();
                    onIcon.classList.add('hidden');
                    offIcon.classList.remove('hidden');
                } else {
                    audio.pause();
                    onIcon.classList.remove('hidden');
                    offIcon.classList.add('hidden');
                }
            });
        });

        // Generate falling stars
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('stars-container');
            const starCount = 20;
            const symbols = ['✦', '★', '❈', '✨'];
            
            for(let i=0; i<starCount; i++) {
                const star = document.createElement('div');
                star.className = 'star text-xs md:text-sm absolute';
                star.innerHTML = symbols[Math.floor(Math.random() * symbols.length)];
                
                // Random properties
                const left = Math.random() * 100;
                const duration = 5 + Math.random() * 10;
                const delay = Math.random() * 5;
                const size = 0.5 + Math.random() * 1;
                
                star.style.left = left + '%';
                star.style.animationDuration = duration + 's';
                star.style.animationDelay = delay + 's';
                star.style.transform = `scale(${size})`;
                
                container.appendChild(star);
            }
        });
    </script>
</body>
</html>
