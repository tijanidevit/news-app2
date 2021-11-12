-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Nov 2021 pada 10.32
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'admin@news.com', '39d5b40ca6272059833743b327220089', '2021-10-20 05:50:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Project', '2021-10-20 11:49:42', '2021-10-20 11:49:42'),
(2, 'Seminar', '2021-10-20 11:49:42', '2021-10-20 11:49:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `gists`
--

CREATE TABLE `gists` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(150) NOT NULL,
  `status` int(11) DEFAULT 1,
  `views` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `gists`
--

INSERT INTO `gists` (`id`, `student_id`, `category_id`, `title`, `content`, `featured_image`, `status`, `views`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'When is NACOS Day?', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\n            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\n            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\n            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\n            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\n            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'img.png', 1, 8, '2021-10-22 09:13:18', '2021-11-02 18:51:36'),
(2, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 1, 4, '2021-11-02 18:17:55', '2021-11-12 09:14:10'),
(3, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:19:56', '2021-11-02 18:19:56'),
(4, 1, 1, 'Result Will Be Out Soon', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '', 0, 0, '2021-11-02 18:20:58', '2021-11-02 18:20:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gist_comments`
--

CREATE TABLE `gist_comments` (
  `id` int(11) DEFAULT NULL,
  `gist_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `gist_comments`
--

INSERT INTO `gist_comments` (`id`, `gist_id`, `student_id`, `comment`, `created_at`) VALUES
(NULL, 1, 1, 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '2021-11-02 18:45:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gist_likes`
--

CREATE TABLE `gist_likes` (
  `id` int(11) NOT NULL,
  `gist_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `levels`
--

CREATE TABLE `levels` (
  `id` int(11) NOT NULL,
  `level` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `levels`
--

INSERT INTO `levels` (`id`, `level`) VALUES
(1, 'ND1'),
(2, 'ND2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pfas`
--

CREATE TABLE `pfas` (
  `id` int(11) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pfas`
--

INSERT INTO `pfas` (`id`, `fullname`, `email`, `password`, `created_at`) VALUES
(1, 'Lawal Ayoade', 'ayoade@pfa.com', '8fa147cab7a5fb603d52c8114973989b', '2021-11-03 01:15:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `featured_image` varchar(120) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`id`, `category_id`, `title`, `content`, `featured_image`, `views`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'A Simple Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2003-Davido.jpg', 2, 1, '2021-10-20 06:53:01', '2021-11-02 17:35:47'),
(2, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '8601-Davido.jpg', 0, 1, '2021-10-20 06:56:53', '2021-10-20 12:08:28'),
(3, 2, 'Davido Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2003-Davido.jpg', 0, 1, '2021-10-20 06:57:08', '2021-10-20 12:08:24'),
(4, 1, 'Another Test', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '8854-fpi_logo.png', 0, 1, '2021-10-20 06:58:23', '2021-10-20 12:08:30'),
(5, 1, 'Hello World', 'Hello World Content!', '8431-Davido.jpg', 0, 1, '2021-10-20 12:04:09', '2021-10-20 12:04:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `post_comments`
--

INSERT INTO `post_comments` (`id`, `post_id`, `sender`, `comment`, `created_at`) VALUES
(1, 1, '1', 'Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon Result Will Be Out Soon ', '2021-11-12 08:53:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `matric_no` varchar(20) NOT NULL,
  `level_id` int(11) NOT NULL,
  `fullname` varchar(120) NOT NULL,
  `email` varchar(120) NOT NULL,
  `image` varchar(120) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `password` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `matric_no`, `level_id`, `fullname`, `email`, `image`, `gender`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'N/CS/17/0900', 1, 'Abara Chima Odiri', 'odiri@gmail.com', 'od.png', 'M', '3b1f8519c8d10b83f4680968b7f0d103', 1, '2021-10-22 08:19:22', '2021-10-22 08:19:22'),
(2, '', 1, 'Ayomide Aniyah', 'fine@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:29:17', '2021-11-02 16:29:17'),
(3, '', 1, 'Ayomide Aniyah', 'bad@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:30:12', '2021-11-02 16:30:12'),
(4, 'H/CS/19/0911', 1, 'Ayomide Aniyah', 'you@me.com', '', 'F', 'MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09', 1, '2021-11-02 16:32:52', '2021-11-02 16:32:52');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gists`
--
ALTER TABLE `gists`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `gist_likes`
--
ALTER TABLE `gist_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pfas`
--
ALTER TABLE `pfas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `gists`
--
ALTER TABLE `gists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `gist_likes`
--
ALTER TABLE `gist_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pfas`
--
ALTER TABLE `pfas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
