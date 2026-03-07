BEGIN;

ALTER TABLE admins
    ADD COLUMN IF NOT EXISTS email VARCHAR(255),
    ADD COLUMN IF NOT EXISTS google_id VARCHAR(255),
    ADD COLUMN IF NOT EXISTS role VARCHAR(20) NOT NULL DEFAULT 'viewer',
    ADD COLUMN IF NOT EXISTS approval_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS approved_by INTEGER,
    ADD COLUMN IF NOT EXISTS approved_at TIMESTAMP,
    ADD COLUMN IF NOT EXISTS is_super_admin BOOLEAN NOT NULL DEFAULT FALSE;

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

UPDATE admins
SET
    role = 'admin',
    approval_status = 'approved',
    approved_at = COALESCE(approved_at, CURRENT_TIMESTAMP)
WHERE COALESCE(google_id, '') = ''
  AND COALESCE(role, '') IN ('', 'viewer')
  AND COALESCE(approval_status, '') IN ('', 'pending');

UPDATE admins
SET is_super_admin = TRUE
WHERE id = (
    SELECT id
    FROM admins
    WHERE role = 'admin'
      AND approval_status = 'approved'
    ORDER BY id ASC
    LIMIT 1
)
AND NOT EXISTS (
    SELECT 1 FROM admins WHERE is_super_admin = TRUE
);

ALTER TABLE program
    ADD COLUMN IF NOT EXISTS harga_modal NUMERIC(10,2) NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS harga_jual NUMERIC(10,2) NOT NULL DEFAULT 0;

UPDATE program p
SET
    harga_jual = src.harga,
    harga_modal = src.harga_modal
FROM (
    SELECT id_program, MAX(harga) AS harga, MAX(harga_modal) AS harga_modal
    FROM transaksi
    GROUP BY id_program
) AS src
WHERE p.id = src.id_program
  AND p.harga_jual = 0
  AND p.harga_modal = 0;

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
