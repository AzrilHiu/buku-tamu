CREATE DATABASE IF NOT EXISTS `app_bukutamu_baru`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `app_bukutamu_baru`;

CREATE TABLE IF NOT EXISTS `buku_tamu` (
    `id_tamu` CHAR(5) NOT NULL,
    `tanggal` DATE NOT NULL,
    `nama_tamu` VARCHAR(100) NOT NULL,
    `alamat` TEXT NOT NULL,
    `no_hp` VARCHAR(20) NOT NULL,
    `bertemu` VARCHAR(100) NOT NULL,
    `kepentingan` TEXT NOT NULL,
    `gambar` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id_tamu`),
    KEY `idx_buku_tamu_tanggal` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
    `id_user` CHAR(5) NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'operator') NOT NULL DEFAULT 'operator',
    PRIMARY KEY (`id_user`),
    UNIQUE KEY `uq_users_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Generate a bcrypt hash with generate_password.php, then create the first admin:
-- INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`)
-- VALUES ('U0001', 'Administrator', 'admin', '<bcrypt-hash>', 'admin');
