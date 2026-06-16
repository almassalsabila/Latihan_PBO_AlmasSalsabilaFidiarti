<?php
// Mengimport semua file yang dibutuhkan
require_once 'database.php';
require_once 'tiketReguler.php';
require_once 'tiketIMAX.php';
require_once 'tiketVelvet.php';

// 1. Inisialisasi Koneksi Database
$database = new Database();
$db = $database->getConnection();

// 2. Query untuk mengambil seluruh data tiket
$query = "SELECT * FROM tabel_tiket ORDER BY jadwal_tayang ASC";
$stmt = $db->prepare($query);
$stmt->execute();

// 3. Wadah untuk mengelompokkan objek tiket berdasarkan jenis studio
$studioGroups = [
    'reguler' => [],
    'IMAX'    => [],
    'velvet'  => []
];

// 4. Looping Data & Transformasi ke dalam Objek Polimorfisme
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $jenis = $row['jenis_studio'];
    
    if ($jenis == 'reguler') {
        $studioGroups['reguler'][] = new TiketReguler(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['tipe_audio'], $row['lokasi_baris']
        );
    } elseif ($jenis == 'IMAX') {
        $studioGroups['IMAX'][] = new TiketIMAX(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['kacamata_3d_id'], $row['efek_gerak_fitur']
        );
    } elseif ($jenis == 'velvet') {
        $studioGroups['velvet'][] = new TiketVelvet(
            $row['id_tiket'], $row['nama_film'], $row['jadwal_tayang'], 
            $row['jumlah_kursi'], $row['harga_dasar_tiket'], 
            $row['bantal_selimut_pack'], $row['layanan_butler']
        );
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Tiket Bioskop - Almas</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #121212; color: #e0e0e0; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { text-align: center; color: #ffc107; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; }
        
        /* Styling Kelompok Studio */
        .studio-section { margin-bottom: 50px; background: #1e1e1e; border-radius: 8px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .studio-title { font-size: 24px; font-weight: bold; padding-bottom: 10px; margin-bottom: 20px; border-bottom: 3px solid; }
        
        /* Warna Khusus Tiap Studio */
        .title-reguler { color: #00bcd4; border-color: #00bcd4; }
        .title-imax { color: #ff9800; border-color: #ff9800; }
        .title-velvet { color: #e91e63; border-color: #e91e63; }
        
        /* Styling Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background-color: #252525; border-radius: 6px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; }
        th { background-color: #333; color: #fff; font-weight: 600; text-transform: uppercase; font-size: 13px; }
        tr { border-bottom: 1px solid #3d3d3d; }
        tr:last-child { border-bottom: none; }
        tr:hover { background-color: #2d2d2d; }
        
        /* Badge & Harga */
        .badge { background: #333; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .total-harga { font-weight: bold; color: #2ecc71; font-size: 15px; }
        .text-muted { color: #888; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <h1>Daftar Pesanan Tiket Bioskop</h1>

    <?php foreach ($studioGroups as $jenisStudio => $daftarTiket): ?>
        <div class="studio-section">
            <div class="studio-title title-<?php echo strtolower($jenisStudio); ?>">
                STUDIO <?php echo strtoupper($jenisStudio); ?>
            </div>

            <?php if (empty($daftarTiket)): ?>
                <p class="text-muted">Belum ada pesanan tiket untuk tipe studio ini.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Tiket</th>
                            <th>Nama Film</th>
                            <th>Jadwal Tayang</th>
                            <th>Jumlah</th>
                            <th>Harga Dasar</th>
                            <th>Fasilitas Spesifik (Polimorfik)</th>
                            <th>Total Harga (Polimorfik)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarTiket as $tiket): ?>
                            <tr>
                                <td><span class="badge"><?php echo $tiket->getIdTiket(); ?></span></td>
                                <td><strong><?php echo $tiket->getNamaFilm(); ?></strong></td>
                                <td><?php echo date('d M Y - H:i', strtotime($tiket->getJadwalTayang())); ?> WIB</td>
                                <td><?php echo $tiket->getJumlahKursi(); ?> Kursi</td>
                                <td>Rp <?php echo number_format($tiket->getHargaDasarTiket(), 0, ',', '.'); ?></td>
                                
                                <td style="color: #ffc107; font-size: 14px;">
                                    <?php echo $tiket->tampilkanInfoFasilitas(); ?>
                                </td>
                                <td class="total-harga">
                                    Rp <?php echo number_format($tiket->hitungTotalHarga(), 0, ',', '.'); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>