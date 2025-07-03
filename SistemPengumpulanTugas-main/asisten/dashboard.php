<?php
// 1. Definisi Variabel untuk Template
$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// 2. Panggil Header
require_once 'templates/header.php';
require_once '../config.php'; // Tambahkan ini

// --- MULAI LOGIKA PENGAMBILAN DATA ---

// 1. Hitung total modul
$total_modul = $conn->query("SELECT COUNT(id) as total FROM modul")->fetch_assoc()['total'];

// 2. Hitung total laporan masuk
$total_laporan = $conn->query("SELECT COUNT(id) as total FROM pengumpulan_laporan")->fetch_assoc()['total'];

// 3. Hitung laporan belum dinilai
$laporan_belum_dinilai = $conn->query("SELECT COUNT(id) as total FROM pengumpulan_laporan WHERE nilai IS NULL")->fetch_assoc()['total'];

// 4. Ambil aktivitas terbaru
$sql_aktivitas = "SELECT u.nama, m.judul_modul, pl.tanggal_pengumpulan
                  FROM pengumpulan_laporan pl
                  JOIN users u ON pl.mahasiswa_id = u.id
                  JOIN modul m ON pl.modul_id = m.id
                  ORDER BY pl.tanggal_pengumpulan DESC LIMIT 5";
$aktivitas_terbaru = $conn->query($sql_aktivitas);


// --- AKHIR LOGIKA PENGAMBILAN DATA ---
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-blue-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Modul</p>
            <p class="text-2xl font-bold text-gray-800"><?php echo $total_modul; ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-green-100 p-3 rounded-full">
             <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Laporan Masuk</p>
            <p class="text-2xl font-bold text-gray-800"><?php echo $total_laporan; ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-yellow-100 p-3 rounded-full">
             <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Laporan Belum Dinilai</p>
            <p class="text-2xl font-bold text-gray-800"><?php echo $laporan_belum_dinilai; ?></p>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-md mt-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Laporan Terbaru</h3>
    <div class="space-y-4">
        <?php if ($aktivitas_terbaru->num_rows > 0): ?>
            <?php while($aktivitas = $aktivitas_terbaru->fetch_assoc()): ?>
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                    <span class="font-bold text-gray-500"><?php echo strtoupper(substr($aktivitas['nama'], 0, 2)); ?></span>
                </div>
                <div>
                    <p class="text-gray-800"><strong><?php echo htmlspecialchars($aktivitas['nama']); ?></strong> mengumpulkan laporan untuk <strong><?php echo htmlspecialchars($aktivitas['judul_modul']); ?></strong></p>
                    <p class="text-sm text-gray-500"><?php echo date('d M Y, H:i', strtotime($aktivitas['tanggal_pengumpulan'])); ?> WIB</p>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
             <p class="text-gray-500">Belum ada laporan yang masuk.</p>
        <?php endif; ?>
    </div>
</div>

<?php
// 3. Panggil Footer
require_once 'templates/footer.php';
?>