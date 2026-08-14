-- Valentina Studio --
-- MySQL dump --
-- ---------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
-- ---------------------------------------------------------


-- CREATE TABLE "log_pinjam" -----------------------------------
CREATE TABLE `log_pinjam`( 
	`id_log` Int( 0 ) AUTO_INCREMENT NOT NULL,
	`id_buku` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`id_anggota` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`tgl_pinjam` Date NOT NULL,
	PRIMARY KEY ( `id_log` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 6;
-- -------------------------------------------------------------


-- CREATE TABLE "tb_anggota" -----------------------------------
CREATE TABLE `tb_anggota`( 
	`id_anggota` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`nama` VarChar( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`jekel` VarChar( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
	`kelas` VarChar( 150 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`pangkat_gol` VarChar( 120 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`nip` VarChar( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`no_hp` VarChar( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	PRIMARY KEY ( `id_anggota` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tb_buku" --------------------------------------
CREATE TABLE `tb_buku`( 
	`id_buku` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`kode_buku` VarChar( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`seri_buku` VarChar( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`judul_buku` VarChar( 300 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`pengarang` VarChar( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`penerbit` VarChar( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`rak` VarChar( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`th_terbit` Year NULL DEFAULT NULL,
	`jumlah` Int( 0 ) NOT NULL DEFAULT 0,
	PRIMARY KEY ( `id_buku` ),
	CONSTRAINT `uk_kode_buku` UNIQUE( `kode_buku` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tb_ebook" -------------------------------------
CREATE TABLE `tb_ebook`( 
	`id_ebook` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`id_buku` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
	`kode_ebook` VarChar( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`judul_ebook` VarChar( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`penulis` VarChar( 150 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`penerbit` VarChar( 150 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`tahun_terbit` Year NULL DEFAULT NULL,
	`kategori` VarChar( 120 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`deskripsi` Text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
	`sumber_file` Enum( 'upload', 'url' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'upload',
	`nama_file_asli` VarChar( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`nama_file_simpan` VarChar( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`file_path` VarChar( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`file_url` VarChar( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`ukuran_file` BigInt( 0 ) NOT NULL DEFAULT 0,
	`ukuran_label` VarChar( 30 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`ekstensi_file` VarChar( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'pdf',
	`status_aktif` Enum( '1', '0' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
	`created_at` DateTime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` DateTime NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY ( `id_ebook` ),
	CONSTRAINT `kode_ebook` UNIQUE( `kode_ebook` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- CREATE TABLE "tb_pengguna" ----------------------------------
CREATE TABLE `tb_pengguna`( 
	`id_pengguna` Int( 0 ) AUTO_INCREMENT NOT NULL,
	`nama_pengguna` VarChar( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`username` VarChar( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`password` VarChar( 35 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`level` Enum( 'Administrator', 'Petugas', '', '' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	PRIMARY KEY ( `id_pengguna` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB
AUTO_INCREMENT = 6;
-- -------------------------------------------------------------


-- CREATE TABLE "tb_sirkulasi" ---------------------------------
CREATE TABLE `tb_sirkulasi`( 
	`id_sk` VarChar( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`id_buku` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`id_anggota` VarChar( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`tgl_pinjam` Date NOT NULL,
	`tgl_kembali` Date NOT NULL,
	`status` Enum( 'PIN', 'KEM' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	PRIMARY KEY ( `id_sk` ) )
CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
ENGINE = InnoDB;
-- -------------------------------------------------------------


-- Dump data of "log_pinjam" -------------------------------
-- ---------------------------------------------------------


-- Dump data of "tb_anggota" -------------------------------
BEGIN;

INSERT INTO `tb_anggota`(`id_anggota`,`nama`,`jekel`,`kelas`,`pangkat_gol`,`nip`,`no_hp`) VALUES 
( 'A001', 'Andriawan Sitindaon, S.T., M.Han', '-', 'Kapuskod Baloghan Kemhan | 30-09-2025 | Laksma TNI', 'Laksma TNI', '11939/P', '021-7668062-3' ),
( 'A002', 'Drs. Rofi\'i Ansor, M.A.P.', '-', 'Bidang Tata Kelola Bangsisinfokod | Puskod Baloghan Kemhan | Kolonel Laut (KH)', 'Kolonel Laut (KH)', '12828/P', '0821-1300-8693' ),
( 'A003', 'Bambang Purstyadi, S.Sos', '-', 'Kepala Bidang Dukungan Teknis | Puskod Baloghan Kemhan | Kolonel Cpl', 'Kolonel Cpl', '11930093030970', '021-7668062-3' ),
( 'A004', 'Bernadeta Retno N.P., S.Sos', '-', 'Kepala Bidang Operasional Kodifikasi | Puskod Baloghan Kemhan | Kolonel Adm', 'Kolonel Adm', '520914', '021-7668062-3' ),
( 'A005', 'Mohamad Toha, S.Pd., M.I.R', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan | Kolonel Laut (KH)', 'Kolonel Laut (KH)', '12266/P', '021-7668062-3' ),
( 'A006', 'Taufik Rokhman', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan | Kolonel Adm', 'Kolonel Adm', '521866', '021-7668062-3' ),
( 'A007', 'Andriawan Sitindaon, S.T., M.Han', '-', 'Kapuskod Baloghan Kemhan | 30-09-2025', 'Laksma TNI', '11939/P', '021-7668062-3' ),
( 'A008', 'Drs. Rofi\'i Ansor, M.A.P.', '-', 'Bidang Tata Kelola Bangsisinfokod | Puskod Baloghan Kemhan', 'Kolonel Laut (KH)', '12828/P', '0821-1300-8693' ),
( 'A009', 'Bambang Purstyadi, S.Sos', '-', 'Kepala Bidang Dukungan Teknis | Puskod Baloghan Kemhan', 'Kolonel Cpl', '11930093030970', '021-7668062-3' ),
( 'A010', 'Bernadeta Retno N.P., S.Sos', '-', 'Kepala Bidang Operasional Kodifikasi | Puskod Baloghan Kemhan', 'Kolonel Adm', '520914', '021-7668062-3' ),
( 'A011', 'Mohamad Toha, S.Pd., M.I.R', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Laut (KH)', '12266/P', '021-7668062-3' ),
( 'A012', 'Taufik Rokhman', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Adm', '521866', '021-7668062-3' ),
( 'A013', 'Sahadi, S.I.P.,M.Han', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Cpl', '11980064110276', '021-7668062-3' ),
( 'A014', 'Suprianto, S,P,d,.M.Sc', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Inf', '11000014500474', '021-7668062-3' ),
( 'A015', 'Ichwan Hanryan, S.E', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Caj', '14930067440271', '08156072171' ),
( 'A016', 'M. Dede Solikin, S.T., M.Han', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Tek', '530357', '0813-1340-9008' ),
( 'A017', 'Iwan Setiawan, S.E., M.Tr. Opsla', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Laut (T)', '14980/P', '021-7668062-3' ),
( 'A018', 'Andriansyah R. Nasution, S.H., M.Hum', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Laut (H)', '13119/P', '021-7668062-3' ),
( 'A019', 'Irwahyu Cahyadi', '-', 'Penelaah Teknis Kebijakan Subbidang | Identifikasi dan Kodifikasi Bidang', 'Mayor Cku', '2910017170271', '021-7668062-3' ),
( 'A020', 'Yuniar Fatrosa', '-', 'Pengelola Layanan Operasional | Subbag Tata Usaha Puskod Baloghan Kemhan', 'Peltu Ttu/W', '84317', '021-7668062-3' ),
( 'A021', 'Mahardika Mulia', '-', 'Pengelola Layanan Operasional | Subbidang Nomenklatur dan Klasifikasi', 'Peltu Ekl', '98576', '021-7668062-3' ),
( 'A022', 'Suhandi Pramana', '-', 'Operator Layanan Operasional Subbag | Tata Usaha Puskod Baloghan Kemhan', 'Sertu', '31060618840386', '021-7668062-3' ),
( 'A023', 'Ronny Johan Napitupulu', '-', 'Pengelola Layanan Operasional Subbag | Tata Usaha Puskod Baloghan Kemhan', 'Kopda', '31110007601089', '021-7668062-3' ),
( 'A024', 'Andriansyah R. Nasution, S.H., M.Hum', '-', 'Kataloger Ahli Madya | Puskod Baloghan Kemhan', 'Kolonel Laut (H)', '13119/P', '021-7668062-3' ),
( 'A025', 'Mahardika Mulia', '-', 'Pengelola Layanan Operasional | Subbidang Nomenklatur dan Klasifikasi', 'Peltu Ekl', '98576', '021-7668062-3' ),
( 'A026', 'Suhandi Pramana', '-', 'Operator Layanan Operasional Subbag | Tata Usaha Puskod Baloghan Kemhan', 'Sertu', '31060618840386', '021-7668062-3' ),
( 'A027', 'I Gusti Ayu Nyoman Setiawati, S.T,M.T', 'Perempuan', 'Kataloger Madya | Puskod Baloghan Kemhan', 'Pembina Utama Muda IV/c', '197001151998032003', '0816-1981-920' ),
( 'A028', 'Titim Sumartini, S.E, MM', 'Perempuan', 'Kataloger Madya | Puskod Baloghan Kemhan', 'Pembina Tk.I IV/b', '197612281999032001', '0812-8822-1941' ),
( 'A029', 'Teguh Mulyono,S.Pd, MM', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina TK.I IV/b', '196711301989031001', '0812-1130-4348' ),
( 'A030', 'Pantas Situmorang,S.Sos,M.AP', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina Tk.I IV/b', '196812051992031001', '-' ),
( 'A031', 'Sri Windarti S.Sos, M.M', 'Perempuan', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina TK.I IV/b', '197001271990032001', '-' ),
( 'A032', 'Tomy Wardana, S.Kom, M.A.P', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina TK.I IV/b', '197905132003121001', '081213100224' ),
( 'A033', 'Rachmad Nano Basuki, S.T,M.AB', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina IV/a', '197810142005011001', '08157784282' ),
( 'A034', 'Yusuf Mulyana, S.Kom., M.A', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina IV/a', '197803302003121001', '-' ),
( 'A035', 'Dr. Donny Bayu Sukarno, S.T., M.B.A', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina TK.I IV/b', '197902102003121001', '-' ),
( 'A036', 'Yuliyanto, S.A.P., M.A.P', 'Laki-laki', 'Kataloger Ahli Madya | Pusat Kodifikasi Baloghan Kemhan', 'Pembina IV/a', '197507101995121001', '-' ),
( 'A037', 'Yudha Dwi Pratidya, S.E., M.Han', 'Laki-laki', 'Kataloger Ahli Madya | Puskod Baloghan kemhan', 'Pembina IV/a', '197906122008121001', '-' ),
( 'A038', 'Sri Mulyani, S.E, M.M', 'Laki-laki', 'Kataloger Ahli Madya | Puskod Baloghan kemhan', 'Pembina IV/a', '197003091990032002', '-' ),
( 'A039', 'Nur Cahyo Eko Priyono, S.Kom., M.M', 'Laki-laki', 'Kasubbag Tata Usaha | Puskod Baloghan Kemhan', 'Pembina IV/a', '198204282008121003', '-' ),
( 'A040', 'Retno Tri Tjahjawati Soemarto', 'Perempuan', 'Kepala Subbidang Tata Kelola Bidang | Perencanaan Administrasi Kodifikasi', 'Pembina IV/a', '196911231998032001', '-' ),
( 'A041', 'Juli Syawaludin, S.Sos', 'Laki-laki', 'Kepala Subbidang Pengembangan Kodifikasi | Bidang Perencanaan Administrasi Kodifikasi', 'Penata Tk.I III/d', '198307262008121004', '082135351000' ),
( 'A042', 'Siti Fidyawati, SH', 'Perempuan', 'Kasubbid Fungsional Kataloger | Bidang Dukungan Teknis', 'Pembina IV/a', '198301032006042001', '-' ),
( 'A043', 'Cahyadi Adiwijaya, S.Kom, M.Si (Han)', 'Laki-laki', 'Kepala Subbidang Administrasi Kodifikasi | Biang Opersional Kodifikasi', 'Penata Tk.I III/d', '198103312009121001', '08569951711' ),
( 'A044', 'Tuti Komariah, S.E., S.Sos., M.Tr.A.P', 'Perempuan', 'APN Muda Baloghan Kemhan | 2026', 'Pembina IV/a', '197401201994032001', '-' ),
( 'A045', 'Prihatin Budi Susanto, S.E., M.M', 'Laki-laki', 'APN Muda Baloghan Kemhan | 2026', 'Pembina IV/a', '197402141994031004', '-' ),
( 'A046', 'Agus Wiyono, S.Pd., M.M', 'Laki-laki', 'Kasubbid Publikasi Katalog Materiil | Bidang Dukungan Teknis Kodifikasi', 'Penata Tk.I III/d', '197203031996011001', '-' ),
( 'A047', 'Ni Ketut Ringin Putriani, S.E., M.M', 'Perempuan', 'Kasubbid Kerja sama dan Pelatihan Kodifikasi | Bidang Dukungan Teknis Kodifikasi', 'Penata Tk.I III/d', '198209262010122002', '-' ),
( 'A048', 'Kania Dewi, S.E.', 'Perempuan', 'Penelaah Teknis Kebijakan Subbidang Tata Kelola | Bidang Perencanaan Administrasi Kodifikasi', 'Penata Tk I III/d', '196810041988012001', '082116812996' ),
( 'A049', 'Farindah Astuti S.E, M.M', 'Perempuan', 'Kasubbid Validasi Data Kodifikasi | Bidang Operasional Kodifikasi', 'Penata III/c', '197208071996032001', '08071996032001' ),
( 'A050', 'Budi Wijayanti,S.Pd', 'Perempuan', 'Penelaah Teknis Kebijakan Subbidang | Pengembangan Kodifikasi Bidang', 'Penata Tk.I III/d', '198110192009122001', '081359616042' ),
( 'A051', 'Erni Novita, S.E., M.A', 'Perempuan', 'Kataloger Ahli Muda | Puskod Baloghan Kemhan', 'Penata Tk.I III/d', '198403162009122002', '0813-8247-7183' ),
( 'A052', 'Novita Rahma Irjayanti, S.Sos', 'Perempuan', 'Penelaah Teknis Kebijakan Subbagian Tata Usaha | Puskod Baloghan Kemhan', 'Penata Tk I III/d', '197309201999032001', '087885870222' ),
( 'A053', 'Rima Pertiwi, S.E., M.Han', 'Perempuan', 'Penelaah Teknis Kebijakan Subbidang Sistem Informasi | Kodifikasi Bidang Perencanaan Administrasi Kodifikasi', 'Penata Tk.I III/d', '198509132010122005', '-' ),
( 'A054', 'Danang Putu Prastowo, S,AB', 'Laki-laki', 'Penelaah Teknis Kebijakan Subbidang | Administrasi Kodifikasi Bidang Opskod', 'Penata Tk.I III/d', '197801151999031001', '-' ),
( 'A055', 'Mohamad Badrut Taman.Z', 'Laki-laki', 'Kataloger Penyelia | Puskod Baloghan Kemhan', 'Penata III/c', '198310032006041001', '0821-2521-4033' ),
( 'A056', 'Firdaus Septiadi, A.Md', 'Laki-laki', 'Kataloger Penyelia | Puskod Baloghan Kemhan', 'Penata III/c', '197509022005011001', '-' ),
( 'A057', 'Seno Pramono, S.A.P', 'Laki-laki', 'Kataloger Penyelia | Puskod Baloghan Kemhan', 'Penata III/c', '197411271998031001', '0857-7539-7334' ),
( 'A058', 'Suparno', 'Laki-laki', 'Pengolah Data dan Informasi Subbag Tata Usaha | Puskod Baloghan Kemhan', 'Penda Tk. I III/b', '196903271991031002', '021-87909542' ),
( 'A059', 'Fajar Muharyati, S.A.P., M.A', 'Perempuan', 'Pengolah Data dan Informasi Subbidang | Mutu Data Kodifikasi Bidang Operasional Kodifikasi', 'Penata III/c', '198310312008122001', '0813-8929-6462' ),
( 'A060', 'Alexandra Diaz, S. Sos', 'Perempuan', 'Kataloger Ahli Muda | Puskod Baloghan Kemhan', 'Penata III/c', '197403102003122001', '00-03-2026' ),
( 'A061', 'Muhammad Nur, S.Kom', 'Laki-laki', 'Pengolah Data dan Informasi Subbagian Tata Usaha | Puskod Baloghan Kemhan', 'Penata III/c', '198403122009121002
', '0856-9421-0797' ),
( 'A062', 'Perta Elfrida T, S.H', 'Perempuan', 'Pengolah Data dan Informasi Subbagian | Kerjasama dan Pelatihan Kodifikasi', 'Penda Tk.I III/b', '197501282003122001', '021-435' ),
( 'A063', 'Risnowati, S.A.P., M.A', 'Perempuan', 'Pengolah Data dan Informasi Subbidang | Nomenklatur dan Klasifikasi', 'Penda Tk.I III/b', '198309272006042001', '085714901948' ),
( 'A064', 'Kartika', 'Perempuan', 'Penata Kelola Sistem dan Teknologi Informasi | Subbag Tata Usaha Puskod Baloghan Kemhan', 'Penda Tk.I III/b', '197305092003122001', '05092003122001' ),
( 'A065', 'Purwanto', 'Laki-laki', 'Penata Layanan Operasional Subbidang | Tata Kelola Bidang Perencanaan Administrasi', 'Penda Tk.I III/b', '197105191998031004', '05191998031004' ),
( 'A066', 'Bina Kristiantoro', 'Laki-laki', 'Kataloger Mahir | Puskod Baloghan Kemhan', 'Penda Tk.I III/b', '197212292005011001', '005011001' ),
( 'A067', 'Joko Ratminto', 'Laki-laki', 'Penata Kelola Sistem dan Teknologi Informasi | Subbidang Sistem Informasi Kodifikasi', 'Penda Tk.I III/b', '197201012006041001', '081297514981' ),
( 'A068', 'Santoni, S.Kom', 'Laki-laki', 'Pengolah Data dan Informasi | Subbagian Tata Usaha Puskod Baloghan Kemhan', 'Penda Tk.I III/b', '198005132003121003', '081386450500' ),
( 'A069', 'Khilman Fahruli, A.Md', 'Laki-laki', 'Pranata Komputer Pelaksana / Terampil | Puskod Baloghan Kemhan', 'PPPK VII/ IIc', '198507272023211019', '081284395097' ),
( 'A070', 'M. Haris Suhud, S.Kom', 'Laki-laki', 'Pranata Komputer Ahli Pertama | Puskod Baloghan Kemhan', 'PPPK IX/ III/a', '199603152024211010', '01-03-2024' ),
( 'A071', 'Achmad Budi Setiawan, S.Kom', 'Laki-laki', 'Pranata Komputer Ahli Pertama | Puskod Baloghan Kemhan', 'PPPK \'IX/ III/a', '199810132024211004', '01-03-2024' ),
( 'A072', 'Tuti Wahyuni, S.Pd', 'Perempuan', 'Penata Layanan Operasional Subbidang | Publikasi Katalog Materiil Bidang Dukungan', 'PPPK IX/III/a', '199105252024212012', '05252024212012' ),
( 'A073', 'Ferrando Satria, S.Ak', 'Laki-laki', 'Penata Layanan Operasional Subbagian | Tata Usaha Puskod Baloghan Kemhan', 'PPPK IX/III/a', '199410282024211003', '06775219617000' ),
( 'A074', 'Ali Fasihi, S.E', 'Laki-laki', 'Penata Layanan Operasional Subbidang | Fungsional Kataloger Bidang Dukungan Teknis Kodifikasi', 'PPPK IX/III/a', '199008212024211004', '008212024211004' ),
( 'A075', 'Leli Fitri Amalia, S.E', 'Perempuan', 'Analis Kebijakan Ahli Pertama | Puskod Baloghan Kemhan', 'CPNS Penda III/a', '199901182025062013', '01-06-2025' ),
( 'A076', 'Weli Hasim', 'Laki-laki', 'Operator Layanan Operasional Subbag TU | Puskod Baloghan Kemhan', 'PPPK Paruh Waktu', '199806202025211082', '01-11-2025' ),
( 'A077', 'Dastim, S.E', 'Laki-laki', 'Penata Layanan Operasional Subbag TU | Puskod Baloghan Kemhan', 'PPPK IX/III/a', '199105202025211066', '03-11-2025' ),
( 'A078', 'Muhammad Nur, S.Kom', 'Laki-laki', 'Pengolah Data dan Informasi Subbagian Tata Usaha | Puskod Baloghan Kemhan', 'Penata III/c', '01-04-2026', '0856-9421-0797' ),
( 'A079', 'Dastim, S.E', 'Laki-laki', 'Penata Layanan Operasional Subbag TU | Puskod Baloghan Kemhan', 'PPPK IX/III/a', '199105202025211066', '05202025211066' ),
( 'A080', 'Resi Aditya,S.Kom, MM', 'Laki-laki', 'Kataloger Madya | Puskod Baloghan Kemhan', 'Pembina Tk.I IV/b', '197705142002121001
', '05142002121001' ),
( 'A081', 'Wagiman', 'Laki-laki', 'Penyusun Bahan Ketatausahaan Subbag TU Puskod Baloghan Kemhan', 'Penata III/c', '196002081982021001', '085726508231' ),
( 'A082', 'Sutono', 'Laki-laki', 'Pengolah Bahan Evlap Subbid Tala Bidrenminkod Puskod Baloghan Kemhan', 'Penda Tk.I III/b', '197004161994031001', '081381569603' );
COMMIT;
-- ---------------------------------------------------------


-- Dump data of "tb_buku" ----------------------------------
BEGIN;

INSERT INTO `tb_buku`(`id_buku`,`kode_buku`,`seri_buku`,`judul_buku`,`pengarang`,`penerbit`,`rak`,`th_terbit`,`jumlah`) VALUES 
( 'B001', 'KAM-001', 'Kamus', 'Belanda Indonesia', 'Prof, Drs S. Wojowasito', 'PT. Lestari Perkasa', '', '1976', '5' ),
( 'B002', 'KAM-002', 'Kamus', 'Jepang Indonesia - Kamus Kanji Modern', 'Andrew N. Nelson', 'kesaint blanc', '', '1969', '2' ),
( 'B003', 'KAM-003', 'Kamus', 'Al-Munawwir Arab - Indonesia Terlengkap', 'Ahmad Warson Munawwir', 'Pustaka Progressif', '', '1997', '4' ),
( 'B004', 'KAM-004', 'Kamus', 'Besar Mandarin - Indonesia - Mandarin', 'Tim Prima Pena', 'Reality Publisher', '', '2008', '5' ),
( 'B005', 'KKMP-001', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Konflik dan Integrasi', '', '', '', '2006', '1' ),
( 'B006', 'KKMP-002', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Teknologi dan Industri Pertahanan', '', '', '', '2006', '1' ),
( 'B007', 'KKMP-003', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Manajemen Sumda Pertahanan', '', '', '', '2006', '1' ),
( 'B008', 'KKMP-004', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Strategi Pertahanan', '', '', '', '2006', '1' ),
( 'B009', 'KKMP-005', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Metodologi Penelitian', '', '', '', '2006', '1' ),
( 'B010', 'KKMP-006', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Kajian Lingkungan Strategi', '', '', '', '2006', '1' ),
( 'B011', 'KKMP-007', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Demokrasi dan Demoktratisasi', '', '', '', '2006', '1' ),
( 'B012', 'KKMP-008', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Pengetahuan Good Governance', '', '', '', '2006', '1' ),
( 'B013', 'KKMP-009', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Kepemimpinan Masa Depan', '', '', '', '2006', '1' ),
( 'B014', 'KKMP-010', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Kebijakan Umum Pertahanan', '', '', '', '2006', '1' ),
( 'B015', 'KKMP-011', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Manajemen Wilayah Pertahanan', '', '', '', '2006', '1' ),
( 'B016', 'KKMP-012', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Seminar Pendidikan', '', '', '', '2006', '1' ),
( 'B017', 'KKMP-013', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Reinventing Government', '', '', '', '2006', '1' ),
( 'B018', 'KKMP-014', 'Kursus Kepemimpinan dan Manajemen Pertahanan', 'Doktrin Pertahanan', '', '', '', '2006', '1' ),
( 'B019', 'NSNN-001', '', 'National Stock Numbers (NSN) Volume 2', '', '', '', NULL, '1' ),
( 'B020', 'MA-001', 'Majalah Advokasi', 'Hukum dan Operasi', '', '', '', '2006', '7' ),
( 'B021', 'BK-001', '', 'Buletin Kodifikasi', '', '', '', '2012', '4' ),
( 'B022', 'MD-001', '', 'Majalah Dislitbangal', '', '', '', '2016', '8' ),
( 'B023', 'PPPB-001', '', 'Protap Pencegahan dan penanggulangan bahaya kebakaran dilingkungan pusat kodifikasi', '', '', '', '2002', '1' ),
( 'B024', 'FCST-001', '', 'Federal Catalog System Training Program H55-7 Section C', '', '', '', NULL, '64' ),
( 'B025', 'MFSC-001', '', 'Manual For Supply Cataloguing - Malaysian Armed Forces Cataloguing Authority - 34 - M1', '', '', '', NULL, '1' ),
( 'B026', 'KJ-001', 'Kontrak Jual', 'Beli 81 mm Mortar Type W 87', '', '', '', NULL, '1' ),
( 'B027', 'KJ-002', 'Kontrak Jual', 'Beli Solar 130 LC - V Crawler Excavator', '', '', '', NULL, '1' ),
( 'B028', 'FIIG-001', 'Federal Item Identification Guide', 'Hazard-Detecting Instruments and Appratus', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B029', 'FIIG-002', 'Federal Item Identification Guide', 'Biologicals', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B030', 'FIIG-003', 'Federal Item Identification Guide', 'Hospital and Medical Bags, Cases, Covers, and Pads', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B031', 'FIIG-004', 'Federal Item Identification Guide', 'Packing and Packing Bulk Material', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B032', 'FIIG-005', 'Federal Item Identification Guide', 'Sewing Machines and accessories', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B033', 'FIIG-006', 'Federal Item Identification Guide', 'Fuels : Liquid nd Solid', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B034', 'FIIG-007', 'Federal Item Identification Guide', 'X-RaY Equimpent and supplies mrdicaldrbtal and veterinary', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B035', 'FIIG-008', 'Federal Item Identification Guide', 'Musical Instruments, Phonographs and home radios', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B036', 'FIIG-009', 'Federal Item Identification Guide', 'Metal Materials, Ores, Minerals, aand Alloys', '', 'Defense Logistics Agency', '', '1989', '3' ),
( 'B037', 'FIIG-010', 'Federal Item Identification Guide', 'Preservative and sealing compounds', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B038', 'FIIG-011', 'Federal Item Identification Guide', 'Surgical Dressings', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B039', 'FIIG-012', 'Federal Item Identification Guide', 'Brakes and Brake Components', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B040', 'FIIG-013', 'Federal Item Identification Guide', 'Scales and Balances', '', 'Defense Logistics Agency', '', '1989', '3' ),
( 'B041', 'FIIG-014', 'Federal Item Identification Guide', 'Pumps and Compressor Components', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B042', 'FIIG-015', 'Federal Item Identification Guide', 'Batteries', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B043', 'FIIG-016', 'Federal Item Identification Guide', 'Fuels : Liquid and Solid', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B044', 'FIIG-017', 'Federal Item Identification Guide', 'Fuels : Miscellaneous Meters', '', 'Defense Logistics Agency', '', '1989', '1' ),
( 'B045', 'FIIG-018', 'Federal Item Identification Guide', 'Fuels : Stufing Materials and Leather', '', 'Defense Logistics Agency', '', '1989', '2' ),
( 'B046', 'FIIG-019', 'Federal Item Identification Guide', 'Fuels : Alarm and Signal System', '', 'Defense Logistics Agency', '', '1989', '3' ),
( 'B047', 'FIIG-020', 'Federal Item Identification Guide', 'Fuels : Engine Fuel and Cooling System Components', '', 'Defense Logistics Agency', '', '1989', '4' ),
( 'B048', 'PSTA-001', '', 'Pra Seminar TNI AU tentang Perspektif Pembangunan dan Pengembangan Ratih pada Pelita VII', '', 'Mabesau', '', '1996', '1' ),
( 'B049', 'PSTA-002', 'Pra Seminar TNI AU tentang Pokok', 'Pokok Pikiran tentang Pembangunan ABRI Sebagai kekuatan Sosial Politik pada pelita VII', '', 'Mabesau', '', '1996', '1' ),
( 'B050', 'PSTA-003', 'Pra Seminar TNI AU tentang Pokok', 'Pokok Pikiran Partisipasi Warga Negara dalam Bela Negara', '', 'Mabesau', '', '1996', '1' ),
( 'B051', 'LDPS-001', '', 'Logistics Data Products and Services', '', 'Defense Logistics Services Center', '', '1983', '1' ),
( 'B052', 'FCST-002', '', 'Federal Catalog System Training Program', '', 'Defense Logistics Services Center', '', '1993', '5' ),
( 'B053', 'HPTD-001', '', 'Himpunan Perundangan yang terkait dengan penyelenggaraan dan pengelolaan pertahanan', '', 'Departemen Pertahanan', '', '2007', '7' ),
( 'B054', 'PPTP-001', '', 'Penyusunan Perjanjian (Teori dan Praktek)', '', 'Departemen Pertahanan', '', '2008', '2' ),
( 'B055', 'HPDP-001', '', 'Hukum Pertahanan dalam praktek pelaksanaan tugas pemerintahan', '', 'Departemen Pertahanan', '', '2008', '4' ),
( 'B056', 'MRHP-001', '', 'Menguak Realitas Hukum, Pertahanan dan Hak Asasi Manusia', '', 'Departemen Pertahanan', '', '2008', '3' ),
( 'B057', 'HPPU-001', '', 'Himpunan Peraturan Perundang-undangan terkait dengan tata cara pembentukan peraturan perundang-undangan', '', '', '', '2007', '4' ),
( 'B058', 'HUUM-001', '', 'Himpunan Undang-Undang dan Makalah tentang Hak Asasi Manusia (HAM)', '', '', '', '2007', '7' ),
( 'B059', 'PKBD-001', '', 'Peraturan Kepegawaian yang berkaitan dengan disiplin pegawai negeri sipil', '', 'Departemen Pertahananan', '', '2008', '1' ),
( 'B060', 'KAM-005', 'Kamus', 'Istilah Aneka Hukum', 'Prof. Drs. C.S.T. Kansif S.H.', 'Jala Penerbit', '', '2009', '3' ),
( 'B061', 'HKI-001', '', 'Hukum Kepegawaian di Indonesia', 'Sri Hartini, S.H. M.H.', 'Sinar Grafika', '', '2007', '7' ),
( 'B062', 'KAM-006', 'Kamus', 'Indonesia Jerman', 'Erich - Dieter Krause', 'Dian Rakyat', '', '2000', '5' ),
( 'B063', 'AHPB-001', '', 'Aspek Hukum Pengadaan Barang dan Jasa', 'Adrian Sutedi, S.H., M.H.', 'Sinar Grafika', '', '2009', '9' ),
( 'B064', 'HPK-001', '', 'Hukum Perlindungan Konsumen', 'Celina Tri Siwi Kristiyanti, S.H., M.Hum', 'Sinar Grafika', '', '2008', '1' ),
( 'B065', 'HI-001', '', 'Hukum Internasional', 'Prof. Dr. Yudha Bhakti Ardhi', 'Bunga Rampai', '', '2003', '9' ),
( 'B066', 'PHMM-001', '', 'Penelaahan Hukum Militer untuk memperkuat ketahanan nasional dan perlindungan Hak asasi manusia', '', 'Departemen Pertahanan', '', '2009', '8' ),
( 'B067', 'HTNI-001', '', 'Hukum Tata Negara Indonesia', 'Moh. Kusnardi S.H.', 'FHUI', '', '1988', '10' ),
( 'B068', 'KP-001', 'Kumpulan Perjanjian', 'Perjanjian Internasional tentang batas - batas teritorial dan sumber alam indonesia', '', '', '', NULL, '1' ),
( 'B069', 'BBPP-001', '', 'Beberapa Bahan Pokok Pemikiran Hukum Udara dan Hukum Ruang Angkasa', 'Prof. Dr. H. Priyatna Abdurrasyid, S.H. PH.d', 'Departemen Pertahanan', '', '2009', '9' ),
( 'B070', 'HPPU-002', '', 'Himpunan Peraturan Perundang-undangan Hukum Laut', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B071', 'PATS-001', '', 'Perluasasan Ajaran Turut Serta dalam Pemberantasan Tindak idana Terorisme Trannasional', '', 'Departemen Pertahanan', '', '2009', '4' ),
( 'B072', 'HKPN-001', '', 'Hukum Keuangan dan Perbendahraan Negara', 'Prof. Drs. C.S.T. Kansif S.H.', 'PT Prdaya paramita', '', '2006', '6' ),
( 'B073', 'HPK-002', '', 'Himpunan Peraturan Kepegawaian', '', 'CV. Tamita Utama', '', '2008', '10' ),
( 'B074', 'HPPU-003', '', 'Himpunan Peraturan Perundangan-Undangan Republik Indonesia', '', 'CV. Eka Jaya', '', '2008', '4' ),
( 'B075', 'HPKG-001', '', 'Himpunan Peraturan Kepegawaian tentang gaji baru PNS, Hakim, TNI/Polri', '', 'CV. Tamita Utama', '', '2010', '2' ),
( 'B076', 'BHP-001', 'Buku Himpunan Peraturan', 'Peraturan Panglima Angkatan Bersenjata', '', 'Setum Mabes Abri', '', '1993', '1' ),
( 'B077', 'BHP-002', 'Buku Himpunan Peraturan', 'Peraturan Panglima Angkatan Bersenjata', '', 'Setum Mabes Abri', '', '1997', '1' ),
( 'B078', 'BHAP-001', '', 'Buku Himpunan Amanat, Pengarahan, Sambutan dan Ceramah Menhankam/Pangabri', '', 'Setum Mabes Abri', '', '1998', '1' ),
( 'B079', 'BHPD-001', '', 'Buku Himpunan Peraturan Departemen Pertahanan', '', 'Setum Mabes Abri', '', '2000', '1' ),
( 'B080', 'HAK-001', '', 'Himpunan Aturan Kepegawaian', '', 'CV. Tamita Utama', '', '2006', '1' ),
( 'B081', 'PKMD-001', '', 'Penyelenggaraan Katalogisasi Material dilingkungan Departemen Pertahanan Keamanan dan ABRI', '', '', '', '1986', '1' ),
( 'B082', 'PPPM-001', '', 'Pokok-pokok Pembinaan Materiil Pertahanan Negara di Lingkungan Departemen Pertahanan dan Tentara Nasional Indonesia', '', '', '', NULL, '1' ),
( 'B083', 'PSTA-004', '', 'Pra Seminar TNI Angkatan Udara tentang Perspektif Pembangunan dan Pengembangan Bidang Hankam pada Pelita VII', '', '', '', '1996', '1' ),
( 'B084', 'PPJF-001', '', 'Pedoman Pelaksanaan Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kemhan', '', '2010', '2' ),
( 'B085', 'PDHK-001', '', 'Publikasi Data Hasil Kodifikasi Nomor Sediian Nasional (NSN) tahun 2015', '', 'Kemhan', '', '2015', '3' ),
( 'B086', 'PKAP-001', '', 'Publikasi Katalog Alkesad Puskod Baranahan Kemenhan RI', '', '', '', NULL, '1' ),
( 'B087', 'PBMK-001', '', 'Peraturan Bersama Menhan dan KBKN tentang jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kemhan', '', '2008', '1' ),
( 'B088', 'MBKS-001', '', 'Majalah Badiklat Kemhan Satria', '', 'Kemhan', '', '2014', '4' ),
( 'B089', 'MSSP-001', '', 'Majalah Satria Studi Pertahanan', '', 'Dephan', '', '2005', '7' ),
( 'B090', 'PPMI-001', '', 'Potensi Pertahanan Media Informasi Ditjen Pothan Keamanan', '', 'Dephan', '', '2013', '1' ),
( 'B091', 'MA-002', 'Majalah Advokasi', 'Hukum dan Operasi', '', 'Dephan', '', '2013', '3' ),
( 'B092', 'MBK-001', '', 'Majalah Buletin Kodifikasi', '', 'Dephan', '', '2012', '1' ),
( 'B093', 'MW-001', '', 'Majalah Wira', '', '', '', '2008', '1' ),
( 'B094', 'BK-002', '', 'Buletin Katalogisasi', '', '', '', NULL, '1' ),
( 'B095', 'SBHE-001', '', 'Standar Bangunan Hemat Energi', '', 'Badan Standarisasi Nasional', '', '2008', '1' ),
( 'B096', 'TER-001', 'Terjemahan', 'Acodp-1, Bab I, II dan III', '', 'Puskod Kemhan', '', '2012', '1' ),
( 'B097', 'VDMD-001', '', 'Verifikasi Data Materiil Data Refrensi', '', 'Puskod Kemhan', '', '2013', '1' ),
( 'B098', '7UUT-001', '', '7 Undang Undang Tahun 2008', '', 'CV. Eka Jaya', '', '2008', '11' ),
( 'B099', 'PPRI-001', '', 'Peraturan Presiden Republik Indonesia Nomor 8 Tahun 2008 Tentang Badan Nasional Penanggulangan Bencana', '', '', '', '2008', '1' ),
( 'B100', 'PPPK-001', '', 'Pokok-Pokok Penyelenggaraan kodifikasi materiil pertahanan sistem nomor sediaan nasional dilingkungnan Dephan dan TNI', '', 'Menhan', '', '2008', '1' ),
( 'B101', 'JPBJ-001', '', 'Juklak tentang Pengadaan barang/jasa militer secara elektronik di lingkungan Departemen Petahanan dan Tentara Nasional Indonesia', '', 'Menhan', '', '2007', '1' ),
( 'B102', 'PDKJ-001', '', 'Peraturan Dirjen Kuathan tentang Juklak Prosedur Penghapusan Barang Milik Negara dilingkungan Kemenhan', '', 'Menhan', '', '2019', '1' ),
( 'B103', 'PPPK-002', '', 'Pokok-pokok penyelenggaraan Kodifikasi Materiill Pertahanan Sistem Nomor Sediaan Nasional', '', 'Menhan', '', '2008', '1' ),
( 'B104', 'TCPB-001', '', 'Tata Cara pengadaan Barang dan Jasa militer dengan fasilitas kredit ekspor dilingkungan Dephan', '', 'Dephan', '', '2005', '1' ),
( 'B105', 'PMPP-001', '', 'Peraturan Menteri Pertahanan Pengendalian Inventori material pertahanan negara', '', 'Dirjen Kuathan Menhan', '', '2009', '1' ),
( 'B106', 'PTLN-001', '', 'Penerimaan Tamu Luar Negeri dilingkungan Departemen Pertahanan', '', 'Dephan', '', '2007', '1' ),
( 'B107', 'PBMK-002', '', 'Peraturan Bersama Menhan dan KBKN tentang jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Menhan', '', '2008', '38' ),
( 'B108', 'JPPP-001', '', 'Juklak Prosedur penyelenggaraan Perbendaharaan Materiil dilingkungan Kemenhan dan TNI', '', 'Dirjen Kuathan Kemhan', '', '2010', '1' ),
( 'B109', 'JTCP-001', '', 'Juklak tata cara penyelenggaraan jasa angkutan dan asuransi materiil hasil pengadaan fasilitas kredit ekspor (KE) Foreign Military Sales (FMS)', '', 'Menhan', '', '2007', '1' ),
( 'B110', 'HPPU-004', '', 'Himpunan Peraturan Perundang-Undangan bagi Prajurit TNI', '', 'Babinkum TNI', '', '2016', '8' ),
( 'B111', 'PDMJ-001', '', 'Progjagar Dirjen Matfas dan Jasa', '', 'Ditfasjasa', '', '2000', '1' ),
( 'B112', 'JPDD-001', '', 'Juknis Pengolahan data dengan Komputer inventarisasi kekayaan milik negara dilingkungan Dephankam', '', 'Dephan', '', '1987', '1' ),
( 'B113', 'AJEI-001', '', 'Analisis Jabatan Eselon III, IV dan uraian tugas pekerjaan eselon V pusat Katalogisasi', '', 'Dephan', '', '2000', '1' ),
( 'B114', 'PKPJ-001', '', 'Permenhan Ketentuan Penggunaan Jasa Telekomunikasi dilingkungan Dephan dan TNI', '', 'Dephan', '', '2007', '1' ),
( 'B115', 'HPPT-001', '', 'Himpunan Peraturan pemberantasan tindak pidana Korupsi, kolusi dan nepotisme', '', 'CV. Tamita Utama', '', '2009', '8' ),
( 'B116', 'PPRN-001', '', 'Peraturan Pemerintah RI Nomor 26 tentang Penataan Ruang dan rencana tata ruang wilayah nasional', '', 'CV. Tamita Utama', '', '2008', '10' ),
( 'B117', 'AKI-001', '', 'Antologi Kepustakaan Indonesia', 'Joko Santoso', 'Sagung Seto', '', '2006', '9' ),
( 'B118', 'URN1-001', '', 'UU RI Nomor 10 tentang Pemilihan Umum Anggota DPR, DPD dan DPRD', '', 'CV. Tamita Utama', '', '2008', '7' ),
( 'B119', 'URN4-001', '', 'UU RI Nomor 43 Tahun 2007 tentang Perpustakaan', '', 'CV. Tamita Utama', '', '2009', '10' ),
( 'B120', 'PBJD-001', '', 'Peraturan Baru Jaldis dalam dan luar negeri bagi pejabat negera, PNS dan PTT', '', 'CV. Tamita Utama', '', '2009', '6' ),
( 'B121', 'UURN-001', '', 'Undang-Undang RI Nomor 42 Tahun 2008 tentang Pemilihan Umum Presiden dan Wakil Presiden Tahun 2009', '', 'CV. Tamita Utama', '', '2009', '10' ),
( 'B122', 'PPRN-002', '', 'Peraruran pemerintah RI Nomor 47 dan 48 tahun 2008 tentang Wajib Belajar dan pendanaan pendidikan', '', 'CV. Tamita Utama', '', '2008', '10' ),
( 'B123', 'URN3-001', '', 'UU RI Nomor 36 tahun 2008 Ttg Perubahan keempat atas UU Nomor 7 Tahun 1983', '', 'CV. Eka Jaya Jakarta', '', '2008', '5' ),
( 'B124', 'TER-002', 'Terjemahan', 'Petunjuk Teknis Panduan NMCRL', '', 'Kemhan', '', '2015', '1' ),
( 'B125', 'DPNN-001', '', 'Data Publikasi NSN ke NSPA', '', 'kemhan', '', '2014', '1' ),
( 'B126', 'PPPI-001', '', 'Permenhan tentang Pedoman Pelaksanaan Inpassing Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kemhan', '', '2010', '1' ),
( 'B127', 'PAUD-001', '', 'Pedoman Administrasi Umum Departemen Pertahanan Umum', '', 'Kemhan', '', '2009', '1' ),
( 'B128', 'PPPB-002', '', 'Pedoman Pelaksanaan Pengadaan Barang / Jasa Militer dilingkungan Dephan', '', 'Kemhan', '', '2005', '1' ),
( 'B129', 'KFKP-001', '', 'Kumpulan Formulir Kuesioner Penyusunan Gaji Berbasis Kinerja dan Sistem Merit', '', 'Kemhan', '', '2008', '1' ),
( 'B130', 'SOPA-001', '', 'Standard Operasional Prosedur Administrasi Pemerintahan dilingkungan Kemhan', '', 'Kemhan', '', '2011', '1' ),
( 'B131', 'TER-003', 'Terjemahan', 'Acodp-1, Bab IV, VI dan VII', '', 'Kemhan', '', '2013', '1' ),
( 'B132', 'KMCD-001', '', 'Kepemimpinan Militer Catatan daari Pengalaman', '', '', '', NULL, '1' ),
( 'B133', 'KJBN-001', '', 'Kontrak Jual Beli Nomor Trak/780/PDN/XII/2021/AU Pengadaan Simulator Radar dan laboratorium SKADIK 402 Lanud SMO', '', 'Kemhan', '', '2021', '1' ),
( 'B134', 'PKMS-001', '', 'Proses Kodifikasi Materiil Sistem NSN', '', 'Kemhan', '', '2019', '25' ),
( 'B135', 'VDMP-001', '', 'Verifikasi Data Materiil Pertahanan', '', 'Kemhan', '', '2015', '1' ),
( 'B136', 'PDNS-001', '', 'Publikasi data NCAGE dan SCAGE', '', 'Kemhan', '', '2015', '1' ),
( 'B137', 'HPPU-005', '', 'Himpunan Peraturan Perundang-Undangan Bidang Pertahanan', '', 'Kemhan', '', '2007', '1' ),
( 'B138', 'DIMB-001', '', 'Data Identifikasi Materiil Bekal Komoditi : Senjata Browning Machine Gun', '', 'Mabes TNI AD', '', '1995', '5' ),
( 'B139', 'DP-001', '', 'Direktori Pesantren', '', 'Departemen Agama', '', '2007', '3' ),
( 'B140', 'HPPU-006', '', 'Himpunan Peraturan Perundang-Undangan Bidang Pertahanan', '', 'Dephan RI', '', '2007', '1' ),
( 'B141', 'JHSB-001', '', 'Jurnal Harga Satuan Bahan Bangunan Konstruksi dan Interior', '', '', '', '2009', '1' ),
( 'B142', 'HPBI-001', '', 'Himpunan Peraturan di Bidang Industri dan Perdagangan', '', 'Deprindag', '', '2004', '1' ),
( 'B143', 'KPBB-001', '', 'Konvensi Perserikatan Bangsa-Bangsa tentang Hukum Laut', '', 'Babinkum TNI', '', '2012', '1' ),
( 'B144', 'MPKM-001', '', 'Materi Pelatihan Kodifikasi Materiil Sistem NSN', '', 'Mabes TNI AD', '', '2014', '1' ),
( 'B145', 'VPFR-001', '', 'Verifikasi dan Pengesahan Frekuensi Radio LF, MF, HF, VHF, UHF dan SHF dilingkungan Dephan dan TNI', '', 'Dephan RI', '', '2008', '1' ),
( 'B146', 'SOPA-002', '', 'Standar Operasional Prosedur Administrasi Pemerintahan dilingkungan Baranahan Kemenhan RI', '', 'Kemenhan', '', '2014', '1' ),
( 'B147', 'HASC-001', '', 'Himpunan Amanat, Sambutan, Ceramah, dan Prasarana Menhankam', '', 'Dephan RI', '', '1996', '2' ),
( 'B148', 'MKPI-001', '', 'Manajemen dan Kepemimpinan pendidikan islam', 'Marno, M.Ag, Triyo Supriyatno, S.Pd., M.Ag', 'Refika Aditama', '', '2008', '10' ),
( 'B149', 'KRKN-001', '', 'Keputusan Rapat Kerja Nasional Badan Kesejahteraan Masjid', '', 'Departemen Agama', '', '2007', '1' ),
( 'B150', 'PPM-001', '', 'Pedoman Pemberdayaan Masjid', '', 'Departemen Agama', '', '2007', '1' ),
( 'B151', 'PPTQ-001', '', 'Pedoman Pembinaan Tahfidzul Quran', '', 'Departemen Agama', '', '1982', '1' ),
( 'B152', 'PPK-001', '', 'Pedoman Pembinaan Kemasjidan', '', 'Departemen Agama', '', '2007', '1' ),
( 'B153', 'KKMA-001', '', 'Kumpulan Keputusan Menteri Agama Tentang Susunan Organisasi dan Tata Kerja Unit Pelaksana Teknis', '', 'Departemen Agama', '', '1982', '1' ),
( 'B154', 'PPJH-001', '', 'Pola Pembinaan Jamaah Haji', '', 'Departemen Agama', '', '2006', '2' ),
( 'B155', 'PPPP-001', '', 'Panduan Petunjuk Pelaksanaan Penyelenggaraan MTQ dan STQ', '', 'Lembaga Pengembangan Tilawatil Quran Tingkat Nasional', '', '2007', '4' ),
( 'B156', 'PKKD-001', '', 'Pengorganisasian kegiatan keagamaan dilingkungan instansi dan perusahaan', '', 'Departemen Agama', '', '1995', '1' ),
( 'B157', 'PMCH-001', '', 'Pedoman Media Center Haji', '', 'Departemen Agama', '', '2008', '5' ),
( 'B158', 'PPTB-001', '', 'Panduan Pelaksanaan Takbiran bagi Umat Islam', '', 'Departemen Agama', '', '2003', '2' ),
( 'B159', 'MMDP-001', '', 'Makanan dan Minuman dalam perspektif Syariat Islam', '', 'Departemen Agama', '', '2007', '1' ),
( 'B160', 'PPAO-001', '', 'Petunjuk Pelaksanaan Akreditasi Organisasi Pengelola Zakat', '', 'Departemen Agama', '', '2007', '2' ),
( 'B161', 'PPHM-001', '', 'Panduan Pelestarian Haji Mabrur', '', 'Departemen Agama', '', NULL, '1' ),
( 'B162', 'PPKH-001', '', 'Pedoman Pembinaan Kerukunan Hidup Umat Beragama', '', 'Departemen Agama', '', '2007', '1' ),
( 'B163', 'PPUP-001', '', 'Peraturan Perundang-Undangan Pengelolaan Zakat', '', 'Departemen Agama', '', '2007', '1' ),
( 'B164', 'KPPK-001', '', 'Korps penasihat Perkawinan dan Keluarga', '', 'Departemen Agama', '', '2007', '5' ),
( 'B165', 'KPM-001', '', 'Khutbah Pedoman Muslim', '', 'Pustaka Amani Jakarta', '', NULL, '1' ),
( 'B166', 'TKSB-001', '', 'Tuntutan Keluarga Sakinah Bagi Remaja Usia Nikah', '', 'Departemen Agama', '', '2007', '1' ),
( 'B167', 'DIPM-001', '', 'Dakwah Islam dan Pesan Moral', '', 'Kurnia Kalam Semesta', '', '2002', '1' ),
( 'B168', 'PPPP-002', '', 'Pedoman Pengambangan Pesantren dan Pendidikan Keagamaan', '', 'Departemen Agama', '', '2009', '2' ),
( 'B169', 'PPKP-001', '', 'Perspektif Pembinaan KBIH dan Pasca haji', '', 'Departemen Agama', '', '2002', '10' ),
( 'B170', 'PPPT-001', '', 'Petunjuk Pelaksanaan Pensertifikatan tanah wakaf', '', 'Departemen Agama', '', '2005', '5' ),
( 'B171', 'HKJT-001', '', 'Himpunan Khotbah Jumah Teladan', 'Abu Faris', 'Pustaka Amani jakarta', '', NULL, '1' ),
( 'B172', 'PPPP-003', '', 'Panduan Praktis Pelayanan Pondok Pesantren pada masyarakat bidang ta\'lim', '', 'Departemen Agama', '', '2004', '2' ),
( 'B173', 'PPPP-004', '', 'Pedoman Penyelenggaraan Program Paket A pada pondok pesantren', '', 'Departemen Agama', '', '2004', '2' ),
( 'B174', 'PPPP-005', '', 'Pedoman Penyelenggaraan Program Paket B pada pondok pesantren', '', 'Departemen Agama', '', '2004', '2' ),
( 'B175', 'PKBM-001', '', 'Pedoman Kegiatan Belajar Mengajar Paket A, B, dan C', '', 'Departemen Agama', '', '2004', '2' ),
( 'B176', 'PPPP-006', '', 'Panduan Praktis Pelayanan Pondok Pesantren pada masyarakat bidang muamalah', '', 'Departemen Agama', '', '2004', '2' ),
( 'B177', 'PPPP-007', '', 'Panduan Praktis Pelayanan Pondok Pesantren pada masyarakat bidang ubudyah', '', 'Departemen Agama', '', '2004', '2' ),
( 'B178', 'PPPP-008', '', 'Pedoman Pengembangan Pesantren dan Pendidikan keagamaan tahun 2004-2009', '', 'Departemen Agama', '', '2004', '3' ),
( 'B179', 'MSSP-002', '', 'Majalah Satria Studi Pertahanan', '', 'Dephan', '', '2006', '19' ),
( 'B180', 'TPK-001', '', 'Tindak Pidana Korupsi', 'Evi Hartanti, S.H.', 'Sinar Grafika', '', '2005', '1' ),
( 'B181', 'UURN-002', '', 'Undang-Undang RI Nomor 12 Tahun 2003', '', 'Biro Hukum Setjen Kemhan', '', '2004', '1' ),
( 'B182', 'RPAN-001', '', 'Reformasi Pengelolaan Anggaran Negara Sistem Penganggaran Berbasis Kinerja', 'Dr. Mulia P. Nasution', 'Pustaka Sinar harapan', '', '2005', '1' ),
( 'B183', 'SKTE-001', '', 'Sistem Keamanan Transportasi Email', '', 'Kemenkominfo', '', '2011', '1' ),
( 'B184', 'LLPK-001', '', 'Lika Liku Penyusunan Kontrak', '', 'Departemen Pertahanan', '', '2009', '1' ),
( 'B185', 'MAJP-001', '', 'Metode Analisis Jalur (Path Analysis) dan Aplikasinya', 'Dr. Nidjo Sanjojo, M.Sc', 'Pustaka Sinar Harapan', '', '2011', '1' ),
( 'B186', 'GC-001', '', 'Gita Cinta', '', '', '', NULL, '1' ),
( 'B187', 'ATWS-001', '', 'A treasure Worth Seeking (permata hati)', 'Sandra Brown', 'Pt. Gramedia Pustaka utama', '', '2000', '1' ),
( 'B188', 'BC-001', '', 'Barbara Cartland', '', '', '', NULL, '1' ),
( 'B189', 'PTPA-001', '', 'Peta Taman Pengajian Alquran', '', 'Kementerian Agama', '', '1994', '1' ),
( 'B190', 'PPKP-002', '', 'Petunjuk Praktis Keselamatan Penerbangan Haji', '', 'Kementerian Agama', '', '2005', '2' ),
( 'B191', 'PSAP-001', '', 'Pembakuan Sarana Alat Peraga Bimbingan Manasik Haji', '', 'Kementerian Agama', '', '2007', '1' ),
( 'B192', 'TM-001', '', 'Tipologi Masjid', '', 'Kementerian Agama', '', '2007', '1' ),
( 'B193', 'UJSE-001', '', 'Uraian Jabatan Struktural Eselon II, III, dan IV diingkungan Kemenhan', '', 'kemenhan', '', '2011', '1' ),
( 'B194', 'MKMN-001', '', 'Merangkai Kata Menguntai Nada Series', 'Bahrudin Supardi', 'Rosda', '', '2009', '9' ),
( 'B195', 'MBM-001', '', 'Mereka Bicara Mega', 'Zainun Ahmadi & Rahadi Zakaria', 'Yayasan Paragraf', '', '2008', '4' ),
( 'B196', 'GSPE-001', '', 'Gusdur Santri Par Excellence', 'Zuhairi Misrawi', 'Kompas', '', '2010', '4' ),
( 'B197', 'PPAK-001', '', 'Pemeliharaan dan Perawatan Arsip Kertas', '', 'ANRI', '', '2009', '1' ),
( 'B198', 'MBJ-001', '', 'Mereka Bicara JK', '', 'National Press Club Indonesia', '', '2009', '4' ),
( 'B199', 'PSPP-001', '', 'Perjalanan Seorang Prajurit Para Komando', 'Hendro Subroto', 'Kompas', '', '2009', '8' ),
( 'B200', 'PPPP-009', '', 'Permenhan Tentang Pedoman pelaksanaan Pengadaan Barang/Jasa Militer Dilingkungan Dephan', '', 'Dephan', '', '2006', '1' ),
( 'B201', 'MT-001', '', 'Majalah Trubus', '', '', '', NULL, '1' ),
( 'B202', 'UURI-001', '', 'Undang Undang Republik Indonesia Nomor 14 Tahun 2008', '', 'Kemenkominfo', '', '2008', '1' ),
( 'B203', 'MTND-001', '', 'Modul Tata naskah dinas, manajemen arsip inaktif, jabatan fungsional arsiparis, manajemen persuratan dan formulir, Program arsip vital, pengantar kearsipan, sejarah kearsian, sistem pemberkasan', '', 'ANRI', '', '2009', '8' ),
( 'B204', 'SADS-001', '', 'Superior Air defense System sebagai penegak supremasi hukum dan kedaulatan di wilayah udara NKRI', '', 'Labda Prakarsa Nirwikara', '', NULL, '1' ),
( 'B205', 'MKC-001', '', 'Membongkar kegagalan CIA', 'Tim Weiner', 'Gramedia Pustaka Utama', '', '2008', '6' ),
( 'B206', 'PTBL-001', '', 'Petunjuk Teknis Bnetuk Laporan Akuntabilitas Kinerja Departemen Pertahanan', '', '', '', '2004', '1' ),
( 'B207', 'PPVD-001', '', 'Petunjuk pengoprasian verifikasi data materil', '', '', '', '2013', '1' ),
( 'B208', 'MPN-001', '', 'Metode Penetapan NSN', '', '', '', '2009', '1' ),
( 'B209', 'PDKK-001', '', 'Penyusunan Data Kodifikasi Kapal Corvette Sigma', '', '', '', '2016', '1' ),
( 'B210', 'PKMS-002', '', 'Penngenalan Kodifikasi Materil Sistem NSN', '', '', '', '2017', '1' ),
( 'B211', 'PAIC-001', '', 'Pengenalan Aplikasi INTEGRATED CODIFICATION INFORMATION SYSTEM informasi Layanan dan Proses Kodifikasi Sistem NSN', '', '', '', '2017', '1' ),
( 'B212', 'PRCF-001', '', 'Pengenalan Refrensi CO FEDLOG (Federal Logistics)', 'Titim Sumartini,SE,MM', '', '', '2017', '1' ),
( 'B213', 'PRCN-001', '', 'Pengenalan Referensi CD NMCRL (NATO Master Catalogu of References For Logistic)', '', '', '', '2017', '1' ),
( 'B214', 'PPN-001', '', 'Panmduan Penggunaan NMRCRL', '', '', '', NULL, '1' ),
( 'B215', 'MTDP-001', '', 'Metode & Teknik dalam Proses Belajar Mengajar', '', '', '', NULL, '1' ),
( 'B216', 'SOPA-003', '', 'Standar Operasional Prosedur Administrasi Pemerintahan Di Lingkungan Badan Sarana Pertahanan Kementrian Pertahanan', '', '', '', '2014', '1' ),
( 'B217', 'IN1O-001', '', 'Indonesia NO.1 Official Tri-Service Defence, Aerospace and Marine Event', '', '', '', '2010', '1' ),
( 'B218', 'PKMS-003', '', 'Publikasi Kodifikasi Materil Sistem NSN', '', '', '', '2014', '1' ),
( 'B219', 'UJEI-001', '', 'Uraian Jabatan Eselon II, III, IV Dan Tenaga Fungsional Gol IV Puskod dan Pusrehabcat Dephan', '', 'Biro Perencanaan Serjen Dephan', '', '2006', '2' ),
( 'B220', 'BPIE-001', '', 'Birokrasi Pemerintah Indonesia di Era Reformasi', 'Miftah Thoha', 'Kencana Prenada Media Group', '', '2008', '1' ),
( 'B221', 'AM-001', '', 'Applied Mathematics', 'Frank S Budnick', 'McGraw Hill International Book Company', '', '1981', '1' ),
( 'B222', 'MBS-001', '', 'Modern Business Statistics', 'Ronald L Iman & W J Conover', 'John Wiley & Sons', '', '1983', '1' ),
( 'B223', 'MK-001', '', 'Manajemen Keuangan', 'J Fred Weston & Thomas E Copeland', 'The Dryren Press', '', '1991', '1' ),
( 'B224', 'MSKN-001', '', 'Mengenal Sistem Kodifikasi Nasional Jembatan Menuju Logistik Modern', '', '', '', NULL, '1' ),
( 'B225', 'MAKL-001', '', 'Materi Ajaran Katalogisasi Pada Latihan dalam Dinas', '', '', '', '2005', '1' ),
( 'B226', 'PPPK-003', '', 'Pokok Pokok Penyelengaraan Katalogisasi Materiil Pertahanan dilingkungan Dephan dan TNI', 'Kolonel Laut (S) Ir. S. Bonar Harahap', '', '', '2007', '1' ),
( 'B227', 'PPBK-001', '', 'Pencegahan Dan Penanggulangan Bahaya Kebakaran di Lingkungan Pusat Kodifikasi Dephan', 'Departemen Pertahanan RI Pusat Kodifikasi', '', '', '2002', '1' ),
( 'B228', 'GOKD-001', '', 'Geladi Olah Krida Dephan RI Nusantara Jaya I', '', '', '', '2007', '3' ),
( 'B229', 'FCST-003', '', 'Federal Catalog System Training Program', '', 'Defense Logistics Services Center Battle Creek', '', NULL, '1' ),
( 'B230', 'NAKP-001', '', 'Naskah Akademik "Keputusan Presiden Republik Indonesia tentang Tunjangan Kompensasi bagi Pegawai Arsip Nasional Republik Indonesia', '', 'Departemen Kelautan dan Perikanan', '', '2004', '1' ),
( 'B231', 'KBPD-001', '', 'Ketentuan Biaya Perjalanan Dinas Keluar Negeri di Lingkungan Dephan dan TNI', '', 'Departmen Pertahanan Republik Indonesia', '', '2005', '1' ),
( 'B232', 'RPP2-001', '', 'Rencana Pelaksanaan Proyek 2009', '', 'Departemen Perthanan RI', '', '2009', '1' ),
( 'B233', 'PPPB-003', '', 'Peedoman Pelasanaan Pengadaan Barang / Jasa Militer di Lingkungan Pertahanan dan TNI', '', 'Departemen Perthanan RI', '', '2004', '1' ),
( 'B234', 'PPPI-002', '', 'Pokok Pokok Penyelengaraan Inventarisasi dan Penatausahan barang milik negara dilingkungan Dephan dan TNI', '', 'Departemen Perthanan Keamanan', '', '1989', '1' ),
( 'B235', 'BSPK-001', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi', '', 'Kementerian Pertahanan', '', '2013', '1' ),
( 'B236', 'DKME-001', '', 'Data Kodifikasi Materiil Engine Pesawat C-130', '', 'Kementerian Pertahanan', '', '2020', '1' ),
( 'B237', 'SKJS-001', '', 'Standard Kompetensi Jabatan Struktural Badan Sarana Petahanan', '', 'Kementerian Pertahanan', '', '2011', '1' ),
( 'B238', 'TER-004', 'Terjemahan', 'Allied Codification Publication 1', '', 'Badan Sarana Pertahanan Kemhan', '', '2014', '1' ),
( 'B239', 'PNB-001', '', 'Penetapan Nama Barang', '', 'Kementerian Pertahanan', '', '2006', '1' ),
( 'B240', 'PPAB-001', '', 'Pedoman Penyusunan Analisis Beban Kerja di Lingkungan Kementerian Pertahanan', '', 'Kementerian Pertahanan', '', '2011', '1' ),
( 'B241', 'PTPJ-001', '', 'Petunjuk Teknis Pengamanan Jaringan Komputer LAN Ditjen Matfasjasa Dephan', '', 'Kementerian Pertahanan', '', '2000', '1' ),
( 'B242', 'TCPS-001', '', 'Tata Cara Penyusunan Standard Militer Indonesia', '', 'Departemen Pertahanan Republik Indonesia', '', '2007', '1' ),
( 'B243', 'IISK-001', '', 'Istilah Istilah Pada Sistem Kodifikasi NATO', '', 'Departemen Pertahanan Republik Indonesia', '', '2006', '2' ),
( 'B244', 'PPNP-001', '', 'POKJA Publikasi NSN Produk Alkesad', '', 'Departemen Pertahanan Republik Indonesia', '', '2011', '1' ),
( 'B245', 'PTTC-001', '', 'Petunjuk Teknis Tata Cara Inpassing Jabatan Fungsional Kataloger', '', '', '', NULL, '2' ),
( 'B246', 'LPPK-001', '', 'Laporan Pelaksanaan Pendidikan Kursus Intensif Bahasa Inggris Departemen Pertahaanan Angkatan LXIV', '', 'Pusat Kodifikasi', '', '2005', '1' ),
( 'B247', 'LPJP-001', '', 'Laporan Pertanggung Jawaban Pengurus Tutup Tahun 2010', '', 'Pusat Kodifikasi', '', '2010', '1' ),
( 'B248', 'KISK-001', '', 'Kursus Indiktrinasi Sistem Katalog ATM (LLP)', '', 'Malaysian Armed Forces Cataloguing Authority', '', '2004', '2' ),
( 'B249', 'FIIG-021', '', 'Federal item Identification Guide Miscellaneous Relay And Switch Components', '', '', '', '1989', '4' ),
( 'B250', 'RKAK-001', '', 'Rencana Kerja dan Anggaran Kementrian Lembaga (RKA-KL) Baranahan Kemhan TA.2014', '', '', '', '2013', '1' );

INSERT INTO `tb_buku`(`id_buku`,`kode_buku`,`seri_buku`,`judul_buku`,`pengarang`,`penerbit`,`rak`,`th_terbit`,`jumlah`) VALUES 
( 'B251', 'FIIG-022', '', 'Federal item Identification Guide Waveguide', '', '', '', '1993', '1' ),
( 'B252', 'FIIG-023', '', 'Federal Item identification Guide Automobiles, Buses, And Trucks', '', '', '', '1993', '1' ),
( 'B253', 'FIIG-024', '', 'Federal Item Identification Guide Miscellaneous Items', '', '', '', '1993', '1' ),
( 'B254', 'FIIG-025', '', 'Federal Item Identification Guide Knobs, Dials, And Pointers', '', '', '', '1993', '2' ),
( 'B255', 'FIIG-026', '', 'Federal Item Identification Guide Trousersn and Trousers - Like Garments', '', '', '', '1997', '1' ),
( 'B256', 'FIIG-027', '', 'Federal Item Identification Guide Filters, Stainers, and Elements', '', '', '', '1981', '1' ),
( 'B257', 'FIIG-028', '', 'Federal Item Identification Guide Counters, Rotating and Reciprocating', '', '', '', '1981', '1' ),
( 'B258', 'FIIG-029', '', 'Federal Item Identification Guide Switches, RF Transmission Line And Waveguide', '', '', '', '1981', '2' ),
( 'B259', 'FIIG-030', '', 'Federal Item Identification Guide Structural Shapes, Nonmetallic', '', '', '', '1982', '1' ),
( 'B260', 'FIIG-031', '', 'Federal Item Identification Guide Sensitized Materials, Photographic and Copying', '', '', '', '1989', '1' ),
( 'B261', 'FIIG-032', '', 'Federal Item Identification Guide Nails,Staples, and Tacks', '', '', '', '1967', '1' ),
( 'B262', 'FIIG-033', '', 'Federal Item Identification Guide Lumber, Plywood, and Related Products', '', '', '', '1981', '1' ),
( 'B263', 'FIIG-034', '', 'Federal Item Identification Guide Clamps, Hose, Loop, Bridge, and Repair Pipe', '', '', '', '1988', '1' ),
( 'B264', 'FIIG-035', '', 'Federal Item Identification Guide Block And Tackle', '', '', '', '1977', '2' ),
( 'B265', 'FIIG-036', '', 'Federal Item Identification Guide ink', '', '', '', '1976', '1' ),
( 'B266', 'FIIG-037', '', 'Federal Item Identification Guide Light Switch', '', '', '', '1976', '1' ),
( 'B267', 'FIIG-038', '', 'Federal Item Identification Guide Antenna Parts', '', '', '', '1981', '1' ),
( 'B268', 'FIIG-039', '', 'Federal Item Identification Guide Subassemblies', '', '', '', '1992', '1' ),
( 'B269', 'FIIG-040', '', 'Federal Item Identification Guide Consoles, Switchboards,and Panels', '', '', '', '1989', '1' ),
( 'B270', 'FIIG-041', '', 'Federal Item Identification Guide Books, Maps, and Other Aplications', '', '', '', '1989', '1' ),
( 'B271', 'FIIG-042', '', 'Federal Item Identification Guide Miscelleneous Relay and Switch Components', '', '', '', '1989', '1' ),
( 'B272', 'PKDN-001', '', 'Publikasi Katalog Data NSN Alkesad dan Alkesau', '', '', '', '2014', '2' ),
( 'B273', 'PIDI-001', '', 'Product Of Indonesian Defence Industries', '', '', '', '2015', '1' ),
( 'B274', 'IDIF-001', '', 'Indonesian Defense Industry For Non - Weapone System Products Catalogue', '', '', '', '2010', '1' ),
( 'B275', 'PKSS-001', '', 'Publikasi Katalog Senjata SS2-V4 Produk PT. Pindad Indonesia', '', '', '', '2012', '1' ),
( 'B276', 'KMP-001', '', 'Katalogisasi Materiel Pertahanan', '', '', '', '2005', '1' ),
( 'B277', 'KBPD-002', '', 'Ketentuan Biaya Perjalanan Dinas luar Negeri di Lingkungan Dephan dan TNI', '', '', '', '2005', '1' ),
( 'B278', 'PPRI-002', '', 'Peratuiran Pemerintah Republik Indonesia Nomor 40 Tahun 1994 Tentang Rumah Negara', '', '', '', NULL, '1' ),
( 'B279', 'PPAP-001', '', 'Petunjuk Penggunaan Aplikasi Pengumuman Pengadaan Barang dan Jasa Pemerintahan Republik Indonesia', '', '', '', '2006', '1' ),
( 'B280', 'KJBN-002', '', 'Komtrak Jual Beli nomor KJB/005/VI/2007', '', 'Puisat Kodifikasi', '', '2007', '1' ),
( 'B281', 'PTPP-001', '', 'Pertunjuk Teknis Pengelolaan Program, Anggaran dan Pembiayaan di Lingkungan Unit Organisasi Departemen Pertahanan', '', 'Departemen Pertahanan RI', '', '2009', '1' ),
( 'B282', 'NMOC-001', '', 'NATO Manual On Codification Chapter 3 Item Clasification', '', '', '', '1996', '1' ),
( 'B283', 'DOPA-001', '', 'Dtandar Operasional Prosedur Administrasi Pemerintahan Di Lingkungan Badan Sarana Pertahanan Kementrian Pertahanan', '', 'Pusat Kodifikiasi', '', '2014', '1' ),
( 'B284', 'MHKL-001', '', 'Mekanisme Hubungan Kerja Di Lingkungan Badan Sarana Pertahanan Kementrian Pertahanan', '', 'Pusat Kodifikiasi', '', '2014', '1' ),
( 'B285', 'LPPK-002', '', 'Laporan Pelaksanaan Program Kerja Dan Anggaran Serta Daya Serap Pusat Kodifikasi Baranahan Kemhan Periode 1 Jan S.D 30 JUNI 2013', '', 'Pusat Kodifikiasi', '', '2013', '1' ),
( 'B286', 'LPPK-003', '', 'Laporan Pelaksanaan Program Kerja Dan Anggaran Serta Daya Serap Pusat Kodifikasi Baranahan Kemhan Periode 1 Jan S.D 31 Maret 2013', '', 'Pusat Kodifikiasi', '', '2013', '1' ),
( 'B287', 'LEPP-001', '', 'Laporan Evaluasi Pelaksanaan Program Dan Anggaran Serta Daya Serap Pusat Kodifikasi Baranahan Kemhan 2 Jan S.D 30 JUNI 2013', '', 'Pusat Kodifikasi', '', '2013', '1' ),
( 'B288', 'PKM-001', '', 'Pengenalan Katalogisasi Materiil', '', 'Departemen Pertahanan', '', NULL, '1' ),
( 'B289', 'BPPK-001', '', 'Bahan Pelajaran Tentang Pemeliharaan Katalog', 'Ditjen Matfasjasa Dephankam', 'Departemen Pertahanan', '', NULL, '1' ),
( 'B290', 'DIMB-002', '', 'Data Indentifikasi Materiel Bekal Komoditi : Ranmor', '', 'Markas Besar TNI AD Staff Logistik', '', '1994', '1' ),
( 'B291', 'RFCC-001', '', 'Rules For The Classification and Construction of Seagoing Steel Ships Volume III', '', 'Biro Klasifikasi Indonesia', '', '1978', '1' ),
( 'B292', 'SSTK-001', '', 'Syarat Syarat Tipe Kendaraan Bermotor Taktis 3/4 Ton Sebagai Penarik Meriam', '', 'Markas Besar TNI AD', '', '1984', '1' ),
( 'B293', 'DIMB-003', '', 'Data Indentifiaksi Materiel Bekal Komoditi : Munisi Granat Meriam 40MM, 57MM Granat Kanon 75MM, 76MM', '', 'Markas Besar TNI AD Staff Logistik', '', '1995', '1' ),
( 'B294', 'DKPF-001', '', 'Data Kodifikasi Pesawat F-16 Tahun 2019', '', 'Kementerian Pertahanan', '', '2019', '1' ),
( 'B295', 'PTJF-001', '', 'Petunjuk Teknis Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kementerian Pertahanan', '', '2010', '2' ),
( 'B296', 'SOPA-004', '', 'Standar Operasional Prosedur Administrasi Pemerintahan di Lingkungan Badan Sarana Pertahanan Kementerian Pertahanan', '', 'Kementerian Pertahanan', '', '2014', '1' ),
( 'B297', 'BMH-001', '', 'Bimbingan Manasik Haji', '', 'Kementerian Agama', '', '2003', '1' ),
( 'B298', 'HIH-001', '', 'Hikmah Ibadah Haji', '', 'Kementerian Agama', '', '2003', '1' ),
( 'B299', 'BPSP-001', '', 'Buku Panduan Sosialisasi Penerapan Kodifikasi Materiil Sistem NSN', '', 'Departemen Pertahanan RI', '', '2010', '1' ),
( 'B300', 'DPEI-001', '', 'Daftar Pejabar Eselon I dan II Kementerian Pertahanan RI Tahun 2010', '', 'Biro Tata Usaha Kementerian Pertahanan RI', '', '2010', '1' ),
( 'B301', 'DPEI-002', '', 'Daftar Pejabar Eselon I dan II Kementerian Pertahanan RI Tahun 2011', '', 'Biro Tata Usaha Kementerian Pertahanan RI', '', '2011', '1' ),
( 'B302', 'MSUU-001', '', 'Materi Sosialisasi Undang Undang Dasar Negara Republik Indonesia Tahun 2945', '', 'MPR RI', '', '2006', '1' ),
( 'B303', 'MPB-001', '', 'Metode Publikasi Bekal', '', 'Pusat Kodifikasi Dephan', '', '2007', '1' ),
( 'B304', 'PK-001', '', 'Publikasi Katalog', '', 'Departemen Pertahanan RI', '', '2006', '1' ),
( 'B305', 'BPMF-001', '', 'Buku Panduan Tentang Microsoft Frontpage', '', 'Sekertariat Direktorat Jenderal bagian Data dan Informasi', '', '2005', '1' ),
( 'B306', 'PPKK-001', '', 'Program Pengajaran Kurikulum Kursus Kejuruan katalogsasi', '', 'Markas Besar TNI AU', '', '1990', '1' ),
( 'B307', 'PPPM-002', '', 'Pokok Pokok Pembinaan Materiel Pertahanan Negara', '', 'Departemen Pertahanan Republik Indonesia', '', '2003', '1' ),
( 'B308', 'BPPP-001', '', 'Buku Pegangan Penyelengaraan Pembekalan Materiel TNI AU', '', 'Markas Besar TNI AU', '', '1992', '1' ),
( 'B309', 'PPSK-001', '', 'petunjuk Pengoperasian Sistem Komputerisasi Renja', '', '', '', '2009', '1' ),
( 'B310', 'TCPB-002', '', 'Tata Cara Pengadaan Barang / Jasa dengan fasilitas kredit ekspor', '', 'Kementerian Pertahanan', '', '2006', '1' ),
( 'B311', 'PTGK-001', '', 'Petunjuk Teknis Grup dan Klas Materiel Bekal', '', 'Departemen Pertahanan RI', '', '2002', '1' ),
( 'B312', 'IMB-001', '', 'Identifikasi Materiil Bekal', '', 'Departemen Pertahanan RI', '', '2009', '1' ),
( 'B313', 'BKE3-001', '', 'Buletin Kodifikasi edisi 35', '', 'Media Informasi Komunikasi Katalog', '', '2011', '5' ),
( 'B314', 'BKE3-002', '', 'Buletin Kodifikasi edisi 36', '', 'Media Informasi Komunikasi Katalog', '', '2011', '1' ),
( 'B315', 'ICIS-001', '', 'Integrated Codification Information System', '', 'Kementerian Pertahanan', '', '2015', '1' ),
( 'B316', 'BPPP-002', '', 'Buku Panduan : Pusat Pertukaran Data Elektronis Pusat Kodifikasi Dephan', '', 'NATO Mailbox System', '', NULL, '1' ),
( 'B317', 'PRDK-001', '', 'Panduan Riset Data Katalog Meteriil Pertahanaan', '', 'Pusat Kodifikasi Dephan RI', '', '2009', '1' ),
( 'B318', 'PDKA-001', '', 'Penyusunan Data Kodifikasi Alutsista Produk Luar Negeri TNI AD', '', 'Kementerian Pertahanan', '', '2016', '1' ),
( 'B319', 'PKRM-001', '', 'Publikasi Katalog Rocket Motor dan Warhead 2,75 Inchi Produk PT. Dirgantara Indonesia', '', 'Kementerian Pertahanan', '', '2012', '1' ),
( 'B320', 'PDKT-001', '', 'Pemutakhiran Data KHN tahun 2015', '', 'Kementerian Pertahanan', '', '2015', '1' ),
( 'B321', 'DMBD-001', '', 'Daftar Materiel Bekal Dephan dan TNI serta produk industri nasional yang telah diidentifikasi dengan sistem NSN', '', 'Departemen Pertahanan RI', '', '2004', '3' ),
( 'B322', 'DIMB-004', '', 'Data Identifikasi Materiel Bekal Komoditi : Ranmor "Jenis Jeep Suzuki Katana SJ410"', '', 'Markas Besar TNI AD Staff Logistik', '', '1994', '1' ),
( 'B323', 'DIMB-005', '', 'Data Identifikasi Materiel Bekal Komoditi : Ranpur', '', 'Markas Besar TNI AD Staff Logistik', '', '1993', '1' ),
( 'B324', 'OLOB-001', '', 'Observasi Lapangan (OL) "Bahan Ajar Diklatpim Tingkat IV"', 'Drs. H.M. Mansyur A.R & Dra. Hj. Titik Rostiah', 'Lembaga Administrasi Negara', '', '2003', '1' ),
( 'B325', 'CHFS-001', '', 'Cataloging Handbook Federal Supply Chain Classification Part 1 "Group and Classes"', '', 'Defense Logistics Agency', '', '1982', '1' ),
( 'B326', 'MPFO-001', '', 'Manual of Produres for Operations Certification and Inspection', '', 'International Civil Aviation Organization', '', '1983', '1' ),
( 'B327', 'SPSP-001', '', 'Sistem Perencanaan Strategik Pertahanan Keamanan Negara (Sisrenstra Hankamneg) Buku II', '', '', '', '1997', '1' ),
( 'B328', 'SKMM-001', '', 'Simulasi Komputer Menggunakan Model Markov Untuk Perencanaan Personel Perwira TNI AL', 'Arwani', 'Universitas Pembangunan Nasional "Veteran" Jakarta', '', '2007', '1' ),
( 'B329', 'OPJK-001', '', 'Optimalisasi Peran Jaringan Komunikasi Sandi Kejaksaan (JKSK) Dalam Pengamanan Data dan Informasi di Kejaksaan Agung RI', 'Rike Yolanda, SH', 'Sandiman Lanjutan Angkatan 99 PUSDIKLAT BUMI SANAPATI BOGOR', '', '2012', '1' ),
( 'B330', 'HPLS-001', '', 'Haystack Parts & Logistics Service "Quick Start for Windows"', '', 'IHS', '', NULL, '1' ),
( 'B331', 'PPA-001', '', 'Pedoman Penggunaan Administrator', 'Titim Sumartini', 'Pusat Kodifikasi', '', NULL, '1' ),
( 'B332', 'PTNJ-001', '', 'Petunjuk Teknis Nomor : JUKNIS/01/XII/2012 tentang Validasi Data Kodifikasi Materiil', '', 'Kementerian Pertahanan', '', '2012', '1' ),
( 'B333', 'ITOS-001', '', 'Introdution to Oracle9i : SQL "Additional Practice Volume 3"', '', 'Oracle University', '', NULL, '1' ),
( 'B334', 'OPTD-001', '', 'Optimalisasi Peran TNI dalam Membangun Kehidupan Berbangsa dan Bernegara', '', 'PUSPEN TNI', '', '2010', '1' ),
( 'B335', 'TRTS-001', '', 'Terms of Reference (TOR) Sistem Aplikasi T.A.2014', '', 'Subbidang sisinfokod', '', '2014', '1' ),
( 'B336', 'LEPP-002', '', 'Laporan Evaluasi Pelaksanaan Program Dan Anggaran Serta Daya Serap Pusat Kodifikasi Baranahan Kemhan Periode 1 JAN S.D 31 DEC 2012', '', 'Pusat Kodifikasi', '', '2012', '1' ),
( 'B337', 'EPRB-001', '', 'Evaluasi Pelaksanaan Renstra Baranahan Kemhan Tahun 2010-2014', '', 'Kementrian Pertahanan', '', '2014', '1' ),
( 'B338', 'RKAR-001', '', 'Rencana Kerja Anggaran (RKA) Tahun 2013 Pusat Kodifikasi Baranahan Kemhan', '', '', '', '2012', '1' ),
( 'B339', 'LEPP-003', '', 'Laporan Evaluasi Pelaksanaan Program Dan Anggaran Serta Daya Serap Pusat Kodifikasi Baranahan Kemhan 1 Jan S.D 31 Dec 2012', '', 'Pusat Kodifikasi', '', '2012', '1' ),
( 'B340', 'LEPP-004', '', 'Laporan Evaluasi Pelaksanaan Program dan Anggaran Pusat Kodifikasi Baranahan Kemhan 2 JAN S.D 31DEC 2013', '', 'Pusat Kodifikasi', '', '2013', '1' ),
( 'B341', 'DKKL-001', '', 'DIKTAT Kursus Kejuruan Lanjutan Tamtama Kataloging', '', '', '', NULL, '1' ),
( 'B342', 'KPSK-001', '', 'Kurikulum Pendidikan Sekolah Kejuruan Bintara Kataloging', '', '', '', '1995', '1' ),
( 'B343', 'KPSK-002', '', 'Kurikulum Pendidikan Sekolah Kejuruan Dasar Tamtama Kataloging', '', '', '', '1996', '1' ),
( 'B344', 'LT2A-001', '', 'Laporan Tahun 2005 Annual Report PT ENSEVAL', '', '', '', '2006', '1' ),
( 'B345', 'KSKB-001', '', 'Kurikulum Sekolah Kejuruan Bintara Kataloging', '', '', '', '1995', '1' ),
( 'B346', 'PPCD-001', '', 'Petunjuk Pengoprasian CD DIRS DDP dan Spesifikasi Teknis', '', 'Pusat Kodifikasi Dephan', '', '2003', '2' ),
( 'B347', 'BHPU-001', '', 'Buku Himpunan Perundang - Undangan Yang Terkait Dengan Penyelenggaraan Dan Pengelolaan Pertahanan', '', 'Derpartemen Pertahanan', '', '2004', '6' ),
( 'B348', 'PPLA-001', '', 'Pedoman Penyusunan Laporan Akuntabilitas Kinerja Instansi Pemerintah', '', 'Lembaga Administrasi Negara Republik Indonesia', '', '2003', '1' ),
( 'B349', 'MPPK-001', '', 'Modul Pelatihan / Penataran Katalogisasi Materiel Sistem NSN Pemberian Nama Materiel', '', 'Pusat Kodifikasi', '', '2004', '28' ),
( 'B350', 'PKBD-002', '', 'PERATURAN KEPEGAWAIAN YANG BERKAITAN DENGAN DISIPLIN PEGAWAI NEGERI SIPIL', '', 'Biro Kepegawaian Setjen Dephan', '', '2008', '1' ),
( 'B351', 'HKPN-002', '', 'HUKUM KEUANGAN & PERBENDAHARAAN NEGARA', 'Prof. Drs. C.S.T. Kansil, S.H. , Christine S.T. Kansil, S.H., M.H.', '', '', NULL, '1' ),
( 'B352', 'KPRI-001', '', 'Keputusan Presiden Republik Indonesia Nomor 80 Tahun 2003 Tentang Pedoman Pelaksanaan Pengadaan Barang/Jasa Pemerintah', '', 'BAPPENAS', '', '2003', '1' ),
( 'B353', 'MRHP-002', '', 'Menguak Realitas Hukum, Peryahanan & Hak Asasi Manusia', 'PROF. DR. ACHMAD ALI,S.H., M.H.', '', '', '2008', '3' ),
( 'B354', 'HPP-001', 'Himpunan Peraturan Perundang', 'undangan terkait Dengan Tata Cara Pembentukan Perfaturan Perundang - Undangan', '', 'Sekertariat jendereal Departemnen Pertahanan', '', '2007', '4' ),
( 'B355', 'HPDP-002', '', 'Hukum Pertahanan Dalam Praktek Pelaksanaan Tugas Pmerintahan', 'RUSMADIMURAD, S.H., M.H.', 'Sekertariat jendereal Departemnen Pertahanan', '', '2008', '4' ),
( 'B356', 'PMKR-001', '', 'Peraturan Menteri Keuangan RI Nomor : 64/PMK.02/2008 DAN 69/PMK.02/2008 Tentang Standar Biaya Umum Dan Khusus', '', 'CV. TAMITA UTAMA', '', '2009', '1' ),
( 'B357', 'PPRN-003', '', 'Peraturan Pemerintah RI Nomor 38 Tahun 2008 Tentang Pengelolaan Barang Milik Negara/Daerah', '', 'CV. TAMITA UTAMA', '', '2009', '1' ),
( 'B358', 'PPRN-004', '', 'Peraturan Presiden RI Nomor 95 Tahun 2007 KEPPRES NO. 80 Tentang Pedoman Pelaksanaan Pengadaan Barang / Jasa Pemerintahan', '', 'CV. TAMITA UTAMA', '', '2009', '2' ),
( 'B359', 'PPTP-002', '', 'Penyusunan Perjanjian (Teori dan Praktek)', 'Dr. Fran Hendra Winarta, S.H., M.H.', 'Departemen Pertahanan', '', '2008', '3' ),
( 'B360', 'HUUM-002', '', 'Himpunan UIndang - Undang Dan Makalah Tentang Hak Asasi Manusia (HAM)', '', 'Departemen Pertahanan', '', '2007', '7' ),
( 'B361', 'SMPR-001', '', 'Sambutan Menteri Pertahanan RI Pada Rapat Laporan Pertanggung Jawaban Keuangan Negara Di Lingkungan Dephan Dan TNI', '', '', '', '2008', '1' ),
( 'B362', 'APTR-001', '', 'Amanat Panglima TNI Pada Rapat Laporan Pertanggung Jawaban Keuangan Negara Di Lingkungan DEPHAN RI Dana TNI', '', '', '', '2008', '1' ),
( 'B363', 'NPSS-001', '', 'Naskah Penyempurnaan Sistem Skuntansi Instansi Melalui Pembanahan SAK Di Lingkungan DEPHAN dan TNI', '', '', '', '2008', '1' ),
( 'B364', 'BPTB-001', '', 'Bahan Paparan Titm BPK RI Pada Rapat Laporan PertangggunJawaban Keuangan Negara Di Lingkungtan DEPHAN Dan TNI', '', '', '', '2008', '1' ),
( 'B365', 'MNPA-001', '', 'Menteri Negara Pandayagunaan Aparatur Negara Republik Indonesia', '', '', '', '2007', '1' ),
( 'B366', 'MPC-001', '', 'Military Product Catalogue', '', '', '', '2002', '6' ),
( 'B367', 'RPKP-001', '', 'Rencana Pelaksanaan Kegiatan Pemasyarakatan Katalog D i Wilayah Jawa Tengah', '', '', '', '2000', '1' ),
( 'B368', 'IISK-002', '', 'Istilah - isitlah Pada Sistem Kodifikasi NATO', '', 'Pusat Kodifikasi', '', '2006', '1' ),
( 'B369', 'DKBM-001', '', 'DIKTAT Kursus Bintara Manajemen Kejuruan Pembekalan Katalogisasi', '', '', '', NULL, '1' ),
( 'B370', 'AJEI-002', '', 'Analisis Jabatan Eselon III, Eselon IV dan Uraian tugas Pekerjaan Eselon V Pusat Katalogisasi Departemen Pertahanan', '', '', '', '2009', '1' ),
( 'B371', 'DIMB-006', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor Jenis : Mazda 626', '', '', '', '1994', '1' ),
( 'B372', 'DIMB-007', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor Jenis : Truck Isuzu Diesel FSR 86-89', '', '', '', '1994', '1' ),
( 'B373', 'DIMB-008', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor', '', '', '', '1990', '1' ),
( 'B374', 'DIMB-009', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor Jenis : Isuzu Panther', '', '', '', '1994', '1' ),
( 'B375', 'DIMB-010', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor Jenis : Mazda 323', '', '', '', '1994', '1' ),
( 'B376', 'DIMB-011', '', 'Data Identifikasi Mteriel Bekal Komoditi : Ranmor', '', '', '', '1993', '1' ),
( 'B377', 'DIMB-012', '', 'Data Identifikasi Mteriel Bekal Komoditi : Munisi Khusus', '', '', '', '1995', '1' ),
( 'B378', 'DLIE-001', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 8', '', '', '', '1991', '1' ),
( 'B379', 'DLIE-002', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 9', '', '', '', '1991', '1' ),
( 'B380', 'DLIE-003', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 10', '', '', '', '1991', '1' ),
( 'B381', 'DLIE-004', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 11', '', '', '', '1991', '2' ),
( 'B382', 'DLIE-005', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 12', '', '', '', '1991', '2' ),
( 'B383', 'DLIE-006', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 25', '', '', '', '1992', '1' ),
( 'B384', 'DLIE-007', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 26', '', '', '', '1992', '1' ),
( 'B385', 'DLIE-008', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 28', '', '', '', '1992', '1' ),
( 'B386', 'DLIE-009', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 29', '', '', '', '1992', '1' ),
( 'B387', 'DLIE-010', '', 'Defense Language Institute English Language Center Lackland Air Force Base, Texas American Language Course Level II BOOK 30', '', '', '', '1992', '1' ),
( 'B388', 'MSKM-001', '', 'Manajemen Sdm Keuangan dan Materiil', 'Sri Hadiati WK, SH, MBA, Drs. H. Sukadarto, SH,MM', '', '', '2005', '1' ),
( 'B389', 'PPPD-001', '', 'Pengenalan Dan Pengukuran Potensi Diri', 'Dra. Hj. Wahyu Suprapti, MM, Ir. Hj. Sri Ratna, MM', '', '', '2004', '1' ),
( 'B390', 'PKTP-001', '', 'Pola Kerja Terpadu (PKT)', 'Drs. Pitoyo, MA, Drs. Djoenaedi Tamim', '', '', '2001', '1' ),
( 'B391', 'MTSL-001', '', 'Muatan Teknis Substantif Lembaga', 'Drs. Juni Pranoto, M.Pd, Dra. Emma Rahmawiati', '', '', '2001', '1' ),
( 'B392', 'TKPE-001', '', 'Teknik Komunikasi dan Presentasu yang Efektif', 'Dr. P.M. Marpaung, M.Sc, Ir. Brisma Renaldi', '', '', '2001', '1' ),
( 'B393', 'PJKK-001', '', 'Pembinaan Jiwa Korps dan Kode Etik PNS dan Himpunan Peraturan Tentang Kesekertariatan KORPRI', '', 'Dewan Pengurus Nasional KORPS Pegawai Republik Indonesia', '', '2008', '1' ),
( 'B394', 'PPPC-001', '', 'Petunjuk Pengoperasian Penyempurnaan CD-DIRS DDP dan Spesifikasi Teknis Puskod 2004', '', 'Departemen Pertahanaan', '', '2004', '1' ),
( 'B395', 'MPPP-001', '', 'Modal Pengolahan, Penataan dan Perawatan Arsip Audio Visual', '', 'Arsip Nasional Republik Indonesia', '', '2009', '1' ),
( 'B396', 'PUPI-001', '', 'Pedoman Umum Pembentukan Istilah', '', 'Pusat Bahasa Departemen Pendidikan Nasional', '', '2004', '1' ),
( 'B397', 'MMLI-001', '', 'Modul Manajemen Layanan Informasi', '', 'Pusat Bahasa Departemen Pendidikan Nasional', '', NULL, '1' ),
( 'B398', 'BTMS-001', '', 'Bahan Tayangan Materi Sosialisasi Putusan Majelis Permusyawaratan Rakyat Republik Indonesia', '', 'Sekertariat Jenderal MPR RI', '', '2006', '1' ),
( 'B399', 'BKO-001', '', 'Berguru Kepada Obama', 'Siffi Masdi', 'Forum Kita', '', '2009', '2' ),
( 'B400', 'PTKK-001', '', 'Potensi Tandan Kobong Kelapa Sawit Sebagai Sumber Selulosa dalam Pembuatan Nitroselulosa', 'Timbul Siahaan', 'Universitas Brawijaya Malang', '', '2009', '1' ),
( 'B401', 'KOFR-001', '', 'Keeping Our Forces Ready for War & Peace', '', 'Defense Logistics Agency', '', '1997', '1' ),
( 'B402', 'DAW-001', '', 'DLA "Around the World"', '', 'Defense Logistics Agency', '', NULL, '1' ),
( 'B403', 'MAF-001', '', 'MAFCA', '', 'Malaysian Armed Forces Cataloguing Authority', '', NULL, '1' ),
( 'B404', 'BOFG-001', '', 'Business Overview for Gun & Artillery System', '', 'Hanwha Corporation', '', NULL, '1' ),
( 'B405', 'PPPL-001', '', 'Penyerahan Perkara Pelanggaran Lalu Lintas dan Peraturan Panglima TNI tentang Petunjuk Pelaksanaan Penyelesaian Perkara Pelanggaran Lalu Lintas', '', 'Markas Besar TNI', '', '2011', '1' ),
( 'B406', 'E0-001', '', 'EuroBusiness 05', '', '', '', '2005', '2' ),
( 'B407', 'CSFA-001', '', 'Comprehensive Solutions for Anatomical Pathology Laboratories', '', 'Thermo Electron Corporation', '', NULL, '1' ),
( 'B408', 'BMS7-001', '', 'Buletin MAFCA seri 7/2009', '', 'Malaysian Armed Forces Cataloguing Authority', '', '2009', '1' ),
( 'B409', 'BKE3-003', '', 'Buletin Kodifikasi edisi 33', '', 'Pusat Kodifikasi', '', NULL, '1' ),
( 'B410', 'BMS1-001', '', 'Buletin MAFCA seri 10/2010', '', 'Malaysian Armed Forces Cataloguing Authority', '', '2010', '1' ),
( 'B411', 'MIW-001', '', 'Media Informasi WiRA', '', 'Kementerian Pertahanan', '', '2013', '1' ),
( 'B412', 'AHO-001', '', 'Advokasi Hukum dan Operasi', '', 'Kementerian Pertahanan', '', '2013', '1' ),
( 'B413', 'BK-003', '', 'Buletin Kodifiaksi', '', 'Kementerian Pertahanan', '', '2013', '1' ),
( 'B414', 'AHO-002', '', 'Advokasi Hukum dan Operasi', '', 'Kementerian Pertahanan', '', '2014', '1' ),
( 'B415', 'APMI-001', '', 'Analisis Pengaruh Mutu Iklan dan Mutu Pelayanan Terhadap Tingkat Kepuasan Nasabah Kredit Ritel pada PT. Bank Mandiri (Persero)', 'Suharsono', 'Universitas Pembangunan Nasional "Veteran" Jakarta', '', '2005', '1' ),
( 'B416', 'MIW-002', '', 'Media Informasi WiRA', '', 'Kementerian Pertahanan', '', '2019', '5' ),
( 'B417', 'AHO-003', '', 'Advokasi Hukum dan Operasi', '', 'Kementerian Pertahanan', '', '2018', '1' ),
( 'B418', 'AHO-004', '', 'Advokasi Hukum dan Operasi', '', 'Kementerian Pertahanan', '', '2019', '1' ),
( 'B419', 'PTYP-001', '', 'Petunjuk Telepon Yellow Pages Entertaiment & Lifestyle', '', 'MD Media', '', '2015', '1' ),
( 'B420', 'JHPT-001', '', 'Jurnal Hukum & Pembangunan Tahun ke 46 no 2', '', 'FH UI', '', '2016', '1' ),
( 'B421', 'STAP-001', '', 'Spesifikasi Teknik Aplikasi Pelatihan Kodifikasi Mobile tahun 2014', '', 'Pusat Kodifikasi', '', '2014', '1' ),
( 'B422', 'OMB1-001', '', 'Operations Management buku 1 edisi 9', 'Jay Heizer & Barry Render', 'Salemba Empat', '', '2012', '1' ),
( 'B423', 'APSP-001', '', 'Analisis Pengaruh Sifat Pelatihan, Sikap Individu dan dukungan sosial terhadap motivasi pelatihan', 'Ponco Sudiharto', 'Universitas Semarang', '', '2005', '1' ),
( 'B424', 'AHAP-001', '', 'Analisis Hubungan antara Pengembangan Karier dengan motovasi kerja pegawai negeri sipil direktorat jenderal SDM departemen pertahanan', 'Abdul Rachim Saeni', 'Universitas Pembangunan Nasional "Veteran" Jakarta', '', '2001', '1' ),
( 'B425', 'APKL-001', '', 'Analisis Pengarub Kompensasi dan Lingkungan Kerja terhadap Kinerja Aparat Pemerintah "Dinas Perindustrian dan Perdagangan Kabupaten Bali"', 'Iwan Trisno', 'Sekolah Tinggi Ilmu Ekonomi STIKUBANK Semarang', '', '2004', '1' ),
( 'B426', 'IKB2-001', '', 'Ikahan Kilas Balik 2012', '', 'Ikatan Alumni Pertahanan', '', '2012', '1' ),
( 'B427', 'JFKA-001', '', 'Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Menteri Negara Pendayagunaan Aparatur Negara RI', '', '2008', '2' ),
( 'B428', 'DCI-001', '', 'Defense Conseil International', '', 'DCI', '', '1972', '1' ),
( 'B429', 'TEM-001', '', 'Tempo', '', 'Tempo', '', '2012', '1' ),
( 'B430', 'MDE5-001', '', 'Majalah Defender Edisi 51', '', 'Majalah Defender', '', '2010', '1' ),
( 'B431', 'MIW-003', '', 'Media Informasi WiRA', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B432', 'PPIJ-001', '', 'Pedoma Pelaksanaan Inpassing Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kementerian Pertahanan', '', '2010', '1' ),
( 'B433', 'PTIR-001', '', 'Penyusunan Total Item Record tahun 2015', '', 'Kementerian Pertahanan', '', '2015', '1' ),
( 'B434', 'DDAP-001', '', 'Dasar-Dasar Administasi Publik', 'Drs. Sutopo, MPA, Drs. Adam Ibrahim Indrawijaya, MPA', '', '', '2001', '1' ),
( 'B435', 'KE-001', '', 'Kecerdasan Emosional', 'Drs. A. Winarno, M.Sc, Tri Saksono, SH, M.Pd', '', '', '2001', '1' ),
( 'B436', 'KAT-001', '', 'Kepemimpinan Di Alam Terbuka', 'Ir. Djidji Surjadi, M.Sc, Hartoto Hendradjaja, SH', '', '', '2001', '1' ),
( 'B437', 'OPP-001', '', 'Operasionalisasi Pelayanan Prima', 'Drs. Eko Supriyanto, M.Sc, DRA. Sri Sugiyanti', '', '', '2001', '1' ),
( 'B438', 'PITP-001', '', 'Pengelolaan Informasi dan Teknik Pelaporan', 'Drs. Endar Ma\'moeri, Drs. Soetrisno, M.Psi', '', '', '2001', '1' ),
( 'B439', 'IAST-001', '', 'Isu Aktual Sesuai Tema', 'Drs. Juni Pranoto, M.Pd , Dra. Emma Rahmawiati , Drs. Agung A. Mataram, MM', '', '', '2005', '1' ),
( 'B440', 'KIP-001', '', 'Konsep Indikator Pembangunan', 'Drs. Irawan Kadiman, MA', '', '', '2005', '1' ),
( 'B441', 'DDGG-001', '', 'Dasar-Dasar Good Governance', 'Drs. Idup Suhady, M.Si , Dra. Desi Fernanda, M.Soc.Sc', '', '', '2005', '1' ),
( 'B442', 'KKKK-001', '', 'Kertas Kerja Kelompok(KKK) dan Kertas Kerja Angkatan (KKA)', 'Drs. Soetrisno, M.Psi , SIndhu Setiatmoko, SE', '', '', '2004', '1' ),
( 'B443', 'KKPK-001', '', 'Kertas Kerja Perseorangan (KKP)', 'Drs.Suparman, SKM , Drs. Djoenaedi Tamim', '', '', '2004', '1' ),
( 'B444', 'KDMP-001', '', 'Katalog Diklat Manajemen Pertahanan TA. 2008', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B445', 'PAPM-001', '', 'Perubahan Atas Peraturan Menteri Pertahanan NOMOR: PER/01/M/VIII/2005 tentang Susunan Organisasi Dan Tata Kerja Departemen Pertahanan', '', 'Departemen Pertahanan', '', '2008', '2' ),
( 'B446', 'PPCD-002', '', 'Petunjuk Pengoprasian CD-DIRS & DDP Edisi 2002', '', 'Pusat Kodifikasi', '', '2002', '1' ),
( 'B447', 'PPPP-010', '', 'Pokok - Pokok Pembinaan Penyelenggaraan Sistem Informasi Pertahanan Negara', '', 'Departemen Pertahanan', '', '2004', '1' ),
( 'B448', 'OTKR-001', '', 'Organisasi dan Tata Kerja Rumah Sakit dr. Suyoto Departemen Pertahanan', '', 'Departemen Pertahanan', '', '2008', '2' ),
( 'B449', 'SKBK-001', '', 'Sitersis Dan Karakteristik Boron Karbida (B,C) Dari Asam Borat, Karbon, Dan Asam Sitrat, Sebagai Fasa Oenguat Komposit Material Tahan Peluru Alumina - Titania', '', 'Universitas Indonesia', '', '2009', '1' ),
( 'B450', 'SNKT-001', '', 'Selamatkan Negeri Kita Dari Terorisme', '', '', '', NULL, '1' ),
( 'B451', 'KAM-007', 'Kamus', 'Naskah Sementara Istilah ABRI Bahasa Indonesia - Inggris Tahun 1993', '', '', '', '1993', '1' ),
( 'B452', 'UURI-002', '', 'Undang-Undang Republik Indonesia Nomor 12 Tahun 2003 Tentang Pemilihan Umum Anggota Dewan Perwakilan Rkyat, Dewan Perwakilan Daerah, dan Dewan Perwakilan Rakyat Daerah', '', '', '', '2003', '1' ),
( 'B453', 'JJHD-001', '', 'Jenis- Jenis Hayati Yang Dilindungi Perundang-Undangan Indonesia', '', '', '', NULL, '1' ),
( 'B454', 'MCPW-001', '', 'Materi Ceramah Pemantapan Wawasan Kebangsaan Tahun 1997/1998', '', '', '', '1997', '1' ),
( 'B455', 'SIPI-001', '', 'Statistik Industri dan Perdagangan Industrial And Trade Statistics', '', '', '', '2004', '1' ),
( 'B456', 'KP-002', 'Kumpulan Perjanjian', 'Perjanjian Internasional Tentang Batas - Batas Teritorial Dan Sumber Alam Indonesia', 'Adi Sumardiman', '', '', '2007', '2' ),
( 'B457', 'RWKE-001', '', 'Reorientasi Wawasan Kebangsaan di Era Demokrasi', '', '', '', NULL, '2' ),
( 'B458', 'PPBN-001', '', 'Pendidikan Pendahuluan Bela Negara Tahap Lanjut', 'Drs. Saafroedin dkk.', 'Sekolah Tinggi Ilmu Ekonomi Perbanas Penerbit Intermedia Jakarta', '', NULL, '4' ),
( 'B459', 'KMPR-001', '', 'Ketetapan Majelis Permusyawaratan Rakyat Republik Indonesia Nomor I/MPR/2003 Tentanf Peninjauan Terhadap Materi Dan Statu Hukum KETETAPAN MAJELIS PERMUSYAWARATAN RAKYAT SEMENTARA DAN KETETAPAN MAJELIS PERMUSYAWARATAN RAKYAT REPUBLIK INDONESIA TAHUN 1960 SAMPAI DENGAN TAHUN 2002', '', 'Sekertariat Jenderal MPR RI', '', '2006', '1' ),
( 'B460', 'MSPM-001', '', 'Materi Sosialisasi Putusan Majelis Permusyawaratan Rakyat Republik Indonesia Ketetapan MPR RI dan Keputusan MPR RI', '', 'Sekertariat Jenderal MPR RI', '', '2006', '1' ),
( 'B461', 'PPUU-001', '', 'Panduan Pemasyarakatan UIndang - Undang Dasar Negara Republik Indonesia Tahun 1945 Sesuai Dengan Urutan Bab, Pasal, dan Ayat', '', 'Sekertariat Jenderal MPR RI', '', '2006', '1' ),
( 'B462', 'RMIB-001', '', 'Reformasi Menuju Indonesia Baru', '', 'Yayasan Swadaya Bhakti Mahasiswa', '', NULL, '1' ),
( 'B463', 'SKJS-002', '', 'Standar Kompetensi Jabatan Struktural Di Lingkungan Pusat Kodifikasi Departemen Pertahanan RI', '', 'Departemen Pertahanan', '', '2005', '1' ),
( 'B464', 'PUDD-001', '', 'Peraturan Urusan Dinas Dalam Pusat Kodifikasi', '', '', '', '2002', '1' ),
( 'B465', 'AJEI-003', '', 'Analisis Jabatan Eselon III, Eselon IV Dan Uraian Tugas Pekerjaan Eselon V Pusat KatalogisasiDepartemen Pertahanan', '', '', '', '2000', '5' ),
( 'B466', 'UJEI-002', '', 'Uraian Jabatan Eselon II, III Dan IV PUSKOD Dan Pusrehabcat Setjen Dephan', '', 'Biro Ortala Setjen Dephan', '', '2004', '3' ),
( 'B467', 'KPPK-002', '', 'Kurikulum Dan Pedoman Penyelenggaraan Kursus Administrasi Kepegawaian TK. III Dephan TA. 2002', '', '', '', '2002', '1' ),
( 'B468', 'PWPH-001', '', 'Pendelegasian Wewenang Penjatuhan Hukuman Disiplin Bagi Pegawai Negeri Sipil Di Departemen Pertahanan', '', 'Departemen Pertahanan', '', '2003', '1' ),
( 'B469', 'PTPP-002', '', 'Prosedur Tetap Pencegahan Dan Penanggulangan Bahaya Kebakaran Di Lingkungan DITJEN MATFASJASA DEPHAN', '', '', '', '2000', '1' ),
( 'B470', 'AAMP-001', '', 'Amanat Anggaran Menteri Pertahanan Tahun Anggaran 2001', '', 'Departemen Pertahanan', '', '2000', '1' ),
( 'B471', 'UJEI-003', '', 'Uraian Jabatan Eselon II, III Dan Tenaga Fungsional GOL IV PUSKOD Dan Pusrehabcat Setjen Dephan', '', 'Biro Perencanaan Setjen Dephan', '', '2006', '1' ),
( 'B472', 'OTEP-001', '', 'Organisasi dan Tugas Eselon Pembantu Pimpinan dan Eselon Pelayanan Komando Daerah Militer', '', '', '', '1985', '1' ),
( 'B473', 'PTJE-001', '', 'Pedoman TataKerja Jabatan Eselon V Pusat Katalogisasi Departemen Pertahanan', '', 'Departemen Pertahanan', '', '2000', '12' ),
( 'B474', 'PPPB-004', '', 'Pedoman Pelaksanaan Pengadaan Baran/Jasa Pemerinta', '', 'FokusMedia', '', NULL, '1' ),
( 'B475', 'DNAP-001', '', 'Daftar Nama Dan Alamat Pejabat Eselon I, II, III, Dan IV Kementriaan Pertahanan RI Tahun 2012', '', '', '', '2012', '2' ),
( 'B476', 'JFKA-002', '', 'Jabatan Fungsional Kataloger Dan Angka Kreditnya', '', '', '', '2008', '1' ),
( 'B477', 'PPIJ-002', '', 'Pedoman Pelaksanaan Inpassing Jabatan Fungsional Kataloger Dan Angka Kreditnya', '', '', '', '2010', '1' ),
( 'B478', 'KJBA-001', '', 'Konytrak Jual Beli antara Kementriaan Pertahanan Republik Indonesia Dengan PT DIRGANTARA INDONESIA', '', '', '', '2012', '1' ),
( 'B479', 'BSPK-002', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Laporan Hasil Validasi Transaksi ata LSA Dan LAU Antara NCB Triwulan', '', '', '', '2015', '1' ),
( 'B480', 'KPRB-001', '', 'Kementriaan Pertahanan RI Badan Sarana Pertahanan Lampiran LA Data Transaksi Pengiriman LSA Triwulan II TA. 2016', '', '', '', '2016', '1' ),
( 'B481', 'TPKM-001', '', 'tesis Pengaruh Kepemimpinan Dan Motivasi Terhadap Kinerja Pegawai Di Lingkungan Sekretariat Kabinet', 'ISLACHUDDIN', '', '', '2004', '1' ),
( 'B482', 'JHPT-002', '', 'Jurnal Hukum & Pembangunan Tahun ke 46 no . 3', '', '', '', '2016', '1' ),
( 'B483', 'IPT-001', '', 'IELTSS Practice Test', 'Vanessa Jakeman, Clare Mcdowell', '', '', NULL, '1' ),
( 'B484', 'MIHT-001', '', 'Modul in-house Training Web Server Teori & Praktek', '', '', '', NULL, '1' ),
( 'B485', 'POMM-001', '', 'Perilaku Organisasi Memahami, Mem[prediksi dan Mengelola Perilakju Manusia Dalam Organisasi', 'Paul Ohoiwutun', '', '', '2008', '1' ),
( 'B486', 'KKMP-015', '', 'Kumpulan Kuliah Manajemen Produksi Dan Operasi Modul 1 Pendahuluan', 'Prabowo Pudjo Widodo', '', '', '2002', '1' ),
( 'B487', 'DMP-001', '', 'Dasar Manajemen Pemasaran', 'Drs. Djumarno, MBA', '', '', NULL, '1' ),
( 'B488', 'DW-001', '', 'Dharma Wiratama', '', '', '', NULL, '1' ),
( 'B489', 'KPRB-002', '', 'Kementriaan Pertahanan RI Badan Sarana Pertahanan Publikasi Data Hasil Kodifikasi Nomor Sediaan Nasional (NSN)', '', '', '', '2015', '2' ),
( 'B490', 'PBMP-001', '', 'Peraturan Bersama Menteri Pertahanan Dan Kepala Badan Kepegawaian Negara Tentang Jabatan Fungsional Kataloger Dan Angka Kreditnya', '', '', '', '2008', '1' ),
( 'B491', 'PPPB-005', '', 'Publikasi Pabrikan Puskod Baranahan Kementriaan Pertahanan RI', '', '', '', NULL, '1' ),
( 'B492', 'PTNJ-002', '', 'Petunjuk Teknis NOMOR: JUKNIS/ 03 / VI / 2013 Tnetang Identifikasi Materil Kodifikasi Sistem Nomor Sediaan Nasional (NSN)', '', '', '', '2013', '1' ),
( 'B493', 'PTTC-002', '', 'Petunjuk Teknis Tata Cara Pemberian Nomor Kode Pabrik Dalam Ktalogiasai Mteriel Pertahanan Sistem NSN', '', '', '', '2004', '98' ),
( 'B494', 'PMPN-001', '', 'Peraturan Menteri Pertahanan Nomor 14 Tahun 2010 Tentang Pedoman Pelaksanaan Jabatan Fungsional Kataloger Dan Angka Kreditnya', '', '', '', '2010', '1' ),
( 'B495', 'OUKP-001', '', 'Oracle University Knowledge. Performance. Succes Introduction to oracle 9i: SQL Volume 3', '', '', '', NULL, '2' ),
( 'B496', 'OUKP-002', '', 'Oracle University Knowledge. Performance. Succes Introduction to oracle 9i: SQL Volume 1`', '', '', '', NULL, '3' ),
( 'B497', 'OUKP-003', '', 'Oracle University Knowledge. Performance. Succes Introduction to oracle 9i: SQL Volume 2', '', '', '', NULL, '4' ),
( 'B498', 'OUKP-004', '', 'Oracle University Knowledge. Performance. Succes oracle 9i DBA Fundamental Volume 1', '', '', '', NULL, '2' ),
( 'B499', 'PTNJ-003', '', 'Petunjuk Teknis Nomor : JUKNIS/111/VI/2014 Tentang Tata Cara On The Job Training (OJT) Kodifikasi Materiil Sistem NSN', '', '', '', '2014', '1' ),
( 'B500', 'PTNJ-004', '', 'Petunjuk Teknis Nomor : JUKNIS/445/XI/2014 Tentang Publikasi Kodifikasi Materiil Sistem NSN', '', '', '', '2014', '2' );

INSERT INTO `tb_buku`(`id_buku`,`kode_buku`,`seri_buku`,`judul_buku`,`pengarang`,`penerbit`,`rak`,`th_terbit`,`jumlah`) VALUES 
( 'B501', 'MSKM-002', '', 'Mengenal Sistem Kodifikasi Materiel Pertahanan', '', 'Pusat Kodifikasi Dephan', '', NULL, '4' ),
( 'B502', 'PB-001', '', 'PenyakitIkan Budidaya', '', '', '', '2011', '2' ),
( 'B503', 'PTCP-001', '', 'Pedoman Dan Tata Cara Pemotongan Hewan Secara Halal', '', '', '', '2007', '1' ),
( 'B504', 'BKSD-001', '', 'Bayarlah Kesalahan Di Sini, Di Dunia Ini', '', '', '', '2004', '1' ),
( 'B505', 'MKIA-001', '', 'Majalah Kisah Islami Alkisah', '', '', '', NULL, '1' ),
( 'B506', 'MGMG-001', '', 'Majalah Ghoib Mengimani Yang Ghoib Sesuai Syari\'at', '', '', '', '2006', '1' ),
( 'B507', 'PTPK-001', '', 'Petunjuk Teknis Pemberian Kodifikasi Sementara', '', 'Pusat Kodifikasi DEPHAN', '', '2003', '9' ),
( 'B508', 'PMKP-001', '', 'Peraturan Menteri Kelautan dan Perikanan Republik Indonesia Nomor per. 13/MEN/2011 Tentang Pedoman Penyusunan Program Penyulkuhan Perikanan', '', '', '', '2011', '2' ),
( 'B509', 'KPDH-001', '', 'Konsumen & Pasien Dalam Hukum Indonesia', 'Drs. M. Sofyan Lubis, SH & Muhammad Harry, SH.', 'LIBERTY YOGYAKARTA', '', '2008', '1' ),
( 'B510', 'DPEI-003', '', 'Daftar Pejabat ESELON III Dan IV Kementrian Pertahanan RI Tahun 2012', '', 'Biro Tata Usaha', '', '2010', '1' ),
( 'B511', 'PTPK-002', '', 'Petunjuk Teknis Pemberian Kodifikasi Sementara', '', 'Pusat Kodifikasi', '', '2002', '1' ),
( 'B512', 'PPLA-002', '', 'Pedoman Pneyusunan Laporan Akuntabilitas Kinerja Instansi Pemerintah', '', '', '', '2003', '1' ),
( 'B513', 'PPRI-003', '', 'Peraturan Pemerintah Republik Indonesia Nomor 8 Tahun 2008 Tentang Pelaporan Keuangan Dan Kinerja Instansi Pemerintah', '', 'Departemen Keuangan', '', '2006', '1' ),
( 'B514', 'KMPN-001', '', 'Keputusan Menteri Pertahanan Nomor : KEP / 19.a / M / XII / 2000 Tnetang Susunan Organisasui dan Tata Kerja Departemen pertahanan', '', 'Biro Ortala Setjen Dephan', '', '2002', '1' ),
( 'B515', 'PTNJ-005', '', 'Petunjuk Teknis NOMOR: juknis/520/xii/2014 Tnetang Tata Cara Asistensi Kodifikasi Materiil Sistem NSN', '', 'Kementriaan Pertahanan RI', '', '2014', '1' ),
( 'B516', 'MKMS-001', '', 'Mengenal Kodifikasi Materiil Sistem NSN', '', 'Pusat Kodifikasi Kemhan', '', NULL, '1' ),
( 'B517', 'BPSN-001', '', 'Buku Panduan Seminar Nasional Kodifikasi SIstem NSN', '', 'Pusat Kodifikasi', '', '2010', '1' ),
( 'B518', 'PKWM-001', '', 'Pragnya Karya Wiratama Majalah Resmi Ikatan Alumni Sekolah Staff Dan Komando Angkatan Udara', '', '', '', '2000', '1' ),
( 'B519', 'CPPS-001', '', 'Company Profile PT. Sritex', '', 'PT. Sritex', '', NULL, '1' ),
( 'B520', 'PCI-001', '', 'Producr Catalogue Infoglobal', '', 'Infoglobal', '', NULL, '1' ),
( 'B521', 'IST-001', '', 'Innovatice SImulation Technologies', '', 'PT. Innosimulation Technology Indonesia', '', NULL, '1' ),
( 'B522', 'PCI-002', '', 'Product Catalogue Infoglobal', '', 'PT. Sritex', '', NULL, '1' ),
( 'B523', 'IDEF-001', '', 'Indo Defense Expo & Forum 1', '', 'Indo Defense', '', '2018', '1' ),
( 'B524', 'IDEF-002', '', 'Indo Defense Expo & Forum 2', '', 'Indo Defense', '', '2018', '1' ),
( 'B525', 'PCPD-001', '', 'Product Catalogue PT. Dumas', '', 'PT. Dumas', '', NULL, '1' ),
( 'B526', 'KSNI-001', '', 'Katalog Standard Nasional Indonesia "Konstruksi dan Bangunan"', '', 'Badan Standardsasi Nasional', '', '2008', '1' ),
( 'B527', 'MTP-001', '', 'Majalah TNI "PATRIOT"', '', 'TNI', '', '2018', '1' ),
( 'B528', 'CPPH-001', '', 'Company Profila PT. Hariff Daya', '', '', '', NULL, '1' ),
( 'B529', 'PDP-001', '', 'Pindad Defense Products', '', 'PINDAD', '', NULL, '1' ),
( 'B530', 'MMGE-001', '', 'Majalah Mobile Guide edisi 64', '', 'Mobile Guide', '', '2012', '1' ),
( 'B531', 'PSKS-001', '', 'Product Specification Krakateu Steel', '', 'PT. Krakatau Steel', '', NULL, '1' ),
( 'B532', 'MD-002', '', 'Majalah Dislitbangsal', '', '', '', '1986', '2' ),
( 'B533', 'BME1-001', '', 'Buletin MAFCA Edisi 10', '', 'MAFCA', '', '2012', '1' ),
( 'B534', 'BKE3-004', '', 'Buletin Kodifikasi edisi 39', '', 'Pusat Kodifikasi', '', '2013', '1' ),
( 'B535', 'BKE4-001', '', 'Buletin Kodifikasi edisi 40', '', 'Pusat Kodifikasi', '', '2013', '2' ),
( 'B536', 'BKE3-005', '', 'Buletin Kodifikasi edisi 34', '', 'Pusat Kodifikasi', '', '2010', '8' ),
( 'B537', 'BKE3-006', '', 'Buletin Kodifikasi edisi 35', '', 'Pusat Kodifikasi', '', '2011', '1' ),
( 'B538', 'IFJS-001', '', 'Informasi Faktor Jabatan Struktural Pejabat Eselon IV Badan Sarana Pertahanan Kementerian Pertahanan', '', 'Kementerian Pertahanan', '', '2012', '1' ),
( 'B539', 'PAJP-001', '', 'Penetapan Analisis Jabatan Pejabat Struktural Eselon III Satuan Kerja/Subsatuan Kerja', '', 'Kementerian Pertahanan', '', '2013', '1' ),
( 'B540', 'HPP-002', 'Himpunan Peraturan Perundang', 'undangan Hukum Laut', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B541', 'PPJF-002', '', 'Pedoman Pelaksanaan Jabatan Fungsional Kataloger dan Angka Kreditnya', '', 'Kementerian Pertahanan', '', '2010', '15' ),
( 'B542', 'MAJ-001', '', 'Metode Analisis Jalur', 'Dr. Nidjo Sandjiji, M.Sc', 'Pustaka Sinar Harapan', '', '2011', '1' ),
( 'B543', 'C3V1-001', '', 'CH 3 Volume 10', '', 'Defense Logistics Agency', '', '1994', '2' ),
( 'B544', 'PBOK-001', '', 'Pengaruh Budaya Organisasi, Komitmen Organisasi dan Gaya Kepemimpinan Terhadap Kinerja Oragnisasi di Pusat Kodifikasi Depatemen Pertahanan', 'Resi Aditya, S.Kom', 'Universitas Pembangunan Nasional "Veteran" Jakarta', '', '2008', '1' ),
( 'B545', 'MPPA-001', '', 'Modul Pemeliharaan dan Perawatan Arsip Kertas', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B546', 'MDE6-001', '', 'Majalah Defender Edisi 62', '', 'Majalah Defender', '', '2012', '1' ),
( 'B547', 'MDE6-002', '', 'Majalah Defender Edisi 63 ]', '', 'Majalah Defender', '', '2012', '1' ),
( 'B548', 'SWAV-001', '', 'SATRIA Widyagocara Adimanggala Vol 8 N0 1', '', 'Kementerian Pertahanan', '', '2012', '1' ),
( 'B549', 'SWAV-002', '', 'SATRIA Widyagocara Adimanggala Vol 8 N0 4', '', 'Kementerian Pertahanan', '', '2012', '1' ),
( 'B550', 'PM-001', '', 'PC Media', '', 'Departemen Pertahanan', '', '2012', '1' ),
( 'B551', 'MIWV-001', '', 'Media Informasi WIRA Vol 19 No 5', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B552', 'MIWV-002', '', 'Media Informasi WIRA Vol 19 No 6', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B553', 'MIWV-003', '', 'Media Informasi WIRA Vol 20 No 1', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B554', 'MIWV-004', '', 'Media Informasi WIRA Vol 20 No 2', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B555', 'MIWV-005', '', 'Media Informasi WIRA Vol 20 No 3', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B556', 'SKN-001', '', 'Sistem Katalogisasi Nasional', '', 'Departemen Pertahanan', '', '2002', '2' ),
( 'B557', 'MSP-001', '', 'Modul Sistem Pemberkasan', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B558', 'MSK-001', '', 'Modul Sejarah Kearsipan', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B559', 'MPK-001', '', 'Modul Pengantar Kearsipan', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B560', 'MPAV-001', '', 'Modul Program Arsip Vital, Metode Pelindungan dan Pengamanan Arsip Vital', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B561', 'MMPF-001', '', 'Modul Manajemen Persuratan dan Formulir', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B562', 'PMNP-001', '', 'Peratuan Menteri Negara Pendayaan Aparatur Negara', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B563', 'MTND-002', '', 'Modul Tata Naskah Dinas', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B564', 'MMAI-001', '', 'Modul Manajemen Arsip Inaktif', '', 'Arsip Nasional RI', '', '2009', '1' ),
( 'B565', 'BB-001', '', 'Berkibarlah Benderaku', 'Bondan Winarno', 'TSA Komunika', '', '2002', '1' ),
( 'B566', 'TSAW-001', '', 'Ten Strategies of a World Class Cybersecurity Operations Center', 'Carson Zimmerman', 'The Mitre Coorporation', '', '2014', '1' ),
( 'B567', 'SADS-002', '', 'Superior Air Defense System sebagau Penegak Supermasi Hukum dan Kedaulatan di wilayah udara NKRI', '', 'Labda Prakasa Nirwikara', '', NULL, '1' ),
( 'B568', 'FIUN-001', '', 'Finalis Indonesia United Nation Public Service Award (UNPSA)', '', 'Kementerian Pendayagunaan Aparatur Negara', '', '2014', '1' ),
( 'B569', 'NKP-001', '', 'Newsletter Kementerian Pertahanan', '', 'Kementerian Pertahanan', '', NULL, '2' ),
( 'B570', 'BSPK-003', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran I.B Data Transaksi Pengiriman LSA TRIWULAN IV TA.2015', '', '', '', '2015', '10' ),
( 'B571', 'BSOK-001', '', 'Badan Sarana Oertahanan Kemhan Pusat Kodifikasi Validasi Data Katalog Materiil NSN NON 45 SEGMEN B Tahun 2013', '', '', '', '2013', '1' ),
( 'B572', 'BSPK-004', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Laporan Hasil Validasi Transaksi Data LSA dan LAU ANtara NCB TRIWULAN 1 TA.2015', '', '', '', '2015', '10' ),
( 'B573', 'BSPK-005', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran I.C Data Transaksi Penerimaan LSA TRIWULAN I TA.2015', '', '', '', '2015', '11' ),
( 'B574', 'BSPK-006', '', 'Badan Sarana Pertahanan KEMHAN Pjusat Kodifikasi SUB Lampiran l.A Source/Sumber Data Komoditi Transaksi LSA Triwulan i TA.2015', '', '', '', '2015', '5' ),
( 'B575', 'KPRB-003', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. A Data Transaksi Pengiriman LAU Triwulan IV TA. 2016', '', '', '', '2016', '1' ),
( 'B576', 'KPRB-004', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. A Data Transaksi Pengiriman LAU Triwulan III TA. 2016', '', 'Pusat Kodifikasi', '', '2016', '2' ),
( 'B577', 'KPRB-005', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. A Data Transaksi Pengiriman LAU Triwulan II TA. 2016', '', '', '', '2016', '2' ),
( 'B578', 'KPRB-006', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. A Data Transaksi Pengiriman LAU Triwulan I TA. 2016', '', '', '', '2016', '2' ),
( 'B579', 'KPRB-007', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LAU Triwulan I TA. 2016', '', '', '', '2016', '2' ),
( 'B580', 'KPRB-008', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LAU Triwulan II TA. 2016', '', '', '', '2016', '2' ),
( 'B581', 'KPRB-009', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LAU Triwulan III TA. 2016', '', '', '', '2016', '1' ),
( 'B582', 'KPRB-010', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LAU Triwulan IV TA. 2016', '', '', '', '2016', '1' ),
( 'B583', 'KPRB-011', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LSA Triwulan I TA. 2016', '', '', '', NULL, '2' ),
( 'B584', 'KPRB-012', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LSA Triwulan II TA. 2016', '', '', '', NULL, '2' ),
( 'B585', 'KPRB-013', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LSA Triwulan III TA. 2016', '', '', '', NULL, '2' ),
( 'B586', 'KPRB-014', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran II. B Data Transaksi Penerimaan LSA Triwulan IV TA. 2016', '', '', '', NULL, '1' ),
( 'B587', 'KPRB-015', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran I. A Data Transaksi Pengiriman LSA Triwulan I TA. 2016', '', '', '', NULL, '2' ),
( 'B588', 'KPRB-016', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran I. A Data Transaksi Pengiriman LSA Triwulan II TA. 2016', '', 'Pusat Kodifikasi', '', '2011', '1' ),
( 'B589', 'KPRB-017', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran I. A Data Transaksi Pengiriman LSA Triwulan III TA. 2016', '', '', '', NULL, '1' ),
( 'B590', 'KPRB-018', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran I. A Data Transaksi Pengiriman LSA Triwulan IV TA. 2016', '', '', '', NULL, '1' ),
( 'B591', 'LHVP-001', '', 'Laporan Hasil Validasi Pertukaran/Transaksi Data LSA dan LAU Triwulan I TA. 2016', '', '', '', NULL, '2' ),
( 'B592', 'LHVP-002', '', 'Laporan Hasil Validasi Pertukaran/Transaksi Data LSA dan LAU Triwulan II TA. 2016', '', '', '', NULL, '1' ),
( 'B593', 'LHVP-003', '', 'Laporan Hasil Validasi Pertukaran/Transaksi Data LSA dan LAU Triwulan III TA. 2016', '', '', '', NULL, '1' ),
( 'B594', 'LHVP-004', '', 'Laporan Hasil Validasi Pertukaran/Transaksi Data LSA dan LAU Triwulan IV TA. 2016', '', '', '', NULL, '1' ),
( 'B595', 'KPRB-019', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Lampiran I. B Data Transaksi Penerimaan LSA Triwulan IV TA. 2016', '', '', '', NULL, '1' ),
( 'B596', 'KPRB-020', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Sub Lampiran II. B Data Transaksi Penngiriman LAU Triwulan I TA. 2015', '', '', '', '2015', '3' ),
( 'B597', 'KPRB-021', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Sub Lampiran II. B Data Transaksi Penngiriman LAU Triwulan II TA. 2015', '', '', '', '2015', '3' ),
( 'B598', 'KPRB-022', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Sub Lampiran II. B Data Transaksi Penngiriman LAU Triwulan III TA. 2015', '', '', '', '2015', '3' ),
( 'B599', 'KPRB-023', '', 'Kementrian Pertahanan RI Badan Sarana Pertahanan Sub Lampiran II. B Data Transaksi Penngiriman LAU Triwulan IV TA. 2015', '', '', '', '2015', '3' ),
( 'B600', 'BSPK-007', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran II.A Source/Sumber Data Komoditi Transaksi LAU Triwulan IV TA.2015', '', '', '', NULL, '3' ),
( 'B601', 'BSPK-008', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran II.A Source/Sumber Data Komoditi Transaksi LAU Triwulan III TA.2015', '', '', '', NULL, '3' ),
( 'B602', 'BSPK-009', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran II.A Source/Sumber Data Komoditi Transaksi LAU Triwulan I TA.2016', '', '', '', NULL, '3' ),
( 'B603', 'BSPK-010', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran II.A Source/Sumber Data Komoditi Transaksi LAU Triwulan IV TA.2018', '', '', '', NULL, '1' ),
( 'B604', 'BSPK-011', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran II.C Data Transaksi Penerimaan LAU Triwulan I TA. 2015', '', '', '', NULL, '3' ),
( 'B605', 'BSPK-012', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran II.C Data Transaksi Penerimaan LAU Triwulan IV TA. 2016', '', '', '', NULL, '3' ),
( 'B606', 'BSPK-013', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran II.A Source/Sumber Data Komoditi Transaksi LAU Triwulan II TA.2016', '', '', '', NULL, '3' ),
( 'B607', 'BSPK-014', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran I.A Source/Sumber Data Komoditi Transaksi LSA Triwulan I TA.2015', '', '', '', '2015', '2' ),
( 'B608', 'BSPK-015', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran I.A Source/Sumber Data Komoditi Transaksi LSA Triwulan III TA.2016', '', '', '', NULL, '2' ),
( 'B609', 'BSPK-016', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran I.A Source/Sumber Data Komoditi Transaksi LSA Triwulan IV TA.2017', '', 'Pusat Kodifikasi', '', '2011', '1' ),
( 'B610', 'BSPK-017', '', 'Badan Sarana Pertahanan KEMHAN Pusat Kodifikasi Sub Lampiran I.B Data Transaksi Pengiriman LSA TRIWULAN II TA.2015', '', '', '', NULL, '1' ),
( 'B611', 'BSPK-018', '', 'Badan Sarana Pertahanan Kemhan Pusat Kodifikasi Sub Lampiran I.A Source/Sumber Data Komoditi Transaksi LSA Triwulan II TA.2015', '', '', '', NULL, '1' ),
( 'B612', 'MNPA-002', '', 'Menteri Negara Pendayagunaan Aparatur Negara Republik Indonesia Tentang Jabatan Funfsional Kataloger Dan Angka Kreditnya', '', '', '', '2008', '34' ),
( 'B613', 'PMPN-002', '', 'Peraturan Menteri Pertahanan Nomor 04 Tahun 2014 Tentang Pokok-Pokok Penyelenggaraan Kodifikasi Materiil Sistem Nomor Sediaan Nasional Di Lingkungan Kementriaan Pertahanan Dan Tentara Nasional Indonesia', '', 'Kementriaan Pertahanan', '', '2014', '5' ),
( 'B614', 'OTKD-001', '', 'Organisasi Dan Tata Kerja Departemen Pertahanan Keamanan', '', 'Departemen pertahanan', '', '1983', '1' ),
( 'B615', 'PBMP-002', '', 'Peraturan Bersama Menteri Pertahanan Dan Kepala Badan Kepegawaian Negara Taentang Jabatan Fungsional Kataloger Dan Angka Kreditnya', '', 'Departemen Pertahanan', '', '2008', '1' ),
( 'B616', 'PPKM-001', '', 'Pokok Penyelenggaraan Kodifikasi Materiil sistem Nomor Sediaan Nasional Di Lingkungan Tentara Nasional Indonesia', '', '', '', '2016', '4' ),
( 'B617', 'PBMP-003', '', 'Peraturan Bersama Menteri Pertahanan Dan Kepala Badan Kepegawaian Negara Tentang Jabatan FungsionalKataloger Dan Angka Kreditnya', '', 'Departemen Pertahanan', '', '2008', '1' );
COMMIT;
-- ---------------------------------------------------------


-- Dump data of "tb_ebook" ---------------------------------
BEGIN;

INSERT INTO `tb_ebook`(`id_ebook`,`id_buku`,`kode_ebook`,`judul_ebook`,`penulis`,`penerbit`,`tahun_terbit`,`kategori`,`deskripsi`,`sumber_file`,`nama_file_asli`,`nama_file_simpan`,`file_path`,`file_url`,`ukuran_file`,`ukuran_label`,`ekstensi_file`,`status_aktif`,`created_at`,`updated_at`) VALUES 
( 'E001', NULL, 'EBK-20260806-001', 'Komposisi Fungsi', 'Anton', 'Gramedia', '2026', '-', '', 'url', '', '', '', 'https://online.flipbuilder.com/kwcop/xltn/', '0', '-', 'pdf', '1', '2026-08-06 10:18:29', '2026-08-06 10:38:33' ),
( 'E002', NULL, 'EBK-20260806-002', 'Islamic', '', '', NULL, '', 'Ebook islamic', 'upload', 'Ethics_of_Islam.pdf', 'ebook_20260806_112356_8d5e1d0e5a.pdf', 'uploads/ebooks/ebook_20260806_112356_8d5e1d0e5a.pdf', '', '590450', '576,61 KB', 'pdf', '1', '2026-08-06 11:23:56', '2026-08-06 11:26:01' );
COMMIT;
-- ---------------------------------------------------------


-- Dump data of "tb_pengguna" ------------------------------
BEGIN;

INSERT INTO `tb_pengguna`(`id_pengguna`,`nama_pengguna`,`username`,`password`,`level`) VALUES 
( '1', 'Puskod Baloghan Kemhan', 'admin', '202cb962ac59075b964b07152d234b70', 'Administrator' ),
( '5', 'User', 'user', '123', 'Administrator' );
COMMIT;
-- ---------------------------------------------------------


-- Dump data of "tb_sirkulasi" -----------------------------
-- ---------------------------------------------------------


-- CREATE INDEX "id_anggota" -----------------------------------
CREATE INDEX `id_anggota` USING BTREE ON `log_pinjam`( `id_anggota` );
-- -------------------------------------------------------------


-- CREATE INDEX "id_buku" --------------------------------------
CREATE INDEX `id_buku` USING BTREE ON `log_pinjam`( `id_buku` );
-- -------------------------------------------------------------


-- CREATE INDEX "id_buku" --------------------------------------
CREATE INDEX `id_buku` USING BTREE ON `tb_ebook`( `id_buku` );
-- -------------------------------------------------------------


-- CREATE INDEX "judul_ebook" ----------------------------------
CREATE INDEX `judul_ebook` USING BTREE ON `tb_ebook`( `judul_ebook` );
-- -------------------------------------------------------------


-- CREATE INDEX "kategori" -------------------------------------
CREATE INDEX `kategori` USING BTREE ON `tb_ebook`( `kategori` );
-- -------------------------------------------------------------


-- CREATE INDEX "id_anggota" -----------------------------------
CREATE INDEX `id_anggota` USING BTREE ON `tb_sirkulasi`( `id_anggota` );
-- -------------------------------------------------------------


-- CREATE INDEX "id_buku" --------------------------------------
CREATE INDEX `id_buku` USING BTREE ON `tb_sirkulasi`( `id_buku` );
-- -------------------------------------------------------------


-- CREATE LINK "log_pinjam_ibfk_1" -----------------------------
ALTER TABLE `log_pinjam`
	ADD CONSTRAINT `log_pinjam_ibfk_1` FOREIGN KEY ( `id_anggota` )
	REFERENCES `tb_anggota`( `id_anggota` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK "log_pinjam_ibfk_2" -----------------------------
ALTER TABLE `log_pinjam`
	ADD CONSTRAINT `log_pinjam_ibfk_2` FOREIGN KEY ( `id_buku` )
	REFERENCES `tb_buku`( `id_buku` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK "tb_ebook_ibfk_1" -------------------------------
ALTER TABLE `tb_ebook`
	ADD CONSTRAINT `tb_ebook_ibfk_1` FOREIGN KEY ( `id_buku` )
	REFERENCES `tb_buku`( `id_buku` )
	ON DELETE Set NULL
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK "tb_sirkulasi_ibfk_1" ---------------------------
ALTER TABLE `tb_sirkulasi`
	ADD CONSTRAINT `tb_sirkulasi_ibfk_1` FOREIGN KEY ( `id_buku` )
	REFERENCES `tb_buku`( `id_buku` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


-- CREATE LINK "tb_sirkulasi_ibfk_2" ---------------------------
ALTER TABLE `tb_sirkulasi`
	ADD CONSTRAINT `tb_sirkulasi_ibfk_2` FOREIGN KEY ( `id_anggota` )
	REFERENCES `tb_anggota`( `id_anggota` )
	ON DELETE Cascade
	ON UPDATE Cascade;
-- -------------------------------------------------------------


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- ---------------------------------------------------------


