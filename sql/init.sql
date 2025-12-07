-- Database: `soccermatch`
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ==========================================
-- 1. STRUKTUR TABEL
-- ==========================================

CREATE TABLE `tim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tim` varchar(100) NOT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `stadion` varchar(100) DEFAULT NULL,
  `liga` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT 'default_team.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pemain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tim` int(11) NOT NULL,
  `nama_pemain` varchar(100) NOT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_tim` (`id_tim`),
  CONSTRAINT `pemain_ibfk_1` FOREIGN KEY (`id_tim`) REFERENCES `tim` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL,
  `home` int(11) NOT NULL,
  `away` int(11) NOT NULL,
  `venue` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `home` (`home`),
  KEY `away` (`away`),
  CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`home`) REFERENCES `tim` (`id`),
  CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`away`) REFERENCES `tim` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pertandingan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `home` int(11) DEFAULT NULL,
  `away` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `venue` varchar(100) DEFAULT NULL,
  `skor_a` int(11) DEFAULT '0',
  `skor_b` int(11) DEFAULT '0',
  `status` enum('belum','berlangsung','selesai') DEFAULT 'belum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `klasemen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tim` int(11) NOT NULL,
  `main` int(11) DEFAULT '0',
  `menang` int(11) DEFAULT '0',
  `seri` int(11) DEFAULT '0',
  `kalah` int(11) DEFAULT '0',
  `gm` int(11) DEFAULT '0',
  `gk` int(11) DEFAULT '0',
  `poin` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_tim` (`id_tim`),
  CONSTRAINT `klasemen_ibfk_1` FOREIGN KEY (`id_tim`) REFERENCES `tim` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 2. SEEDING DATA TIM (5 LIGA TOP EROPA)
-- ==========================================

-- PREMIER LEAGUE (Inggris)
INSERT INTO `tim` (`id`, `nama_tim`, `kota`, `stadion`, `liga`, `logo`) VALUES
(1, 'Arsenal', 'London', 'Emirates Stadium', 'Premier League', 'arsenal.png'),
(2, 'Aston Villa', 'Birmingham', 'Villa Park', 'Premier League', 'avilla.png'),
(3, 'Bournemouth', 'Bournemouth', 'Vitality Stadium', 'Premier League', 'bournemouth.png'),
(4, 'Brentford', 'London', 'Gtech Community Stadium', 'Premier League', 'brentford.png'),
(5, 'Brighton & Hove Albion', 'Brighton', 'Amex Stadium', 'Premier League', 'brighton.png'),
(6, 'Chelsea', 'London', 'Stamford Bridge', 'Premier League', 'chelsea.png'),
(7, 'Crystal Palace', 'London', 'Selhurst Park', 'Premier League', 'cpalace.png'),
(8, 'Everton', 'Liverpool', 'Goodison Park', 'Premier League', 'everton.png'),
(9, 'Fulham', 'London', 'Craven Cottage', 'Premier League', 'fulham.png'),
(10, 'Ipswich Town', 'Ipswich', 'Portman Road', 'Premier League', 'ipswich.png'),
(11, 'Leicester City', 'Leicester', 'King Power Stadium', 'Premier League', 'leicester.png'),
(12, 'Liverpool', 'Liverpool', 'Anfield', 'Premier League', 'liverpool.png'),
(13, 'Manchester City', 'Manchester', 'Etihad Stadium', 'Premier League', 'mancity.png'),
(14, 'Manchester United', 'Manchester', 'Old Trafford', 'Premier League', 'manutd.png'),
(15, 'Newcastle United', 'Newcastle', 'St James Park', 'Premier League', 'newcastle.png'),
(16, 'Nottingham Forest', 'Nottingham', 'City Ground', 'Premier League', 'nottingham.png'),
(17, 'Southampton', 'Southampton', 'St Marys Stadium', 'Premier League', 'southampton.png'),
(18, 'Tottenham Hotspur', 'London', 'Tottenham Hotspur Stadium', 'Premier League', 'spurs.png'),
(19, 'West Ham United', 'London', 'London Stadium', 'Premier League', 'westham.png'),
(20, 'Wolverhampton Wanderers', 'Wolverhampton', 'Molineux Stadium', 'Premier League', 'wolves.png');

