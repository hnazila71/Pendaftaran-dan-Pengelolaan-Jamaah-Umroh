<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\Controller;
use Throwable;

class AuthController extends Controller
{
    private const ROLE_ADMIN = 'admin';
    private const ROLE_VIEWER = 'viewer';
    private const STATUS_PENDING = 'pending';
    private const STATUS_APPROVED = 'approved';
    private const STATUS_REJECTED = 'rejected';

    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $usernameInput = trim((string) (
            $this->request->getPost('username')
            ?? $this->request->getPost('email')
            ?? $this->request->getPost('nama')
        ));
        $password = (string) $this->request->getPost('password');

        if ($usernameInput === '' || $password === '') {
            return redirect()->back()->withInput()->with('msg', 'Username dan password wajib diisi.');
        }

        $usernameLower = strtolower($usernameInput);

        try {
            $adminModel = new AdminModel();
            $admin = $adminModel
                ->groupStart()
                ->where('nama', $usernameInput)
                ->orWhere('email', $usernameLower)
                ->groupEnd()
                ->first();
            $adminCount = $adminModel->countAll();
        } catch (Throwable $e) {
            log_message('error', 'Login database error: {message}', ['message' => $e->getMessage()]);

            $message = 'Koneksi database bermasalah. Pastikan env `DATABASE_URL` di Render valid dan schema Neon sudah di-import.';
            $lowerError = strtolower($e->getMessage());
            if (str_contains($lowerError, 'relation') && str_contains($lowerError, 'admins')) {
                $message = 'Tabel `admins` belum ada di Neon. Jalankan file neon_postgres_schema.sql lalu coba lagi.';
            }

            return redirect()->back()->withInput()->with(
                'msg',
                $message
            );
        }

        if (! $admin) {
            // Bootstrap akun pertama otomatis jika tabel admins masih kosong.
            if ($adminCount === 0) {
                $adminId = $adminModel->insert([
                    'nama' => $usernameInput,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'email' => null,
                    'google_id' => null,
                    'role' => self::ROLE_ADMIN,
                    'approval_status' => self::STATUS_APPROVED,
                    'approved_by' => null,
                    'approved_at' => date('Y-m-d H:i:s'),
                    'is_super_admin' => true,
                ]);

                if (! $adminId) {
                    return redirect()->back()->withInput()->with('msg', 'Gagal membuat akun admin awal.');
                }

                session()->set([
                    'admin_id' => $adminId,
                    'admin_nama' => $usernameInput,
                    'admin_role' => self::ROLE_ADMIN,
                    'is_super_admin' => true,
                    'isLoggedIn' => true,
                ]);

                return redirect()->to('/dashboard')->with('success', 'Akun admin awal berhasil dibuat.');
            }

            return redirect()->back()->withInput()->with('msg', 'Username/email atau password salah.');
        }

