<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Kad Ucapan Hari Raya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-playfair { font-family: 'Playfair Display', serif; }
        .bg-pattern {
            background-color: #064e3b;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23042f23' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full border border-gray-100">
        <div class="bg-pattern p-8 text-center relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full border-4 border-amber-500/30"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 rounded-full border-4 border-amber-500/30"></div>
            
            <h1 class="text-3xl font-playfair text-amber-400 mb-2 mt-4 relative z-10 transition-transform hover:scale-105">Selamat Hari Raya</h1>
            <p class="text-emerald-100 text-sm opacity-90 relative z-10">Maaf Zahir & Batin</p>
        </div>
        
        <div class="p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Jana Kad Digital Anda</h2>
            <form action="process.php" method="POST" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengirim</label>
                    <input type="text" name="pengirim" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-gray-50" placeholder="Contoh: Ahmad">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                    <input type="text" name="penerima" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-gray-50" placeholder="Contoh: Keluarga Tersayang">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mesej Ucapan</label>
                    <textarea name="mesej" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-gray-50 resize-none" placeholder="Tulis ucapan ikhlas anda di sini..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tema Warna</label>
                    <select name="tema" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-gray-50 appearance-none">
                        <option value="emerald">Emerald Green (Klasik)</option>
                        <option value="gold">Royal Gold (Mewah)</option>
                        <option value="purple">Soft Purple (Elegan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Muat Naik Gambar (Pilihan)</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-gray-50 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-gray-500 mt-1">Sokongan format: JPG, PNG, GIF (Max: 5MB)</p>
                </div>
                <button type="submit" class="w-full bg-emerald-700 text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-emerald-700/30 hover:bg-emerald-800 hover:shadow-emerald-800/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-lg flex justify-center items-center gap-2">
                    Jana Kad Raya <span class="text-amber-400">✨</span>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