-- LALIGA (Spanyol)
INSERT INTO `tim` (`id`, `nama_tim`, `kota`, `stadion`, `liga`, `logo`) VALUES
(21, 'Real Madrid', 'Madrid', 'Santiago Bernabéu', 'LaLiga', 'madrid.png'),
(22, 'FC Barcelona', 'Barcelona', 'Spotify Camp Nou', 'LaLiga', 'barca.png'),
(23, 'Atletico Madrid', 'Madrid', 'Civitas Metropolitano', 'LaLiga', 'atletico.png'),
(24, 'Sevilla', 'Seville', 'Ramón Sánchez Pizjuán', 'LaLiga', 'sevilla.png'),
(25, 'Real Sociedad', 'San Sebastián', 'Reale Arena', 'LaLiga', 'sociedad.png'),
(26, 'Real Betis', 'Seville', 'Benito Villamarín', 'LaLiga', 'betis.png'),
(27, 'Villarreal', 'Villarreal', 'Estadio de la Cerámica', 'LaLiga', 'villarreal.png'),
(28, 'Athletic Club', 'Bilbao', 'San Mamés', 'LaLiga', 'bilbao.png'),
(29, 'Valencia', 'Valencia', 'Mestalla', 'LaLiga', 'valencia.png'),
(30, 'Girona', 'Girona', 'Montilivi', 'LaLiga', 'girona.png');

-- SERIE A (Italia)
INSERT INTO `tim` (`id`, `nama_tim`, `kota`, `stadion`, `liga`, `logo`) VALUES
(41, 'Inter Milan', 'Milan', 'San Siro', 'Serie A', 'inter.png'),
(42, 'AC Milan', 'Milan', 'San Siro', 'Serie A', 'milan.png'),
(43, 'Juventus', 'Turin', 'Allianz Stadium', 'Serie A', 'juve.png'),
(44, 'Napoli', 'Naples', 'Stadio Diego Armando Maradona', 'Serie A', 'napoli.png'),
(45, 'AS Roma', 'Rome', 'Stadio Olimpico', 'Serie A', 'roma.png'),
(46, 'Lazio', 'Rome', 'Stadio Olimpico', 'Serie A', 'lazio.png'),
(47, 'Atalanta', 'Bergamo', 'Gewiss Stadium', 'Serie A', 'atalanta.png'),
(48, 'Fiorentina', 'Florence', 'Stadio Artemio Franchi', 'Serie A', 'fiorentina.png');

-- BUNDESLIGA (Jerman)
INSERT INTO `tim` (`id`, `nama_tim`, `kota`, `stadion`, `liga`, `logo`) VALUES
(61, 'Bayer Leverkusen', 'Leverkusen', 'BayArena', 'Bundesliga', 'leverkusen.png'),
(62, 'Bayern Munich', 'Munich', 'Allianz Arena', 'Bundesliga', 'bayern.png'),
(63, 'Borussia Dortmund', 'Dortmund', 'Signal Iduna Park', 'Bundesliga', 'dortmund.png'),
(64, 'RB Leipzig', 'Leipzig', 'Red Bull Arena', 'Bundesliga', 'leipzig.png'),
(65, 'VfB Stuttgart', 'Stuttgart', 'MHPArena', 'Bundesliga', 'stuttgart.png');

-- LIGUE 1 (Prancis)
INSERT INTO `tim` (`id`, `nama_tim`, `kota`, `stadion`, `liga`, `logo`) VALUES
(81, 'Paris Saint-Germain', 'Paris', 'Parc des Princes', 'Ligue 1', 'psg.png'),
(82, 'AS Monaco', 'Monaco', 'Stade Louis II', 'Ligue 1', 'monaco.png'),
(83, 'Olympique Marseille', 'Marseille', 'Stade Vélodrome', 'Ligue 1', 'marseille.png'),
(84, 'Lille OSC', 'Lille', 'Stade Pierre-Mauroy', 'Ligue 1', 'lille.png'),
(85, 'Lyon', 'Lyon', 'Groupama Stadium', 'Ligue 1', 'lyon.png');

-- ==========================================
-- 3. SEEDING DATA PEMAIN (Key Players)
-- ==========================================

