<?php
require_once 'Tiket.php';

class TiketIMAX extends Tiket {
    // Properti spesifik terenkapsulasi
    private $kacamata3dId;
    private $efekGerakFitur;

    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $kacamata3dId, $efekGerakFitur) {
        parent::__construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket);
        $this->kacamata3dId = $kacamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // [TAHAP 5] Override: Ditambah biaya flat teknologi IMAX sebesar Rp 35.000
    public function hitungTotalHarga() {
        return ($this->jumlah_kursi * $this->hargaDasarTiket) + 35000;
    }

    // Override: Menampilkan info fasilitas IMAX
    public function tampilkanInfoFasilitas() {
        $statusGerak = $this->efekGerakFitur ? "Aktif" : "Tidak Aktif";
        return "Fasilitas IMAX: Kacamata 3D ID ({$this->kacamata3dId}), Fitur Efek Gerak Kursi {$statusGerak}.";
    }
}
?>