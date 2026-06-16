<?php
require_once 'Tiket.php';

class TiketReguler extends Tiket {
    // Properti spesifik terenkapsulasi
    private $tipeAudio;
    private $lokasiBaris;

    // Constructor murni memetakan data induk dan anak
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $tipeAudio, $lokasiBaris) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->tipeAudio = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // [TAHAP 5] Override: Tarif standar murni tanpa biaya tambahan
    public function hitungTotalHarga() {
        return $this->jumlah_kursi * $this->hargaDasarTiket;
    }

    // Override: Menampilkan info fasilitas reguler
    public function tampilkanInfoFasilitas() {
        return "Fasilitas Reguler: Audio menggunakan {$this->tipeAudio}, posisi kursi di baris {$this->lokasiBaris}.";
    }
}
?>