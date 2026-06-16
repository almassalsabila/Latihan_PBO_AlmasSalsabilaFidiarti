<?php
abstract class Tiket {
    // Atribut terenkapsulasi (protected)
    protected $id_tiket;
    protected $nama_film;
    protected $jadwal_tayang;
    protected $jumlah_kursi;
    protected $hargaDasarTiket; // Memetakan kolom 'harga_dasar_tiket' dari DB

    // Constructor untuk memetakan data dari database ke dalam properti objek
    public function __construct($id_tiket, $nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket) {
        $this->id_tiket = $id_tiket;
        $this->nama_film = $nama_film;
        $this->jadwal_tayang = $jadwal_tayang;
        $this->jumlah_kursi = $jumlah_kursi;
        $this->hargaDasarTiket = $hargaDasarTiket;
    }

    // =========================================================================
    // ABSTRACT METHODS (Wajib di-override/diimplementasikan di kelas anak)
    // =========================================================================
    
    // Metode abstrak untuk menghitung total harga (kalkulasi tiket * jumlah kursi + biaya tambahan)
    abstract public function hitungTotalHarga();

    // Metode abstrak untuk menampilkan fasilitas spesifik berdasarkan jenis studio
    abstract public function tampilkanInfoFasilitas();
}
?>

// Fungsi Getter untuk mengakses properti protected di halaman View
    public function getIdTiket() { return $this->id_tiket; }
    public function getNamaFilm() { return $this->nama_film; }
    public function getJadwalTayang() { return $this->jadwal_tayang; }
    public function getJumlahKursi() { return $this->jumlah_kursi; }
    public function getHargaDasarTiket() { return $this->hargaDasarTiket; }