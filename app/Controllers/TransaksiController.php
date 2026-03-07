<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use App\Models\PembayaranModel;
use App\Models\ProgramModel;
use App\Models\TransaksiModel;

class TransaksiController extends AuthenticatedController
{
    public function addTransaksi()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $jamaahModel = new JamaahModel();
        $programModel = new ProgramModel();

        $data['jamaah'] = $jamaahModel->findAll();
        $data['programs'] = $programModel->findAll();

        return view('add_transaksi', $data);
    }

    public function saveTransaksi()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        if (! $this->validate([
            'id_jamaah' => 'required',
            'id_program' => 'required',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $programModel = new ProgramModel();
        $program = $programModel->find((int) $this->request->getPost('id_program'));

        if (! $program) {
            return redirect()->back()->withInput()->with('errors', ['Program tidak ditemukan.']);
        }

        $hargaJual = (float) ($program['harga_jual'] ?? 0);
        $hargaModal = (float) ($program['harga_modal'] ?? 0);

        if ($hargaJual <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Harga jual program belum valid.']);
        }

        $pembayaranAwal = $this->parseMoney((string) $this->request->getPost('pembayaran_awal'));

        if ($pembayaranAwal < 0) {
            return redirect()->back()->withInput()->with('errors', ['Pembayaran awal tidak boleh negatif.']);
        }

        if ($pembayaranAwal > $hargaJual) {
            return redirect()->back()->withInput()->with('errors', ['Pembayaran awal tidak boleh melebihi harga jual program.']);
        }

        $transaksiModel = new TransaksiModel();
        $transaksiModel->insert([
            'id_jamaah' => $this->request->getPost('id_jamaah'),
            'id_program' => $this->request->getPost('id_program'),
            'harga' => $hargaJual,
            'harga_modal' => $hargaModal,
            'dp1' => 0,
            'dp2' => 0,
            'dp3' => 0,
            'kekurangan' => max(0, $hargaJual - $pembayaranAwal),
        ]);

        $transaksiId = $transaksiModel->getInsertID();

        if ($pembayaranAwal > 0) {
            $pembayaranModel = new PembayaranModel();
            $pembayaranModel->insert([
                'transaksi_id' => $transaksiId,
                'nominal' => $pembayaranAwal,
                'keterangan' => 'Pembayaran awal',
                'dibayar_pada' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/dashboard/program/' . $this->request->getPost('id_program'))
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function addPembayaran(int $transaksiId)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $transaksiModel = new TransaksiModel();
        $pembayaranModel = new PembayaranModel();

        $transaksi = $transaksiModel->find($transaksiId);

        if (! $transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $nominal = $this->parseMoney((string) $this->request->getPost('nominal'));

        if ($nominal <= 0) {
            return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
                ->with('error', 'Nominal pembayaran harus lebih dari 0.');
        }

        $totalSudahBayar = $this->getTotalBayar($transaksiId, $transaksi);
        $sisa = max(0, (float) $transaksi['harga'] - $totalSudahBayar);

        if ($sisa <= 0) {
            return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
                ->with('error', 'Transaksi ini sudah lunas.');
        }

        if ($nominal > $sisa) {
            return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
                ->with('error', 'Nominal melebihi sisa pembayaran. Sisa saat ini: Rp ' . number_format($sisa, 0, ',', '.'));
        }

        $pembayaranModel->insert([
            'transaksi_id' => $transaksiId,
            'nominal' => $nominal,
            'keterangan' => trim((string) $this->request->getPost('keterangan')) ?: null,
            'dibayar_pada' => date('Y-m-d H:i:s'),
        ]);

        $sisaBaru = max(0, $sisa - $nominal);
        $transaksiModel->update($transaksiId, ['kekurangan' => $sisaBaru]);

        return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    private function getTotalBayar(int $transaksiId, array $transaksi): float
    {
        $pembayaranModel = new PembayaranModel();
        $sumRow = $pembayaranModel
            ->selectSum('nominal')
            ->where('transaksi_id', $transaksiId)
            ->first();

        $dinamis = (float) ($sumRow['nominal'] ?? 0);
        $legacy = (float) ($transaksi['dp1'] ?? 0) + (float) ($transaksi['dp2'] ?? 0) + (float) ($transaksi['dp3'] ?? 0);

        return $dinamis + $legacy;
    }

    private function parseMoney(string $value): float
    {
        $normalized = str_replace(',', '', trim($value));

        if ($normalized === '') {
            return 0;
        }

        return (float) $normalized;
    }
}
