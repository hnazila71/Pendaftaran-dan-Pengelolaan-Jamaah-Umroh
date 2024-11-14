<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class KeuanganController extends Controller
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        // Ambil total harga dan total kekurangan dari tabel transaksi
        $totalHarga = $this->transaksiModel->selectSum('harga')->get()->getRow()->harga;
        $totalKekurangan = $this->transaksiModel->selectSum('kekurangan')->get()->getRow()->kekurangan;

        // Hitung pemasukan sebagai total harga dikurangi total kekurangan
        $pemasukan = $totalHarga - $totalKekurangan;

        // Kirim data ke view
        return view('keuangan', [
            'totalHarga' => $totalHarga,
            'totalKekurangan' => $totalKekurangan,
            'pemasukan' => $pemasukan,
        ]);
    }
}