        $storedPassword = (string) ($admin['password'] ?? '');
        $passwordValid = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);

        if (! $passwordValid) {
            return redirect()->back()->withInput()->with('msg', 'Username/email atau password salah.');
        }

        $approvalStatus = strtolower((string) ($admin['approval_status'] ?? self::STATUS_APPROVED));
        if ($approvalStatus === self::STATUS_PENDING) {
            return redirect()->back()->withInput()->with('msg', 'Akun kamu masih menunggu persetujuan admin.');
        }

        if ($approvalStatus === self::STATUS_REJECTED) {
            return redirect()->back()->withInput()->with('msg', 'Akun kamu ditolak. Hubungi admin utama untuk akses.');
        }

        $role = strtolower((string) ($admin['role'] ?? self::ROLE_ADMIN));
        if (! in_array($role, [self::ROLE_ADMIN, self::ROLE_VIEWER], true)) {
            $role = self::ROLE_VIEWER;
        }

        session()->set([
            'admin_id' => $admin['id'],
            'admin_nama' => $admin['nama'],
            'admin_role' => $role,
            'is_super_admin' => $this->toBool($admin['is_super_admin'] ?? false),
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function loginGoogle()
    {
        $clientId = trim((string) $this->readEnv([
            'GOOGLE_CLIENT_ID',
            'google.clientId',
            'google_clientId',
        ], ''));
        $clientSecret = trim((string) $this->readEnv([
            'GOOGLE_CLIENT_SECRET',
            'google.clientSecret',
            'google_clientSecret',
        ], ''));
        $redirectUri = $this->resolveGoogleRedirectUri();

        $missing = [];
        if ($clientId === '') {
            $missing[] = 'GOOGLE_CLIENT_ID';
        }
        if ($clientSecret === '') {
            $missing[] = 'GOOGLE_CLIENT_SECRET';
        }
        if ($redirectUri === '') {
            $missing[] = 'GOOGLE_REDIRECT_URI / APP_BASE_URL';
        }

        if ($missing !== []) {
            return redirect()->to('/login')->with(
                'msg',
                'Google Login belum lengkap. Isi env: ' . implode(', ', $missing)
            );
        }

        try {
            $state = bin2hex(random_bytes(16));
        } catch (Throwable $e) {
            $state = bin2hex((string) time());
        }

        session()->set('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'access_type' => 'offline',
            'prompt' => 'select_account',
        ]);

        return redirect()->to('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function loginGoogleCallback()
    {
        $error = (string) $this->request->getGet('error');

        if ($error !== '') {
            return redirect()->to('/login')->with('msg', 'Login Google dibatalkan: ' . $error);
        }

        $state = (string) $this->request->getGet('state');
        $expectedState = (string) session()->get('google_oauth_state');
        session()->remove('google_oauth_state');

        if ($expectedState === '' || $state !== $expectedState) {
            return redirect()->to('/login')->with('msg', 'State OAuth tidak valid. Silakan coba lagi.');
        }

        $code = (string) $this->request->getGet('code');

        if ($code === '') {
            return redirect()->to('/login')->with('msg', 'Kode otorisasi Google tidak ditemukan.');
        }

        $tokenPayload = $this->exchangeGoogleCode($code);

        if (! is_array($tokenPayload) || empty($tokenPayload['access_token'])) {
            return redirect()->to('/login')->with('msg', 'Gagal menukar kode Google menjadi access token.');
        }

        $profile = $this->fetchGoogleProfile((string) $tokenPayload['access_token']);

        if (! is_array($profile) || empty($profile['sub'])) {
            return redirect()->to('/login')->with('msg', 'Gagal mengambil profil pengguna dari Google.');
        }

        $googleId = (string) $profile['sub'];
        $emailRaw = (string) ($profile['email'] ?? '');
        $email = $emailRaw !== '' ? strtolower(trim($emailRaw)) : null;
        $displayName = trim((string) ($profile['name'] ?? 'Google User'));

        $adminModel = new AdminModel();

        $admin = $adminModel->where('google_id', $googleId)->first();

        if (! $admin && $email !== null) {
            $admin = $adminModel->where('email', $email)->first();
        }

        if (! $admin) {
                $adminModel->insert([
                    'nama' => $displayName,
                    'password' => password_hash($this->randomHex(16), PASSWORD_DEFAULT),
                    'email' => $email,
                    'google_id' => $googleId,
                    'role' => self::ROLE_VIEWER,
                    'approval_status' => self::STATUS_PENDING,
                    'approved_by' => null,
                    'approved_at' => null,
                    'is_super_admin' => false,
                ]);

            $adminId = (int) $adminModel->getInsertID();
            $admin = $adminModel->find($adminId);
        } else {
            $updateData = [];

            if (($admin['google_id'] ?? null) === null || (string) ($admin['google_id'] ?? '') === '') {
                $updateData['google_id'] = $googleId;
            }

            if ($email !== null && (($admin['email'] ?? null) === null || (string) ($admin['email'] ?? '') === '')) {
                $updateData['email'] = $email;
            }

            if ($displayName !== '' && (string) ($admin['nama'] ?? '') === '') {
                $updateData['nama'] = $displayName;
            }

            if ((string) ($admin['role'] ?? '') === '') {
                $updateData['role'] = self::ROLE_VIEWER;
            }

            if ((string) ($admin['approval_status'] ?? '') === '') {
                $updateData['approval_status'] = self::STATUS_PENDING;
            }

            if (! array_key_exists('is_super_admin', $admin)) {
                $updateData['is_super_admin'] = false;
            }

            if (! empty($updateData)) {
                $adminModel->update($admin['id'], $updateData);
                $admin = array_merge($admin, $updateData);
            }
        }

        if (! $admin) {
            return redirect()->to('/login')->with('msg', 'Gagal membuat/mengambil akun admin dari Google.');
        }

        $approvalStatus = strtolower((string) ($admin['approval_status'] ?? self::STATUS_PENDING));
        if ($approvalStatus === self::STATUS_PENDING) {
            return redirect()->to('/login')->with(
                'msg',
                'Akun Google kamu sudah terdaftar dan sedang menunggu persetujuan admin.'
            );
        }

        if ($approvalStatus === self::STATUS_REJECTED) {
            return redirect()->to('/login')->with(
                'msg',
                'Akun Google kamu belum disetujui. Hubungi admin untuk aktivasi akun.'
            );
        }

        $role = strtolower((string) ($admin['role'] ?? self::ROLE_VIEWER));
        if (! in_array($role, [self::ROLE_ADMIN, self::ROLE_VIEWER], true)) {
            $role = self::ROLE_VIEWER;
        }

        session()->set([
            'admin_id' => $admin['id'],
            'admin_nama' => $admin['nama'],
            'admin_role' => $role,
            'is_super_admin' => $this->toBool($admin['is_super_admin'] ?? false),
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Login Google berhasil.');
    }

    public function register()
    {
        return view('register');
    }

    public function registerProcess()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]|is_unique[admins.nama]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $adminModel = new AdminModel();
        $adminCount = $adminModel->countAll();

        $isFirstAccount = $adminCount === 0;
        $role = $isFirstAccount ? self::ROLE_ADMIN : self::ROLE_VIEWER;
        $status = $isFirstAccount ? self::STATUS_APPROVED : self::STATUS_PENDING;
        $isSuperAdmin = $isFirstAccount;

        $adminModel->insert([
            'nama' => trim((string) $this->request->getPost('nama')),
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'email' => null,
            'google_id' => null,
            'role' => $role,
            'approval_status' => $status,
            'approved_by' => null,
            'approved_at' => $isFirstAccount ? date('Y-m-d H:i:s') : null,
            'is_super_admin' => $isSuperAdmin,
        ]);

        if ($isFirstAccount) {
            return redirect()->to('/login')->with('success', 'Registrasi admin awal berhasil. Silakan login.');
        }

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Menunggu persetujuan admin.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }

    private function exchangeGoogleCode(string $code): ?array
    {
        $clientId = trim((string) $this->readEnv([
            'GOOGLE_CLIENT_ID',
            'google.clientId',
            'google_clientId',
        ], ''));
        $clientSecret = trim((string) $this->readEnv([
            'GOOGLE_CLIENT_SECRET',
            'google.clientSecret',
            'google_clientSecret',
        ], ''));
        $redirectUri = $this->resolveGoogleRedirectUri();

        if ($clientId === '' || $clientSecret === '' || $redirectUri === '') {
            log_message('error', 'Google OAuth env incomplete for token exchange.');
            return null;
        }

        $payload = http_build_query([
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]);

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 20,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            curl_close($ch);
            return null;
        }

        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode < 200 || $statusCode >= 300) {
            return null;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function fetchGoogleProfile(string $accessToken): ?array
    {
        $ch = curl_init('https://openidconnect.googleapis.com/v1/userinfo');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT => 20,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            curl_close($ch);
            return null;
        }

        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode < 200 || $statusCode >= 300) {
            return null;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function randomHex(int $bytes): string
    {
        try {
            return bin2hex(random_bytes($bytes));
        } catch (Throwable $e) {
            return bin2hex((string) (time() . mt_rand()));
        }
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        $text = strtolower(trim((string) $value));
        return in_array($text, ['1', 't', 'true', 'yes', 'y'], true);
    }

    private function resolveGoogleRedirectUri(): string
    {
        $redirectUri = trim((string) $this->readEnv([
            'GOOGLE_REDIRECT_URI',
            'google.redirectUri',
            'google_redirectUri',
        ], ''));

        if ($redirectUri !== '') {
            return $redirectUri;
        }

        $baseURL = trim((string) $this->readEnv([
            'APP_BASE_URL',
            'app.baseURL',
            'app_baseURL',
            'RENDER_EXTERNAL_URL',
        ], ''));

        if ($baseURL === '') {
            $appConfig = config('App');
            if (is_object($appConfig) && isset($appConfig->baseURL)) {
                $baseURL = trim((string) $appConfig->baseURL);
            }
        }

        if ($baseURL === '') {
            return '';
        }

        return rtrim($baseURL, '/') . '/login/google/callback';
    }

    /**
     * @param array<int, string> $keys
     * @param mixed $default
     *
     * @return mixed
     */
    private function readEnv(array $keys, $default = null)
    {
        foreach ($keys as $key) {
            $value = env($key, null);
            if ($value !== null && $value !== false && $value !== '') {
                return $value;
            }
        }

        return $default;
    }
}
