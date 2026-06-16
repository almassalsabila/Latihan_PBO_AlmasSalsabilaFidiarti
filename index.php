<?php
// 1. Mengimport semua file yang dibutuhkan
require_once 'database.php'; 
require_once 'tiketReguler.php';
require_once 'tiketIMAX.php';
require_once 'tiketVelvet.php';

// 2. Inisialisasi Koneksi Database
$database = new Database();
$db = $database->getConnection();

// 3. Query untuk mengambil seluruh data tiket
$query = "SELECT * FROM tabel_tiket ORDER BY jadwal_tayang ASC";
$stmt = $db->prepare($query);
$stmt->execute();

// 4. Wadah untuk mengelompokkan objek tiket berdasarkan jenis studio
$studioGroups = [
    'reguler' => [],
    'IMAX'    => [],
    'velvet'  => []
];

// Total konter untuk statistik dashboard
$totalTiketTerpesan = 0;
$totalPendapatan = 0;

// 5. Looping Data & Transformasi ke dalam Objek Polimorfisme
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $jenis = $row['jenis_studio'];
    $objekTiket = null;
    
    if ($jenis == 'reguler') {
        $objekTiket = new TiketReguler(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['tipe_audio'], $row['lokasi_baris']
        );
    } elseif ($jenis == 'IMAX') {
        $objekTiket = new TiketIMAX(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['kacamata_3d_id'], $row['efek_gerak_fitur']
        );
    } elseif ($jenis == 'velvet') {
        $objekTiket = new TiketVelvet(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['bantal_selimut_pack'], $row['layanan_butler']
        );
    }

    if ($objekTiket) {
        $studioGroups[$jenis][] = $objekTiket;
        $totalTiketTerpesan += $objekTiket->getJumlahKursi();
        $totalPendapatan += $objekTiket->hitungTotalHarga();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineTech Dashboard - Almas Salsabila</title>
    <style>
        /* IMPLEMENTASI PALET WARNA BARU */
        :root {
            --bg-black: #000000;         /* Hitam murni untuk background utama */
            --brand-red: #8E1616;        /* Merah marun untuk aksen & button aktif */
            --accent-gold: #E8C999;      /* Krem emas untuk teks highlight & border */
            --text-light: #F8EEDF;       /* Putih gading hangat untuk teks utama */
            --bg-card: #141414;          /* Hitam abu-abu tipis agar card terlihat */
        }

        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background-color: var(--bg-black); 
            color: var(--text-light); 
            margin: 0; 
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR NAVIGATION (MENU SEBELAH KIRI) */
        .sidebar {
            width: 260px;
            background-color: #0a0a0a;
            border-right: 2px solid var(--brand-red);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            box-sizing: border-box;
        }

        .sidebar h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--accent-gold);
            margin: 0 0 10px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar .admin-tag {
            font-size: 11px;
            color: var(--brand-red);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }

        .navigation-menu {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .menu-btn {
            background: transparent;
            color: var(--text-light);
            border: 1px solid rgba(232, 201, 153, 0.2);
            padding: 14px 20px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .menu-btn:hover {
            color: var(--accent-gold);
            border-color: var(--accent-gold);
            background: rgba(142, 22, 22, 0.1);
        }

        .menu-btn.active {
            background: var(--brand-red);
            color: #fff;
            border-color: var(--brand-red);
            box-shadow: 0 4px 15px rgba(142, 22, 22, 0.4);
        }

        /* MAIN CONTENT AREA (SEBELAH KANAN) */
        .main-content {
            margin-left: 260px; /* Memberi ruang untuk sidebar */
            flex-1: 1;
            padding: 40px;
            width: calc(100% - 260px);
            box-sizing: border-box;
        }

        /* STATS GRID COMPONENT */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: var(--bg-card);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(232, 201, 153, 0.1);
            border-left: 5px solid var(--brand-red);
        }

        .stat-card h3 { margin: 0 0 8px 0; font-size: 12px; color: var(--accent-gold); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-card .value { font-size: 24px; font-weight: bold; color: #fff; }

        /* STUDIO SECTIONS */
        .studio-section { 
            background-color: var(--bg-card);
            border: 1px solid rgba(232, 201, 153, 0.1);
            border-radius: 16px; 
            padding: 25px; 
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .studio-header { 
            font-size: 18px; 
            font-weight: 800; 
            padding-bottom: 12px; 
            margin-bottom: 20px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid var(--brand-red);
            color: var(--accent-gold);
            letter-spacing: 0.5px;
        }
        
        .count-badge {
            font-size: 11px;
            background-color: var(--brand-red);
            color: #fff;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: bold;
        }

        /* TABLES STYLING */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        
        th { 
            padding: 12px 15px; 
            color: var(--accent-gold); 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 11px;
            letter-spacing: 0.5px;
            text-align: left;
        }
        
        td { 
            padding: 15px; 
            background-color: rgba(20, 20, 20, 0.8);
            border-top: 1px solid rgba(232, 201, 153, 0.05);
            border-bottom: 1px solid rgba(232, 201, 153, 0.05);
        }

        td:first-child { border-left: 1px solid rgba(232, 201, 153, 0.05); border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        td:last-child { border-right: 1px solid rgba(232, 201, 153, 0.05); border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        
        tr:hover td { 
            background-color: rgba(142, 22, 22, 0.08); 
            border-color: rgba(232, 201, 153, 0.2);
        }
        
        /* BADGES & HIGHLIGHTS */
        .id-badge { 
            background: #000; 
            border: 1px solid var(--brand-red);
            padding: 5px 10px; 
            border-radius: 6px; 
            font-family: monospace;
            font-size: 12px; 
            color: var(--accent-gold);
        }

        .film-title { font-size: 15px; font-weight: 600; color: #fff; }
        
        .facility-text { 
            color: var(--text-light); 
            font-size: 13px; 
            background: #000;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            border-left: 3px solid var(--accent-gold);
        }

        .total-harga { 
            font-weight: 700; 
            color: var(--accent-gold); 
            font-size: 15px; 
        }

        .text-empty { color: rgba(248, 238, 223, 0.4); font-style: italic; text-align: center; padding: 20px; }
    </style>
</head>
<body>

    <!-- SIDEBAR LEFT (MENU KIRI) -->
    <div class="sidebar">
        <h2>CineTech</h2>
        <div class="admin-tag">Almas Salsabila</div>
        
        <div class="navigation-menu">
            <button class="menu-btn active" onclick="filterStudio('all', this)">Semua Studio</button>
            <button class="menu-btn" onclick="filterStudio('reguler', this)">Studio Reguler</button>
            <button class="menu-btn" onclick="filterStudio('IMAX', this)">Studio IMAX</button>
            <button class="menu-btn" onclick="filterStudio('velvet', this)">Studio Velvet</button>
        </div>
    </div>

    <!-- MAIN CONTENT RIGHT (KONTEN KANAN) -->
    <div class="main-content">
        
        <!-- DASHBOARD STATS CARD -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Kategori Kelas</h3>
                <div class="value">3 Turunan</div>
            </div>
            <div class="stat-card" style="border-left-color: var(--accent-gold);">
                <h3>Kursi Terpesan</h3>
                <div class="value"><?php echo $totalTiketTerpesan; ?> Kursi</div>
            </div>
            <div class="stat-card" style="border-left-color: #fff;">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></div>
            </div>
        </div>

        <!-- MAIN LOOP UNTUK SETIAP KATEGORI STUDIO -->
        <?php foreach ($studioGroups as $jenisStudio => $daftarTiket): ?>
            <div class="studio-section" data-studio="<?php echo $jenisStudio; ?>">
                
                <div class="studio-header">
                    <span>STUDIO <?php echo strtoupper($jenisStudio); ?></span>
                    <span class="count-badge"><?php echo count($daftarTiket); ?> Tiket</span>
                </div>

                <?php if (empty($daftarTiket)): ?>
                    <div class="text-empty">Belum ada antrean data pemesanan di studio ini.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 10%">ID Tiket</th>
                                    <th style="width: 25%">Nama Film</th>
                                    <th style="width: 15%">Jadwal Tayang</th>
                                    <th style="width: 10%">Jumlah</th>
                                    <th style="width: 12%">Harga Dasar</th>
                                    <th style="width: 28%">Spesifikasi Fasilitas (Polimorfik)</th>
                                    <th style="width: 15%">Total Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftarTiket as $tiket): ?>
                                    <tr>
                                        <td><span class="id-badge"><?php echo $tiket->getIdTiket(); ?></span></td>
                                        <td><span class="film-title"><?php echo $tiket->getNamaFilm(); ?></span></td>
                                        <td><small><?php echo date('d M Y, H:i', strtotime($tiket->getJadwalTayang())); ?> WIB</small></td>
                                        <td><strong><?php echo $tiket->getJumlahKursi(); ?></strong> Kursi</td>
                                        <td><small>Rp <?php echo number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?></small></td>
                                        
                                        <!-- POLIMORFISME METODE -->
                                        <td>
                                            <div class="facility-text">
                                                <?php echo $tiket->tampilkanInfoFasilitas(); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="total-harga">
                                                Rp <?php echo number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- SCRIPT FILTER DINAMIS -->
    <script>
    function filterStudio(type, button) {
        // 1. Ubah status tombol aktif di sidebar
        const buttons = document.querySelectorAll('.menu-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // 2. Sembunyikan atau Tampilkan Section Studio di sebelah kanan
        const sections = document.querySelectorAll('.studio-section');
        sections.forEach(section => {
            if (type === 'all' || section.getAttribute('data-studio') === type) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
    </script>

</body>
</html>