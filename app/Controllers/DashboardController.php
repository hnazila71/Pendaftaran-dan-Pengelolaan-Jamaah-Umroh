<?php

namespace App\Controllers;

use App\Models\JamaahModel;
use App\Models\PembayaranModel;
use App\Models\ProgramModel;
use App\Models\TransaksiModel;
use Throwable;

class DashboardController extends AuthenticatedController
{
    protected $jamaahModel;
    protected $programModel;
    protected $transaksiModel;
    protected $pembayaranModel;

    public function __construct()
    {
        helper('NumberHelper');
    }

    private function initModels(): void
    {
        if (! $this->jamaahModel) {
            $this->jamaahModel = new JamaahModel();
        }

        if (! $this->programModel) {
            $this->programModel = new ProgramModel();
        }

        if (! $this->transaksiModel) {
            $this->transaksiModel = new TransaksiModel();
        }

        if (! $this->pembayaranModel) {
            $this->pembayaranModel = new PembayaranModel();
        }
    }

    public function index()
    {
        $redirect = $this->requireLogin();
        if ($redirect !== null) {
            return $redirect;
        }

        $data['programs'] = [];
        $data['db_issue'] = null;

        try {
            $this->initModels();
            $data['programs'] = $this->programModel->findAll();
        } catch (Throwable $e) {
            log_message('error', 'Dashboard index database error: {message}', ['message' => $e->getMessage()]);
            $data['db_issue'] = 'Database belum siap atau konfigurasi Neon belum benar. Cek DB di Render lalu refresh.';
        }

        $data['is_admin'] = $this->isAdmin();
        $data['is_super_admin'] = $this->isSuperAdmin();
        $data['admin_role'] = (string) session()->get('admin_role');
        $data['admin_nama'] = (string) session()->get('admin_nama');

        return view('dashboard', $data);
    }

    public function viewProgramTransactions($id_program)
    {
        $redirect = $this->requireLogin();
        if ($redirect !== null) {
            return $redirect;
        }

        try {
            $this->initModels();
            $program = $this->programModel->find($id_program);
        } catch (Throwable $e) {
            log_message('error', 'View program transactions database error: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/dashboard')->with('error', 'Database bermasalah. Cek koneksi Neon dan schema.');
        }

        if (! $program) {
            return redirect()->to('/dashboard')->with('error', 'Program tidak ditemukan.');
        }

        $transaksi = $this->transaksiModel
            ->select('transaksi.*, jamaah.nama_jamaah, COALESCE(SUM(transaksi_pembayaran.nominal), 0) as total_bayar_dinamis')
            ->where('transaksi.id_program', $id_program)
            ->join('jamaah', 'transaksi.id_jamaah = jamaah.id')
            ->join('transaksi_pembayaran', 'transaksi_pembayaran.transaksi_id = transaksi.id', 'left')
            ->groupBy('transaksi.id, jamaah.nama_jamaah')
            ->orderBy('transaksi.id', 'ASC')
            ->findAll();

        $riwayatByTransaksi = [];
        $transaksiIds = array_column($transaksi, 'id');

        if (! empty($transaksiIds)) {
            $pembayaranRows = $this->pembayaranModel
                ->whereIn('transaksi_id', $transaksiIds)
                ->orderBy('dibayar_pada', 'ASC')
                ->findAll();

            foreach ($pembayaranRows as $row) {
                $transaksiId = (int) $row['transaksi_id'];
                $riwayatByTransaksi[$transaksiId][] = [
                    'nominal' => (float) ($row['nominal'] ?? 0),
                    'keterangan' => $row['keterangan'] ?? null,
                    'dibayar_pada' => $row['dibayar_pada'] ?? null,
                ];
            }
        }

        foreach ($transaksi as &$item) {
            $riwayatDinamis = $riwayatByTransaksi[(int) $item['id']] ?? [];
            $riwayatLegacy = [];

            foreach (['dp1', 'dp2', 'dp3'] as $dpField) {
                $nominal = (float) ($item[$dpField] ?? 0);

                if ($nominal <= 0) {
                    continue;
                }

                $timeField = $dpField . '_time_edit';
                $riwayatLegacy[] = [
                    'nominal' => $nominal,
                    'keterangan' => strtoupper($dpField) . ' (legacy)',
                    'dibayar_pada' => $item[$timeField] ?? null,
                ];
            }

            $riwayatMerged = array_merge($riwayatDinamis, $riwayatLegacy);
            usort($riwayatMerged, static function (array $a, array $b): int {
                $ta = isset($a['dibayar_pada']) && $a['dibayar_pada'] !== null ? strtotime((string) $a['dibayar_pada']) : 0;
                $tb = isset($b['dibayar_pada']) && $b['dibayar_pada'] !== null ? strtotime((string) $b['dibayar_pada']) : 0;
                return $ta <=> $tb;
            });

            $legacyBayar = (float) ($item['dp1'] ?? 0) + (float) ($item['dp2'] ?? 0) + (float) ($item['dp3'] ?? 0);
            $dinamisBayar = (float) ($item['total_bayar_dinamis'] ?? 0);
            $totalBayar = $legacyBayar + $dinamisBayar;

            $item['total_bayar'] = $totalBayar;
            $item['sisa_tagihan'] = max(0, (float) $item['harga'] - $totalBayar);
            $item['riwayat_pembayaran'] = $riwayatMerged;
        }
        unset($item);

        $data = [
            'program' => $program,
            'transaksi' => $transaksi,
            'is_admin' => $this->isAdmin(),
        ];

        return view('program_transactions', $data);
    }

    public function editDP1($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        return $this->editDP($id, 'dp1');
    }

    public function editDP2($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        return $this->editDP($id, 'dp2');
    }

    public function editDP3($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        return $this->editDP($id, 'dp3');
    }

    private function editDP($id, $dpField)
    {
        try {
            $this->initModels();
            $transaksi = $this->transaksiModel
                ->select('transaksi.*, jamaah.nama_jamaah')
                ->join('jamaah', 'transaksi.id_jamaah = jamaah.id')
                ->where('transaksi.id', $id)
                ->first();
        } catch (Throwable $e) {
            log_message('error', 'Edit DP database error: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/dashboard')->with('error', 'Database bermasalah. Cek koneksi Neon dan schema.');
        }

        if (! $transaksi) {
            return redirect()->to('/dashboard')->with('error', 'Transaksi tidak ditemukan.');
        }

        $data = [
            'transaksi' => $transaksi,
            'dpField' => $dpField,
            'fieldLabel' => strtoupper($dpField),
        ];

        return view('edit_dp', $data);
    }

    public function updateDP($id)
    {
        $redirect = $this->requireAdmin();
        if ($redirect !== null) {
            return $redirect;
        }

        $dpField = $this->request->getPost('dpField');
        $dpValue = str_replace(',', '', (string) $this->request->getPost('dpValue'));

        try {
            $this->initModels();
            $transaksi = $this->transaksiModel->find($id);
        } catch (Throwable $e) {
            log_message('error', 'Update DP database error: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/dashboard')->with('error', 'Database bermasalah. Cek koneksi Neon dan schema.');
        }

        if (! $transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        $timeEditField = $dpField . '_time_edit';

        $updateData = [
            $dpField => $dpValue,
            $timeEditField => date('Y-m-d H:i:s'),
        ];

        $this->transaksiModel->update($id, $updateData);

        return redirect()->to('/dashboard/program/' . $transaksi['id_program'])
            ->with('success', ucfirst($dpField) . ' berhasil diperbarui.');
    }
}
