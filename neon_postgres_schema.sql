BEGIN;

CREATE TABLE IF NOT EXISTS admins (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    google_id VARCHAR(255),
    role VARCHAR(20) NOT NULL DEFAULT 'viewer',
    approval_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    approved_by INTEGER,
    approved_at TIMESTAMP,
    is_super_admin BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT admins_role_check CHECK (role IN ('admin', 'viewer')),
    CONSTRAINT admins_approval_status_check CHECK (approval_status IN ('pending', 'approved', 'rejected')),
    CONSTRAINT admins_approved_by_fk FOREIGN KEY (approved_by) REFERENCES admins (id) ON DELETE SET NULL
);

CREATE UNIQUE INDEX IF NOT EXISTS admins_email_unique_idx
    ON admins (email)
    WHERE email IS NOT NULL;

CREATE UNIQUE INDEX IF NOT EXISTS admins_google_id_unique_idx
    ON admins (google_id)
    WHERE google_id IS NOT NULL;

CREATE INDEX IF NOT EXISTS admins_approval_status_idx
    ON admins (approval_status);

CREATE INDEX IF NOT EXISTS admins_is_super_admin_idx
    ON admins (is_super_admin);

CREATE TABLE IF NOT EXISTS jamaah (
    id SERIAL PRIMARY KEY,
    nama_jamaah VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS pengeluaran (
    id SERIAL PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    jumlah NUMERIC(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS program (
    id SERIAL PRIMARY KEY,
    nama_program VARCHAR(255) NOT NULL,
    tanggal_program DATE NOT NULL,
    harga_modal NUMERIC(10,2) NOT NULL DEFAULT 0,
    harga_jual NUMERIC(10,2) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS program_jamaah (
    id SERIAL PRIMARY KEY,
    jamaah_id INTEGER NOT NULL,
    program_id INTEGER NOT NULL,
    CONSTRAINT program_jamaah_jamaah_fk
        FOREIGN KEY (jamaah_id) REFERENCES jamaah (id),
    CONSTRAINT program_jamaah_program_fk
        FOREIGN KEY (program_id) REFERENCES program (id)
);

CREATE TABLE IF NOT EXISTS transaksi (
    id SERIAL PRIMARY KEY,
    id_jamaah INTEGER NOT NULL,
    id_program INTEGER NOT NULL,
    harga NUMERIC(10,2) NOT NULL,
    dp1 NUMERIC(10,2) NOT NULL,
    dp2 NUMERIC(10,2) NOT NULL,
    dp3 NUMERIC(10,2) NOT NULL,
    kekurangan NUMERIC(10,2),
    dp1_time_edit TIMESTAMP NULL,
    dp2_time_edit TIMESTAMP NULL,
    dp3_time_edit TIMESTAMP NULL,
    harga_modal NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    CONSTRAINT transaksi_jamaah_fk
        FOREIGN KEY (id_jamaah) REFERENCES jamaah (id),
    CONSTRAINT transaksi_program_fk
        FOREIGN KEY (id_program) REFERENCES program (id)
);

CREATE TABLE IF NOT EXISTS transaksi_pembayaran (
    id SERIAL PRIMARY KEY,
    transaksi_id INTEGER NOT NULL,
    nominal NUMERIC(10,2) NOT NULL,
    keterangan VARCHAR(255),
    dibayar_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT transaksi_pembayaran_transaksi_fk
        FOREIGN KEY (transaksi_id) REFERENCES transaksi (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS pengeluaran_log (
    id SERIAL PRIMARY KEY,
    pengeluaran_id INTEGER NOT NULL,
    action VARCHAR(20) NOT NULL DEFAULT 'update',
    edited_by VARCHAR(100) NOT NULL,
    edited_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    old_tanggal DATE,
    new_tanggal DATE,
    old_keterangan VARCHAR(255),
    new_keterangan VARCHAR(255),
    old_jumlah NUMERIC(10,2),
    new_jumlah NUMERIC(10,2),
    CONSTRAINT pengeluaran_log_pengeluaran_fk
        FOREIGN KEY (pengeluaran_id) REFERENCES pengeluaran (id) ON DELETE CASCADE
);

COMMIT;
