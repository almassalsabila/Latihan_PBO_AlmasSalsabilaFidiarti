<?php
require_once 'Tiket.php';

class TiketVelvet extends Tiket {
    // Properti spesifik terenkapsulasi
    private $bantalSelimutPack;
    private $layananButler;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $bantalSelimutPack, $layananButler) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler = $layananButler;
    }

    // [TAHAP 5] Override: Surcharge kelas premium sebesar 50% (dikali 1.50)
    public function hitungTotalHarga() {
        return ($this->jumlah_kursi * $this->hargaDasarTiket) * 1.50;
    }

    // Override: Menampilkan info fasilitas Velvet
    public function tampilkanInfoFasilitas() {
        $statusBantal = $this->bantalSelimutPack ? "Disediakan" : "Tidak Disediakan";
        $statusButler = $this->layananButler ? "Tersedia" : "Tidak Tersedia";
        
        return "Fasilitas Velvet: Bantal & Selimut {$statusBantal}, Layanan Pemesanan Makanan oleh Butler {$statusButler}.";
    }
}
?>