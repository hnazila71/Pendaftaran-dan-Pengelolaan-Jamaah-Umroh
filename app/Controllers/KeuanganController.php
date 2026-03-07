<?php

namespace App\Controllers;

use App\Models\PengeluaranLogModel;
use App\Models\PengeluaranModel;
use App\Models\TransaksiModel;

class KeuanganController extends AuthenticatedController
{
    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect !== null) {
            return $redirect;
        }

        $pengeluaranModel = new PengeluaranModel();
        $pengeluaranLogModel = new PengeluaranLogModel();
        $transaksiModel = new TransaksiModel();

        $data['pengeluaran'] = $pengeluaranModel->orderBy('tanggal', 'DESC')->findAll();

        $total = 0;
        foreach ($data['pengeluaran'] as $item) {
            $total += (float) $item['jumlah'];
        }

        $transaksiSummary = $transaksiModel
            ->selectSum('harga', 'total_harga_jual')
            ->selectSum('harga_modal', 'total_harga_modal')
            ->first();

        $totalHargaJual = (float) ($transaksiSummary['total_harga_jual'] ?? 0);
        $totalHargaModal = (float) ($transaksiSummary['total_harga_modal'] ?? 0);
        $selisihJualModal = $totalHargaJual - $totalHargaModal;
        $selisihSetelahPengeluaran = $selisihJualModal - (float) $total;

        $logs = $pengeluaranLogModel
            ->select('pengeluaran_log.*, pengeluaran.keterangan as pengeluaran_keterangan')
            ->join('pengeluaran', 'pengeluaran.id = pengeluaran_log.pengeluaran_id', 'left')
            ->orderBy('edited_at', 'DESC')
            ->limit(60)
            ->findAll();

        $data['total'] = $total;
        $data['total_harga_jual'] = $totalHargaJual;
        $data['total_harga_modal'] = $totalHargaModal;
        $data['selisih_jual_modal'] = $selisihJualModal;
        $data['selisih_setelah_pengeluaran'] = $selisihSetelahPengeluaran;
        $data['pengeluaran_logs'] = $logs;
        $data['is_admin'] = $this->isAdmin();

        return view('keuangan/index', $data);
    }

    public function save()
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $pengeluaranModel = new PengeluaranModel();
        $pengeluaranLogModel = new PengeluaranLogModel();

        $keterangan = trim((string) $this->request->getPost('keterangan'));
        $jumlah = $this->parseRupiah((string) $this->request->getPost('jumlah'));
        $tanggal = date('Y-m-d');

        if ($keterangan === '' || $jumlah <= 0) {
            return redirect()->back()->withInput()->with('errors', [
                'Keterangan wajib diisi dan jumlah harus lebih dari 0.',
            ]);
        }

        $pengeluaranModel->insert([
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ]);

        $pengeluaranId = (int) $pengeluaranModel->getInsertID();
        $editor = $this->currentEditor();

        $pengeluaranLogModel->insert([
            'pengeluaran_id' => $pengeluaranId,
            'action' => 'create',
            'edited_by' => $editor,
            'edited_at' => date('Y-m-d H:i:s'),
            'new_tanggal' => $tanggal,
            'new_keterangan' => $keterangan,
            'new_jumlah' => $jumlah,
        ]);

        return redirect()->to('/keuangan')->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function edit(int $id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $pengeluaranModel = new PengeluaranModel();
        $pengeluaran = $pengeluaranModel->find($id);

        if (! $pengeluaran) {
            return redirect()->to('/keuangan')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        return view('keuangan/edit', ['pengeluaran' => $pengeluaran]);
    }

    public function update(int $id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $pengeluaranModel = new PengeluaranModel();
        $pengeluaranLogModel = new PengeluaranLogModel();

        $existing = $pengeluaranModel->find($id);

        if (! $existing) {
            return redirect()->to('/keuangan')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        $tanggal = (string) $this->request->getPost('tanggal');
        $keterangan = trim((string) $this->request->getPost('keterangan'));
        $jumlah = $this->parseRupiah((string) $this->request->getPost('jumlah'));

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            return redirect()->back()->withInput()->with('errors', ['Format tanggal tidak valid.']);
        }

        if ($keterangan === '' || $jumlah <= 0) {
            return redirect()->back()->withInput()->with('errors', [
                'Keterangan wajib diisi dan jumlah harus lebih dari 0.',
            ]);
        }

        $pengeluaranModel->update($id, [
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ]);

        $pengeluaranLogModel->insert([
            'pengeluaran_id' => $id,
            'action' => 'update',
            'edited_by' => $this->currentEditor(),
            'edited_at' => date('Y-m-d H:i:s'),
            'old_tanggal' => $existing['tanggal'],
            'new_tanggal' => $tanggal,
            'old_keterangan' => $existing['keterangan'],
            'new_keterangan' => $keterangan,
            'old_jumlah' => (float) $existing['jumlah'],
            'new_jumlah' => $jumlah,
        ]);

        return redirect()->to('/keuangan')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    private function parseRupiah(string $raw): float
    {
        $clean = preg_replace('/[^\d]/', '', $raw);

        if ($clean === null || $clean === '') {
            return 0;
        }

        return (float) $clean;
    }

    private function currentEditor(): string
    {
        $editor = session()->get('admin_nama');

        if (is_string($editor) && trim($editor) !== '') {
            return trim($editor);
        }

        return 'unknown';
    }
}