INSERT INTO `pemain` (`id_tim`, `nama_pemain`, `posisi`) VALUES
-- Man City (13)
(13, 'Erling Haaland', 'Striker'), (13, 'Kevin De Bruyne', 'Midfielder'), (13, 'Rodri', 'Midfielder'), (13, 'Phil Foden', 'Winger'), (13, 'Ruben Dias', 'Defender'),
-- Arsenal (1)
(1, 'Bukayo Saka', 'Winger'), (1, 'Martin Odegaard', 'Midfielder'), (1, 'Declan Rice', 'Midfielder'), (1, 'William Saliba', 'Defender'), (1, 'Gabriel Jesus', 'Striker'),
-- Liverpool (12)
(12, 'Mohamed Salah', 'Winger'), (12, 'Virgil van Dijk', 'Defender'), (12, 'Trent Alexander-Arnold', 'Defender'), (12, 'Alisson Becker', 'Goalkeeper'), (12, 'Alexis Mac Allister', 'Midfielder'),
-- Man Utd (14)
(14, 'Bruno Fernandes', 'Midfielder'), (14, 'Marcus Rashford', 'Winger'), (14, 'Kobbie Mainoo', 'Midfielder'), (14, 'Lisandro Martinez', 'Defender'), (14, 'Andre Onana', 'Goalkeeper'),
-- Real Madrid (21)
(21, 'Kylian Mbappe', 'Striker'), (21, 'Vinicius Junior', 'Winger'), (21, 'Jude Bellingham', 'Midfielder'), (21, 'Luka Modric', 'Midfielder'), (21, 'Thibaut Courtois', 'Goalkeeper'),
-- Barcelona (22)
(22, 'Lamine Yamal', 'Winger'), (22, 'Robert Lewandowski', 'Striker'), (22, 'Pedri', 'Midfielder'), (22, 'Gavi', 'Midfielder'), (22, 'Frenkie de Jong', 'Midfielder'),
-- Inter Milan (41)
(41, 'Lautaro Martinez', 'Striker'), (41, 'Nicolo Barella', 'Midfielder'), (41, 'Hakan Calhanoglu', 'Midfielder'),
-- AC Milan (42)
(42, 'Rafael Leao', 'Winger'), (42, 'Christian Pulisic', 'Winger'), (42, 'Theo Hernandez', 'Defender'),
-- Juventus (43)
(43, 'Dusan Vlahovic', 'Striker'), (43, 'Bremer', 'Defender'), (43, 'Kenan Yildiz', 'Forward'),
-- Bayern (62)
(62, 'Harry Kane', 'Striker'), (62, 'Jamal Musiala', 'Midfielder'), (62, 'Manuel Neuer', 'Goalkeeper'),
-- Leverkusen (61)
(61, 'Florian Wirtz', 'Midfielder'), (61, 'Granit Xhaka', 'Midfielder'), (61, 'Jeremie Frimpong', 'Defender'),
-- PSG (81)
(81, 'Ousmane Dembele', 'Winger'), (81, 'Achraf Hakimi', 'Defender'), (81, 'Marquinhos', 'Defender');

-- ==========================================
-- 4. SEEDING JADWAL DUMMY
-- ==========================================
INSERT INTO `jadwal` (`tanggal`, `home`, `away`, `venue`) VALUES
('2025-12-20 20:00:00', 13, 1, 'Etihad Stadium'),      -- City vs Arsenal
('2025-12-21 20:00:00', 14, 12, 'Old Trafford'),        -- MU vs Liverpool
('2025-12-22 21:00:00', 21, 22, 'Santiago Bernabéu'),   -- Madrid vs Barca
('2025-12-23 20:45:00', 41, 42, 'San Siro'),            -- Derby Milan
('2025-12-24 19:00:00', 62, 63, 'Allianz Arena');       -- Bayern vs Dortmund

-- ==========================================
-- 5. SEEDING USERS & KLASEMEN INIT
-- ==========================================

-- Admin & User (Password: userpassword / adminpassword) - sesuaikan hash di aplikasi nanti
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'admin'),
('user', '$2y$10$YourHashedPasswordHere', 'user');

-- Inisialisasi Klasemen untuk SEMUA tim yang ada di tabel tim
INSERT INTO `klasemen` (`id_tim`, `main`, `menang`, `seri`, `kalah`, `gm`, `gk`, `poin`)
SELECT id, 0, 0, 0, 0, 0, 0, 0 FROM tim;

COMMIT;
