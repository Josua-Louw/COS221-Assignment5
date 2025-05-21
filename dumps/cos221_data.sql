-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 10:43 AM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cos221_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `user_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `launch_date` date DEFAULT NULL,
  `product_link` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `title`, `thumbnail`, `launch_date`, `product_link`, `price`, `description`, `category`, `store_id`, `brand_id`) VALUES
(2, 'To-Daisies A6 Notepad', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3061ba65dc1453a1eefe635b3d87a1df74e.webp', '2025-05-20', 'https://www.google.com/shopping/product/16577154852900363281?gl=za', 55.00, NULL, NULL, 4, NULL),
(3, 'Staedtler Small Stationery Kit', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3067cea55d352795120a183ad30d6522bbb.webp', '2025-05-20', 'https://www.google.com/shopping/product/4520565260507008483?gl=za', 59.99, NULL, NULL, 1, NULL),
(4, 'Butterfly Designer Paper Pad', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3064a8089ef27668a170f78cc02b0e74478.webp', '2025-05-20', 'https://www.google.com/shopping/product/4396065576255016771?gl=za', 60.00, NULL, NULL, 1, NULL),
(5, 'Stationery Essentials Premium Mystery Box', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306b5bcc998321af599e3614de2757a9d32.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10573723084953798893', 700.00, NULL, NULL, 5, NULL),
(6, 'Geoffrey Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306875bd2d96785da624f7988ef08dfbd21.webp', '2025-05-20', 'https://www.google.com/shopping/product/6701479725055872028?gl=za', 89.90, NULL, NULL, 2, NULL),
(7, 'Floral Rustic Wood Stationery Paper Cute Letter Writing Paper for Home', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306bdaf34360554b5347332e37aa5a4eb29.webp', '2025-05-20', 'https://www.google.com/shopping/product/17073104132709988103?gl=za', 399.00, NULL, NULL, 3, NULL),
(8, 'Atlas Bear A5 Notepads Giraffe NP', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30643a9ec37546cdd18ec2d36320222ebe5.webp', '2025-05-20', 'https://www.google.com/shopping/product/17927754399778684734?gl=za', 49.00, NULL, NULL, 4, NULL),
(9, 'Okiyo Kinben Sustainable Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306a2ff38959d1cd818a247dfe8fa435031.webp', '2025-05-20', 'https://www.google.com/shopping/product/11514920409282653866?gl=za', 38.55, NULL, NULL, 8, NULL),
(10, 'Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306dbbee3e68b67d9a0a0aa526e825732c8.webp', '2025-05-20', 'https://www.google.com/shopping/product/18227012151234946958?gl=za', 69.99, NULL, NULL, 1, NULL),
(11, 'Blue Clouds Stationery Paper Aesthetic Letter Writing Paper for Home', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3066b5ef7bac8a86251fe9151425ae5bd20.webp', '2025-05-20', 'https://www.google.com/shopping/product/4644063450758979491?gl=za', 399.00, NULL, NULL, 3, NULL),
(12, 'Field Notes Expedition', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306e6e233b20337855db7af0ace3d4a45ba.webp', '2025-05-20', 'https://www.google.com/shopping/product/134377391279113764?gl=za', 272.30, NULL, NULL, 11, NULL),
(13, 'La Dolce Vita Jam Packed Stationery Box', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306688d13a105cb33613eae34d59e403cde.webp', '2025-05-20', 'https://www.google.com/shopping/product/17970168067865123053?gl=za', 607.50, NULL, NULL, 4, NULL),
(14, 'Stationery Set Kb-608 Pink', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306e7ebb47fa9124d70f578883860b111b4.webp', '2025-05-20', 'https://www.google.com/shopping/product/11167506482101792677?gl=za', 8.00, NULL, NULL, 9, NULL),
(15, 'DOMS Water Coulours Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30689d631b0b4af90a38dd80a3036a46eee.webp', '2025-05-20', 'https://www.google.com/shopping/product/9378541808124621475?gl=za', 139.00, NULL, NULL, 7, NULL),
(16, 'Leather Stationary Combo', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306cc0dbe978f69996081e9ea75b2f640fd.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12191753065119491611', 990.00, NULL, NULL, 3, NULL),
(17, 'TOG Back to School Stationery Pack', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306d6f8550648158aa3cd52449f7c8924f4.webp', '2025-05-20', 'https://www.google.com/shopping/product/16588391824677952144?gl=za', 275.57, NULL, NULL, 10, NULL),
(18, 'Furbish Stationery Set of 12 Bold Floral', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306b861e69139666bb15ec746db177f2fef.webp', '2025-05-20', 'https://www.google.com/shopping/product/4501821251846457949?gl=za', 364.10, NULL, NULL, 14, NULL),
(19, 'Maps Letter Wrting Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30679583fc8becc7af965410b180e3fb3c1.webp', '2025-05-20', 'https://www.google.com/shopping/product/8415966100578890430?gl=za', 538.03, NULL, NULL, 16, NULL),
(20, 'Boxed Stationery Set Butterflies by Inc Peter Pauper Press', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306ddcb200eea03d92605446e180d2679f5.webp', '2025-05-20', 'https://www.google.com/shopping/product/14716014187280079153?gl=za', 882.00, NULL, NULL, 12, NULL),
(21, '\'Bloom with Positivity\' Blue Floral Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306610f109f807fcbaf2bcccf3003825b25.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9796993870758443324', 500.00, NULL, NULL, 3, NULL),
(22, 'Aurora Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3066ef494b5881981130d14e66c56c25f84.webp', '2025-05-20', 'https://www.google.com/shopping/product/8880519458850486525?gl=za', 425.00, NULL, NULL, 13, NULL),
(23, 'Bea Valint - Poppy and Pear Collection - Stationery Pack', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306e76eb063db51a07a496c82fbbd1d81a2.webp', '2025-05-20', 'https://www.google.com/shopping/product/1511588905485146742?gl=za', 180.00, NULL, NULL, 15, NULL),
(24, 'Jam Packed Stationery Gift Box for Women - A Fresh Start Box', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306c06f0a55f6040c04b1a77db12e55e76c.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14946682899480401295', 675.00, NULL, NULL, 3, NULL),
(25, 'Office Stationery | Pastel note paper | Shop Online Now', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306e28e3b344d39a7ab0c63637b1c926185.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11257015667188515963', 79.00, NULL, NULL, 17, NULL),
(26, 'Cute stationery', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3068cc94d6b1f7e1ecc3d79789b24a87912.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4174465050998376011', 130.00, NULL, NULL, 18, NULL),
(27, 'Stationery Pack', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30689e4c096b86745e54aa538022a79c009.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7913382098121323742', 185.00, NULL, NULL, 22, NULL),
(28, 'Field Notes Pitch Black Notebook', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3061a1a4b2e921c9a08b23af9bfbc007c1a.webp', '2025-05-20', 'https://www.google.com/shopping/product/11599516987502864044?gl=za', 308.73, NULL, NULL, 11, NULL),
(29, 'Okiyo Minna Paper Stationery Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306a22c723999ba754f30d37c41c556b5b9.webp', '2025-05-20', 'https://www.google.com/shopping/product/5382738557524588100?gl=za', 110.39, NULL, NULL, 19, NULL),
(30, 'Write GEAR Correspondence Writing Set', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306ca5db6d6cda727908cd84932f2438ad5.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4848277728253595135', 120.00, NULL, NULL, 23, NULL),
(31, 'Budget stationery set - Avail in many colors', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306837f9e484a07049bd8833d4d25cf5321.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1337156054118236133', 25.75, NULL, NULL, 21, NULL),
(32, 'Letter Writing Set - Lilian', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306d5c02462b0e6313006eef784f347f0f7.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:780405431524344041', 245.00, NULL, NULL, 20, NULL),
(33, 'Furbish Studio Stationery S/12', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30616322d3e7d3b578ae6319bae892a6787.webp', '2025-05-20', 'https://www.google.com/shopping/product/15247849093854273446?gl=za', 364.10, NULL, NULL, 14, NULL),
(34, 'Pink Flower Stationery Set of 75', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306f7664cf22f17812568c2a5460b7f9ac2.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:16535466344485211095', 2003.55, NULL, NULL, 24, NULL),
(35, 'Hummingbird Notebooks - Pack of 3 - Lined & Blank Pages - Local SA Brand', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3062a3ad4b843a56e193f1e0be010c18855.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6366389148340270506', 299.00, NULL, NULL, 3, NULL),
(36, 'Boxed Stationery Set Sparkly Garden by Inc Peter Pauper Press', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306364d93be2eb9c5a13f16d95d9d8f651b.webp', '2025-05-20', 'https://www.google.com/shopping/product/3377972528853778113?gl=za', 1408.00, NULL, NULL, 12, NULL),
(37, 'Box Stat Tree of Hearts by Inc Peter Pauper Press', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306e348826e1032ed33e4c79af42facc1c6.webp', '2025-05-20', 'https://www.google.com/shopping/product/5362289667767498308?gl=za', 853.00, NULL, NULL, 26, NULL),
(38, 'Let Hope Bloom Stationery Sampler Gift Set by Kaliwete Creatives • Likhaan', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306ff56f88478a560eca332597f286e9874.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18251391453075797765', 180.60, NULL, NULL, 27, NULL),
(39, '48 Pack Marble Stationery Paper - Letterhead - Decorative Design Paper - Double Sided - Printer Friendly, 8.5 x 11 Inch Letter Size Sheets', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c3069367211842ea6c92d2a0c5980c85bef5.webp', '2025-05-20', 'https://www.google.com/shopping/product/17719489754340843521?gl=za', 761.83, NULL, NULL, 25, NULL),
(40, 'Letter Writing Set - Petals', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c306aefce0fe48fe0abdc14c1587a630be4e.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5245400752381002766', 245.00, NULL, NULL, 20, NULL),
(41, 'Disa Stationery Set - Available in many colours', 'https://serpapi.com/searches/682044acb24c5d30db20b3d3/images/bf413405209d3af587674c442cd8c30676d5467ef772ec11c7c72411da0fc4c9.webp', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13386402500010549749', 26.39, NULL, NULL, 21, NULL),
(42, 'Cat Stationery 8.5 x 11-60 Letterhead Sheets', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSTtwcQI80ZAA70DEaiAR5tCm21C9OZsEmySRYpaX_x-NaDPh7KqOeUvYnk_8HNhJ8whdZFOmsduor2qWYvA_VcPuXNugCgIope946lF77WUVwLnJv4dYtfiA', '2025-05-20', 'https://www.google.com/shopping/product/1437237167158692314?gl=za', 808.00, NULL, NULL, 26, NULL),
(43, 'Hobart Stationery set - Available in many colours', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQhvNSxuDC6rbz0N-dJtH9FnhttPQtpqJMYp-1OqrwP5oOBiDrTX3wnxoDmWRgyyadxfaPu6XuS6raOr_LLE29mshIOZ6QOlpkuor67xCBE', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18002495372119042488', 34.49, NULL, NULL, 21, NULL),
(44, 'Blue Dragonflies Stationery', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcR23epHURx9ET-IlruP48WGdcWMlX_jCQBGxZXXXSq_Hs-mZePTG4ltffc4U3qX3bcLvm0_p6PKJRF8QMHirYHe9-qLMhhL3xrRz6EAUA_q-wXGH88FWuXSMQ', '2025-05-20', 'https://www.google.com/shopping/product/16743207110146924024?gl=za', 846.59, NULL, NULL, 28, NULL),
(45, 'Seashell Stationery Set of 100', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSp2du_-xRN7_Yo5NOFT_eySNXJs1amlokVMii_S21yKnjOCSJRaySbD1WgPSPMfIt5qqvwPfKlYH-n1R7YDcYyvYcoPu-fB3qdA5b2iR9ieQiB4dyWIqhX', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5457592689455009657', 2513.55, NULL, NULL, 24, NULL),
(46, 'Pink Tulip Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTE7J0ARBguBfToLcB0i92IF4NmounRj9nk6qhkdXVK8zeeuDCozId2uQfflNC-G1Dko1U56Ehc60IdzVEX7MrTjN0En4_U_uWodfzwI6bcM-s4xzWXSSjlqQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18380648412734748897', 1001.78, NULL, NULL, 24, NULL),
(47, 'Letter Writing Set - Lilian', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSQGSh-jQPx2zmxAXtU3U6a06TUQyq4bGmPMGaf273Kkyj478NjAPrsBgIgTgLMHaLL3VWp9nb4BF4IPV3WQrau8TsXtWIRirUm3fxvFuf8tBfRF9JRXa85', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:780405431524344041', 245.00, NULL, NULL, 20, NULL),
(48, 'UNICORN SPIRAL NOTEPADS', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRSJTD1Y60a4l9GfaYAA3i_PnoawRGOH55zmom_tfqv0iCX1lrbr-NbCbiBUVJKsnCNKvImLNXlm5_g5A4UpC5Dl7UGJyIh3qjlrLk8EC0', '2025-05-20', 'https://www.google.com/shopping/product/17969594400545421383?gl=za', 591.00, NULL, NULL, 29, NULL),
(49, 'Kraft Stationery Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRDwWOCpx55ewyu-DV8GfBJOYA5SFeat2eO_hz9qnEJSnWa-N8wtjULpR2_BFz8oVu_yt3IHSV2rgB3ho7W_rSVUf7BnbQO5Uh8slxx5aEI', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13470844439495863858', 18.18, NULL, NULL, 21, NULL),
(50, 'Rainbow Wavy Border Stationery Set of 75', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcR1jWqsOAtK5UI9TT7jBWYNSpadlm9RrGjvOx5L3Di54EsqdQ829YiZAV9XKPF37hM5cjXhOQ3Z6HoyvS-epnRFz_k4UscBr7u7MwdHVKO_pabFfb2pcKvC', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13965716352114324685', 2003.55, NULL, NULL, 24, NULL),
(51, 'Write GEAR Correspondence Writing Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQcm8ziE9VE_nTudl2xtQ94jhRM3bHX0HGyLg5b91Bwk1o5QyFeytWfu7GbYupHsPZcTiginMBLPCLQPXzoFIcjgMnLVdT18b57ySLOdDnNMSMGd78iGWpI', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4848277728253595135', 120.00, NULL, NULL, 23, NULL),
(52, 'Patelai 120 Sheets Stationery Paper 8.5 x 11 Classroom Double Sided Lined Stationary Writing Paper Decorative Paper for Note Scrapbook Back to School', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQgOz7vgrS7vsNuaj6OyzndJrPRlOMBmqlKaD1g7NNL3NY0aIEIBmxTjs3wGzSLRBW6b6JxIi8VpYZ6Dm3e-ekXDM1u8NjfAt4X-3Wnbo3llZe5glSCRlO9', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6324425320180106947', 941.00, NULL, NULL, 26, NULL),
(53, 'Avery Stationery set', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRWvUMw4e_QmgIWmiULTs-v-nmwcuEgJzO2h6gdyKqRmjxweRBWZHMKVMvQ5eKsQ7ECSmO0cRHLcla7QUw7rauo3ztZ_7kkmR6vd1OJpR1w0Ra7c6VXd8WbYQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13596854384561499921', 23.64, NULL, NULL, 21, NULL),
(54, 'Rainbow Stationery Paper', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSr6CZpEA2yD1KC9IWY9m8xrhvUNNJyOWXaVtUWZR_Ku2bsFoSV-3P6U-hPFucZNj7voLNKqoaUI-xfi7TZNrRV6kRjHV7jRsTcpVbLrQ', '2025-05-20', 'https://www.google.com/shopping/product/9354718467808510060?gl=za', 895.00, NULL, NULL, 29, NULL),
(55, 'Rainbow Crayon Stationery Set of 100', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcS72Ei7_DapN_nXwt7VbBpaEOgQE0PEuVaYb6U9LSN57FbsSZsB5znQxnIKnCw5lE87s4zrJ7mw-4-OBqGOImFujb6X-f8dY1iiyQfeuqcowkYMPszonR1u', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7135860872120729225', 2513.55, NULL, NULL, 24, NULL),
(56, 'Letter Writing Set - Petals', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcT8kr7ruNAEf9lAbyCxW0fmO3IFENUOkfcvq9Jz9-QtRz5TudGv8JKjMCTzSGBAIXpqun3aA-XdkEZXA5C0126Io7WaKpnxGoWY04rPZf9bVSYLBKjJBLIZ5A', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5245400752381002766', 245.00, NULL, NULL, 20, NULL),
(57, 'IMagicoo 50 Cute Design Writing Stationery Lined Paper Letter Set', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRfrFJqqu27zJCxCg2AjgTcstQSZBVpPIF33G3aldMK4nlq2kKSVwa7ZHY74YTn95PKFClw1mNHzATF6dhAdak8TnPIJhQjVlHvLaXVIuLrlMcoJ5lYRB1w', '2025-05-20', 'https://www.google.com/shopping/product/4063943826260734311?gl=za', 439.00, NULL, NULL, 26, NULL),
(58, 'Eco Friendly Stationery Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTjqfk5Mo1HL3fIyye8lQpyVP4IGtnhA5-Kt_W4AmitoAy7SSlsy_arRF3huAmaDTBGUuAgcMBhC3IpvMC86DeEH3IBk6IjzDI3hxiFI4E', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13206535358719128379', 26.03, NULL, NULL, 21, NULL),
(59, 'Couples stationery with palm Set of 150', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTkWCcDLAgbUvF13WZpTWBPncK_J84x0UsABSJO4F8FsXuFOZnu8hkAXjBZrvyv3GvVxbghmgHwEbxL4rHXz7EWzNLxLtsj-rrfRkUYiZphbCH3lY3ZbYwH', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17810860113360269747', 3460.68, NULL, NULL, 24, NULL),
(60, 'Stationery Variations:', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQ_p_JbwjofL0GL-vY-yfMYcA9OicnVhdf4y89vKgYRTquwedPz_UyzrI0pnPPOF29TPqROiS3AthvSVKrWtDPd7FFxtVmrQXl7xf_I9U0IYDxuJ6Kju30n', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7737348041240208724', 66.93, NULL, NULL, 32, NULL),
(61, 'Wavy Green and Blue Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQJwR8OXh_jYpOJLfnnInOWEm67JU2P-uPO5_q27i6p4N0KI1BfdSpk_OmyW856NvnrrqZw68--vSjGV3GvUUXdnvN_Wv5fRtPBBiODbONzWJ-ABKFt-DM1', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8769920070036337814', 1001.78, NULL, NULL, 24, NULL),
(62, 'Proficient stationery set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRV1P1LkGZUbD581mnolvCanF91tbYaDzroKcVweJ45nIPB1lcj_eBWnR4YHrFJlJTGK9OdDbHcPOR18L4hQVZ2ltPQgg99jq6L2VvrZDo', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7535067072195142316', 21.80, NULL, NULL, 21, NULL),
(63, 'Freedom Stationery 72 Pages A4 Nature Study Books Feint & Margin', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSu3CJs27MjYoJtFHjvwHCGpk2i-vJKwm792F5zTsib10TGudx246dXwCm7ajVegdc43KSnV2_Rj4LXSez_cy_7aOkAtN3OzhzTdGan3VxOeKx6auVIcIqE', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6988146213893100824', 18.00, NULL, NULL, 30, NULL),
(64, 'Lily With Cup Stationery', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSF5PxWgU6m8gjBLXIyV0Ko6x1aGtvWNgcNHH_qXbPaOgO91lHLlSsOE7sM43c3EeY4OCeQlzPkbod0Knz1xUQXuX6opNRdE8jU6joGzwRBnMus5XO8vjINAw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12745715032798999543', 9.99, NULL, NULL, 31, NULL),
(65, 'Letter Writing Set - Wild', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRoC2cJyjxA4Cb561VqvszD4pQJa56uWJDb2P4bL4cm6ZwnBUqvtdVeddgQ2Dxlj3qfnGqyuzSlODYyJgKE6Qj86rCvQNm-a4JAqNnxXHl3yvLd9FEPTTLa0g', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4100268849808068434', 245.00, NULL, NULL, 20, NULL),
(66, 'KSIWRE 48 PCS Stationery Paper and Envelopes Set,Cute Floral Stationary Set for Writing Letters,Double Sided Printing,Letter Writing Kit', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTt9DUotq91EoBqCkAi2GLcLxVcjYSgWcFvLfj_9geAbVSIpB4zvERIGBbVdjY-GYSbqpM5h3hT3dauPcy9ZNmUPsBX39OsBxFrvF62p6KxMQo5GE-pRtjx', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1258417663382948570', 897.00, NULL, NULL, 26, NULL),
(67, 'Rifle Paper Co. Juliet Checklist Notepad', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcR8j-AOzjwwJ5IM3QkQW03jKBynTlnuiyjtVbK5A6_pyOm9HouCvJMVWsv6zXnzDzFNVcyjTior6V7zUfU80P2Vpnqhv8XcHbKKCD8qCHgeZkvh2-I8nQID3Q', '2025-05-20', 'https://www.google.com/shopping/product/156283180213010292?gl=za', 218.57, NULL, NULL, 33, NULL),
(68, 'Giraffe Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTd0WJRoO08ty7657YS0AnAx4FV5JeGlSfZXH7PaB-DCwEsttjAhGRtnz1r-_vBxSKWE_5GmygRFTYOkRL6xetmTqhLJccJeK6ozZAMCGjC', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12521402391442807070', 2513.55, NULL, NULL, 24, NULL),
(69, 'Red Border Classic Stationery Set of 25', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRD7oV-X0NLUURIKgZz4NGDFSEi7RmkLUhkemcssfHIig3S1-Q1woNtcq4kUCEI-ydLnIFuhmDLcmbQCmX5xCHVPHVcymXjjPDMNS5mVYxGs3tR1PhIg_MB', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7404506798501214762', 1001.78, NULL, NULL, 24, NULL),
(70, 'Neenah Paper Classic Crest Stationery', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSHBoLKOj21dalcsHHrSIbATw-DkJsqRFeoTxUTNCKKnQYVtsUd-dvwhkybct59_6vgoaJwSyzRUzLBaci1OgoBgcp-jxMjnMIbNzNPV_f1jgg8QTuukZd7', '2025-05-20', 'https://www.google.com/shopping/product/1917359720914837636?gl=za', 682.85, NULL, NULL, 34, NULL),
(71, 'Estonia Stationary Set 15cm', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTlMkE2o1iwegH7H-ozmK6bHBY-HL0l0qu10Rb0KlfaehGjcwdhuE1FCJe0FobMRT0xB9OAKsIQHP4tmAy5lxq4iyAcQgGoSgtquo2CuE-V', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:16491841083490760193', 22.00, NULL, NULL, 21, NULL),
(72, 'KSIWRE 48 PCS Stationery Paper and Envelopes Set,16 PCS Lined Stationary Writing Paper with 8 PCS Matching Envelopes,Cute Floral Stationary Set for Wr', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQdYXMkfIq9MWIIBNJLcxRl69XjJx0T8AJDoH_7ePuaf5Sipeen9slmn3zF5q_YWx0ohjFhQa2M6wdzotbFqfnUaoe4UtMfjGFjg0EHO1cX9xqb0so9TgKU', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8129799282754857591', 897.00, NULL, NULL, 26, NULL),
(73, 'Stationery - Lunchbox Notes for Guys', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRBq-rSLBlX7Ys44PMUK45JY0mcMuw0gKzhtlpUQNTbeqZl-lm4xRfCd6ZfKVyjgxZehgTY_vfo2OtUYnPHdQ0evmEuNh0Dqz0u7u5iDozOOff7ERMmiwzk', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14109473199552069651', 99.00, NULL, NULL, 37, NULL),
(74, 'Frontline Stationery Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTEP_39GgFz13TaIWaJQReFejFXjMmaE3f6eSC-iGQNh21JZCyx7-fwnCZxD0bhPvaBHGXF8bJf9r6IAbCl7dveCqT3mLtfms0PPTG2bOiIBYuwgNzsCnn-', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8768585883299834494', 30.72, NULL, NULL, 35, NULL),
(75, 'Emily Double Border Stationery Set of 50', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRpUV5I_lnq6Y9Ec3lSyiWf-YAEhtEi7bHri5wbQRvPYDbEmjcAOMrTkDwyr7fmgAT8nAiItX4xb9is8idzWGEC5l0dp7hZIKSULCOGUzd6GITvBrpUSfew', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1937530731457621588', 1457.13, NULL, NULL, 24, NULL),
(76, 'Cute stationery', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQBO9aECX3tFy7gQc2DNKpRIlO366FLX3CEva4hv9BpG5r0msSfRiUfc_dxndfVvgIXUqQ9JPUbKp8RFeimGg8G-ZVx_ot98K5_TvhbN43T', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4174465050998376011', 130.00, NULL, NULL, 18, NULL),
(77, 'Blush Tone on Tone Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRhvbJK15e02sWzyxEICp4-Gr07XTTRUMk3ngtRFGee_2_FtkSQR2KDZdcrxFdvGsnzl_-S-5jMnS_FWj6NmZ87BhAZHVVZdwStDB7rnERfW3ld2bViM-0j', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4130609997626342483', 2513.55, NULL, NULL, 24, NULL),
(78, '72 Pcs Stationary Writing Paper and Envelopes Set, 24 Sheet Floral Letter Paper 12 Pcs Matching Envelopes Cute Lovely Stationery Lined Writing Paper f', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcReTELmGzXkuQxJc9oi4ey7_UpE2HuJ2M5OmtDvJsF0kncq5Py063Ur5Bc1-kCzAfktZA7UoHG-bKBnf2GkIftHvJ6jq4wTMd8f7_pPS2HGjFoySE_xJQ-M', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9688787427007359737', 1355.00, NULL, NULL, 26, NULL),
(79, 'Stationary Writing Paper with Envelopes Flora Stationery Set with Lined Letter Writing Paper', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcS0UP8zLJ2IzkEvssXtv4IZHjCCZ_wUiK4WH2bGcqjyygAwvR2ZN9bS_BJa3cCkB_2Quzk1ZxHZsmmYW2FKjA0M-077T_cZQbRIpEAJL82TIeBGXnajBZtYag', '2025-05-20', 'https://www.google.com/shopping/product/2350327679493722072?gl=za', 399.62, NULL, NULL, 36, NULL),
(80, 'Purple Passion Stationery Paper', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTrMgL4-Q1OHFrmJGHhUzez1KeUC7R7Hk0RANMNrQwGtyJHYmn8SWQyUmUx5J3rUT6Ymkaa5tcNQoLmIKJISkvPdFMSdn6l-ZJPALiDoIus', '2025-05-20', 'https://www.google.com/shopping/product/17400882552144974012?gl=za', 939.00, NULL, NULL, 29, NULL),
(81, 'Freedom Stationery 72 Pages A4 Nature Study Books Feint & Margin', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSu3CJs27MjYoJtFHjvwHCGpk2i-vJKwm792F5zTsib10TGudx246dXwCm7ajVegdc43KSnV2_Rj4LXSez_cy_7aOkAtN3OzhzTdGan3VxOeKx6auVIcIqE', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6988146213893100824', 18.00, NULL, NULL, 30, NULL),
(82, 'IMagicoo 48 Cute Lovely Writing Stationery Paper Letter Set with 24 Envelope/Envelope Seal Sticker (8)', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRDQSk9G_Zw5hJRo_pO6RUY7oQtbbGtS2rO_3E23nZ1eOu4WKwEPwZjZVXYQ7sa72xDvQ_SC2BNwgFcj2Pz8v8tLOzXMUcpimA7Zq3h802A7VRhZf6GTvEu', '2025-05-20', 'https://www.google.com/shopping/product/13433073886487410779?gl=za', 692.14, NULL, NULL, 25, NULL),
(83, 'Dino stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSAQMLL54LT5MiRyMd8gRO49--I2FiIyQuJu3F0qNOc56U_XG_cAEYvpkrwdERNG5J_lINxeDeJv_WnlOxUTGYM21BsjBYwLSQ_kp9wHA9rtzl1TiQMugqv', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14907840096819064386', 1001.78, NULL, NULL, 24, NULL),
(84, 'Great Papers! Morning Mist Letterhead, 80 Count, 8.5\"x11\" (2014250)', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT_BVrNBNwvH2-ozxfEbl4cvCswi-_dPf078q7FxNPw1c_7PYEXgW1Leqo6BIVHYjwC0mpvsOWAXZRUbh8BZHawgMnHqkgQFNwbdwarLOGk', '2025-05-20', 'https://www.google.com/shopping/product/13359026791388037490?gl=za', 722.00, NULL, NULL, 26, NULL),
(85, 'The Taylors Stationery Set of 40', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRYoaGZWmpNrw48ApP0NHl-Qdm8Jtv5Dvu6Njmh2CPCj1BB1JJZ299220qsi7ggTB33r0psOb5fDQTOo0vc2nG0PzuIujLEvOLt5FzGtLgTtn4R24rwVxEv', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6835123337294965552', 1274.99, NULL, NULL, 24, NULL),
(86, 'Letter Writing Set - Petals', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcT8kr7ruNAEf9lAbyCxW0fmO3IFENUOkfcvq9Jz9-QtRz5TudGv8JKjMCTzSGBAIXpqun3aA-XdkEZXA5C0126Io7WaKpnxGoWY04rPZf9bVSYLBKjJBLIZ5A', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5245400752381002766', 245.00, NULL, NULL, 20, NULL),
(87, 'Emily Double Border Stationery Set of 50', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRpUV5I_lnq6Y9Ec3lSyiWf-YAEhtEi7bHri5wbQRvPYDbEmjcAOMrTkDwyr7fmgAT8nAiItX4xb9is8idzWGEC5l0dp7hZIKSULCOGUzd6GITvBrpUSfew', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1937530731457621588', 1457.13, NULL, NULL, 24, NULL),
(88, '100 Stationery Writing Paper, with Cute Floral Designs Perfect for Notes or Letter Writing', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSJ56vLLn-laioQhN3viklj-oBR776KXGEoGTNW9tf0IuAjp9nnZhu1i6pCIh6zNyu3OucV5H45F3Vfhmgch_qRiTmdna7AunG2XO04bfteT4O6HHH8jZYj', '2025-05-20', 'https://www.google.com/shopping/product/14661806527921249193?gl=za', 910.00, NULL, NULL, 29, NULL),
(89, 'Maps Letter Wrting Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSdqhwxcwTMLPuFClVD7wtJpYmhq3Rjleuy9jEwPPXA4vrXviCxue-fb6SN3ZGJPHdDBTHLxt2BHuWYkSgsqErCTL7zNJzKLiNPhrJHBKNOV_JpBGSL_5ZvWg', '2025-05-20', 'https://www.google.com/shopping/product/8415966100578890430?gl=za', 538.03, NULL, NULL, 16, NULL),
(90, 'KSIWRE Stationary Paper and Envelopes Set, 36 PCS Lined Stationary Writing Paper with 18 PCS Matching Envelopes, Fresh Floral Style Letter Writing Pap', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTV3mc_0aJgEFVoftxh9-h6N6UlNEaC7YcHJe7b6EKssIV0nJSr_SwzT_E-_RtZPSk4lZE2ru7LzuP5-T-8NzdCxJrREkwOsBPeyuBaKBDh', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5660924475049107314', 678.00, NULL, NULL, 26, NULL),
(91, 'Squirrel Stationery', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTSe5wDyNn5uVFBC-CZzqq5yHODsvJaSGOyQZeTt6fDqCrCq4wpXGzNtjLsO7S-pW2sTUzSlcQego4F_0-RuRqgurUN9rcO9sCpuWZMZstzMUKVTuaJ-JZxZg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9576314341532463977', 9.99, NULL, NULL, 31, NULL),
(92, 'Quilted Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRFZmfGeQv9vyB_8hdks7YbuozsullY2MajbCA8LDx_DlkACBFnmh21KY1pAxskTSM8GzqLHFW1G-ZBUMYUU_aHfla1ZN985ypLh66G-7Uvw44nCDZD8LLf', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6879674147991196838', 2513.55, NULL, NULL, 24, NULL),
(93, 'Mr. Pen Stationery Writing Paper with Envelopes 60 Letter Writing Paper with 30 Envelopes', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcT0nlkmh-wSjC4bGAotj911mKDz3nZEEcvAaBNtGVNX6-ZANRE7JVctVPbe-SMBVvCet9Z-plmTllC1dZhwtl_B9HhCP4H0XpxJ3tY-7ZnkXNklBkjyack9', '2025-05-20', 'https://www.google.com/shopping/product/15902146149712927328?gl=za', 678.00, NULL, NULL, 29, NULL),
(94, 'Letter Writing Set - Wild', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRoC2cJyjxA4Cb561VqvszD4pQJa56uWJDb2P4bL4cm6ZwnBUqvtdVeddgQ2Dxlj3qfnGqyuzSlODYyJgKE6Qj86rCvQNm-a4JAqNnxXHl3yvLd9FEPTTLa0g', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4100268849808068434', 245.00, NULL, NULL, 20, NULL),
(95, 'Personalized Note Cards Stationery for Men with Name and Double Line Border - Custom Stationary Set with Envelopes - Flat A2 Boxed Notecards, Choose y', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcR13xtPcOAwh9bjAlaLJX6SN8xg21JipvXZpg0XgUjEQhwrSLX6uxjuPLmbi7PriZO-rXhed96U9v5p62y3IbkWq_DdJEAEHyy_RZq5r8GDC6pmbpy9JoNq', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1789272125380074791', 1006.00, NULL, NULL, 26, NULL),
(96, 'Fish Stationery Set of 50', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQDBlIoTBHrKWleSKW3fYLBRSbsiEA6hWdmCnalATaHm7N6h0f8UTVqO-ck_q4w1Y2S3ECWLXUrPAuoVkfcYn5o2HlJk8K-Iv1ZfsLuWQ0', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9004550268950852847', 1457.13, NULL, NULL, 24, NULL),
(97, 'Stationery - Lunchbox Notes for Guys', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRBq-rSLBlX7Ys44PMUK45JY0mcMuw0gKzhtlpUQNTbeqZl-lm4xRfCd6ZfKVyjgxZehgTY_vfo2OtUYnPHdQ0evmEuNh0Dqz0u7u5iDozOOff7ERMmiwzk', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14109473199552069651', 99.00, NULL, NULL, 37, NULL),
(98, 'Office Stationary Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSVFYiDwKrf6sufjb7xA9QUG8UF9FIFb7mxoUOt51ZqmpWT4YtXIGrZCUnFyBajRFpar9r9bmNos2fTgHlT2QpWXM_R5JuoFerAqfKQU3I3rzY1w3CIV9QB42k', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:2363556929381610103', 419.00, NULL, NULL, 38, NULL),
(99, 'Blush Tone on Tone Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRhvbJK15e02sWzyxEICp4-Gr07XTTRUMk3ngtRFGee_2_FtkSQR2KDZdcrxFdvGsnzl_-S-5jMnS_FWj6NmZ87BhAZHVVZdwStDB7rnERfW3ld2bViM-0j', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4130609997626342483', 2513.55, NULL, NULL, 24, NULL),
(100, '80gsm Paper Pads - A4 50 Sheets Pastel', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSvWnOVu7gwgbIu_nmPNixKdMsUobtWa5wvQI8MT3MUzAAvJzjdM0Ea8NvG7UvUJ_CNqHOL8WiPSyklu4kR8_t7rvsrEFOk_dUyNfYg6iIz', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1019867081123668785', 45.42, NULL, NULL, 39, NULL),
(101, 'Stationery Pack', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcT4f-4GU4l0ETaIkhuZ4pa9UaJc2_srp3WP2YnpNALsJEy3-G-gZDy-NI2HmG3SduhGn3D0KbOYzRf9rjYweNo68stKvazPYeCHFhBgCHfaQ7VbuDvyRsB7hw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7913382098121323742', 185.00, NULL, NULL, 22, NULL),
(102, 'Soft Clouds Stationery', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSoCVfAQN645pD9cfEqpwiuVW8dC9ADwu43TOi9KHifx28scnV3eaaD1hK6ecSfLeAVeleKMiVyaxHRNbSuMON3CNE5DPXvWxMCIek7-oI1', '2025-05-20', 'https://www.google.com/shopping/product/16512712649485188884?gl=za', 1134.00, NULL, NULL, 26, NULL),
(103, 'Rainbow Row Stationery set', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcThqXwNHvnY8Mz8b_0udZgovzZVxkofuG65_kK3easo8ohHW-XvpE0y1-M13MY-V5G8u6fvwUqAMeOMomrXycQCEsGWIa54r4ePEs3pCDGTG1GPIbKBZI4AXQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:903570668765000848', 309.64, NULL, NULL, 24, NULL),
(104, 'Kids Line Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSmg39cQLZYiwHNmRT-6bAfWZ-lCyNF4sq3pRsBfp2GW1ZPzOqXoOw9r-GqG6I2OqMERhzgTeml2JArpPvsRN9y7SKknpsKeyXsxtIc2MQf', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15205010537974895728', 1001.78, NULL, NULL, 24, NULL),
(105, 'Global Notes 75gsm 100 sheets 75x75mm - Diabco Stationery', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQWFZHXNM8qtHPAdT7_MdVyvotlHwT-yxYTcDqkKGymFwk6DUkjg3uHzZHn0JUOoh2tytsYJAbX3-gBEdZ45f6HAQZMmy_3RNyFAsB8MXbfe1U4qWx2zCgX', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:252596883204439525', 11.66, NULL, NULL, 40, NULL),
(106, 'Purple Rainbow Stationery Set of 150', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSvMxW-6EtAEDmrMr_Il9kJLijw-xBv5XApWcl2rmScCZfPAinoU4Jwqi9lSNIngQJLaJKs-ik6TW3JQn_xIhPxL3gE911MNETs7WN5ZVQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15998760119292611010', 3460.68, NULL, NULL, 24, NULL),
(107, 'Pink Tulip Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRoUhdB2DERc0KjdNdq5xZ0HnmTPWk2TzqTs39N4bFEEevv0Ccn5lOz9lA_8VkhM9mK92c24XDhzIEM3w-1-shHNYGuscmD-EEwWesbmfAYPPgMk5IneAl2Jg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18380648412734748897', 1001.78, NULL, NULL, 24, NULL),
(108, 'Rainbow Wavy Border Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSnlv3XLqgmLhlu1NdCtJy0KtZUuwFC8EjVhoe4SXZCM5JG3aXPUOfXHr70fw3Xyh-pj5y-mP3zBYpj8jD7RK0PdbDfFyVk69PC2ASE_wzNXpL6zs91fX-W', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9060127803616710422', 2513.55, NULL, NULL, 24, NULL),
(109, 'Geranium Stationery Set of 75', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcT37GlYlBoIp1GzqBAObRJbX1T1MB8pvo_3hQKRIW3mBnhbB_sJI5agIstcdu32kotriRwwRM-Kmo9_9XM0-uHIHAXsNas6FSnJX9Q0kFupkAvCe3YQVfBA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6169456446670767993', 2003.55, NULL, NULL, 24, NULL),
(110, 'Yellow Flowers Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRHhbyRRjRoRu-pZ7QyTTcbyuSNoncojofFE2BWd_Dq96No3Deb0RjhrGNH1J2-F0K__wgJXIoBzBn__aqqAaOYIrhmtYiNOcrKoVi8PBgQYYCuV3LoXzkN', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:2256940649803458769', 2513.55, NULL, NULL, 24, NULL),
(111, 'Bow Lattice Stationery Set of 200', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcSw8ZIJe_hMbeUSVYy2sPP3LBlt26hTrJfLWOPwiQgydCU7Tku97TRPQ1TTQ5eMaOENufthmlEDgdWrelwpONwb01ShwTNbQ-8hujAX_TIUuRzpjRbpkMin', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12023835276474861080', 4462.45, NULL, NULL, 24, NULL),
(112, 'Purple Unicorn Stationery Set of 50', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSn5RXfivr1vB_wOF1gm1GlyCUoKFyFWrPjJeid7Igt4fzrDl8epejSvezJfvi5K6_JuWm7zPZZqjOsMJXZsF4q8JKQzVKwVZZyv8_6m61Xup3_laRLgejRWA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13416701661398169606', 1457.13, NULL, NULL, 24, NULL),
(113, 'Blue Herend Bunny Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTuQvCKk3N-rLOPZZAsjI1TY25UCJ3W-CtfHS0XiRgQrIadOb9hHw4xJgpsUJr-8x_bP2EjUowXbCNDGsT4bl-9g5T2sR7eAGWcmC8yFuduHzTtv-OPcV2pp-E', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8568386881353483825', 1001.78, NULL, NULL, 24, NULL),
(114, 'Two Dog Stationery Set of 50', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRK4AQT-h3-yR0a2xk9uJVrcG0gtm45PcHJ-zB2-vP2At_gNxUppCKoDG7thvPLfhGrpFbEpdOzl3lw87S-ayxk7GQe69tItlAXx7vPkFHx8P5w2QsAuDU-_w', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14833002248217123077', 1457.13, NULL, NULL, 24, NULL),
(115, 'Luca Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQlJDlmTmgBSlJk0W4fZ83W8wiP5fXPT0cWsN0WJ6mnzJ4Bs1rx1bMr2wQIs2kjAmTzU0x9E1IAll9BG6DF6_yJ-h5kn92JvHmhKsiQ8LrLnPtZ4tG7kOhciA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:3600777536187066900', 2513.55, NULL, NULL, 24, NULL),
(116, 'jWk initial Stationery Set of 75', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQ-h9cwLUEI6e3nMHoSPkRG2W0aW9d1MBY9QgHrmwzg2-uX1iGofI_0CieK2L5VWhMrJlq8kgqthGIiSSa3JVYU49IarB7PtW8NwuuUUcPM6t-SDWKtRriq', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11590036771769721867', 2003.55, NULL, NULL, 24, NULL),
(117, 'Red Border Classic Stationery Set of 50', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRD7oV-X0NLUURIKgZz4NGDFSEi7RmkLUhkemcssfHIig3S1-Q1woNtcq4kUCEI-ydLnIFuhmDLcmbQCmX5xCHVPHVcymXjjPDMNS5mVYxGs3tR1PhIg_MB', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8592745148857008228', 1457.13, NULL, NULL, 24, NULL),
(118, 'Dino stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRv5g3vpFC1xS8kJhKZfT-MQmafEyWhklQUuFs7LPbP3YQhIx4nsAi3WoxR__vj3hhZs8MT1IbP2rP0_FjrEt8-bAAQD35bel6Ll44IOKagBbx8zRaPMq2D', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14907840096819064386', 1001.78, NULL, NULL, 24, NULL),
(119, '“From the nursery of” Clementines Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQpb8f_RPYhTDMw9D8W3uWK96i_EeIKl0U89VHHLOCIhiG3toCnR5QllUji8bYUG42mP1jrdmKYgzaq0Gf0EHM-692rMDZdP1bWQ4t1UAI', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17212199248213028839', 2513.55, NULL, NULL, 24, NULL),
(120, 'Frog Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQLMUD1nKFsII3xXTMyAEcShx5or-gYSIdxNQhy1DOE_SDkax6OuIEOm5pLSFowCRziuMuzs0vcQyqpJTeM4IcPUNSMxUOItfy0Hy0f2V0Nw0orim5rv-MB', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17837149737881770077', 2513.55, NULL, NULL, 24, NULL),
(121, 'Mushrooms Stationery Set of 100', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcT1KP491QH3XsyZfLk17L-3qsW1dC-DukNkXq3UoDpi_bvzgTLdaK58n63SHg_JFMYpPUpLPbysgLwPDy5HcfScAre-IMORsb6C-31mvi1pYOlaAx_lQTMG8A', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:2338322529639907966', 2513.55, NULL, NULL, 24, NULL),
(122, 'Blue Cross Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSEWWa5p3bR6BdGuB7IQvMqj0ipGzSiZLEP_IPiLsHIHxoEVPAM-dQ_db1sds3eNkcsvIB653cy9Sjszkut2jRM-4MykMRZul_FQb-SamhE6Ftm-Q8YMVknpg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5448879647582784348', 1001.78, NULL, NULL, 24, NULL),
(123, 'Couples stationery with palm Set of 150', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTkWCcDLAgbUvF13WZpTWBPncK_J84x0UsABSJO4F8FsXuFOZnu8hkAXjBZrvyv3GvVxbghmgHwEbxL4rHXz7EWzNLxLtsj-rrfRkUYiZphbCH3lY3ZbYwH', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17810860113360269747', 3460.68, NULL, NULL, 24, NULL),
(124, 'Baby carriage crest stationery Set of 40', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQ0pxRVOAFSbGrh5DUqf8Itp5CAyUyJhV8HpytrXXKcE2LLeZolRULptSlp5Wz-7KYEVO8SaqpdpKWPla_beHSsHrQ-vhNqBdEVRMePqDo', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7717789842056072687', 1274.99, NULL, NULL, 24, NULL),
(125, 'Baby Blue Stationery Set of 40', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQIdhlaAXpB94uNMtDI3pny-Z4Mfr2z7LpapG7XrrMpYoK1y25rVnEkKLDtP6ynd6xC-Zvnhr_T6wzyDw9OxuEAbYgqN6LpqUO-sUUx9jE', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15497828403330635478', 1274.99, NULL, NULL, 24, NULL),
(126, 'Greenery Stationery Set of 100', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSYL6tnjW1s8wtWjxiUMLFkKUwu23dxBHESN7VCQ8dOMoZqX6lrNL3Lr7xHFpPTLmf9eYgBQYxfzWOD1wo5MtAfJXRlpdNTnjJsEE-C5NwyAt1yb3p6qa0Y1Q', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15171801076589732818', 2513.55, NULL, NULL, 24, NULL),
(127, 'Pink Script Stationery Set of 40', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTaBoSAmSb8P9Kaxqt73FxO2fD43-xzECPlthWlZKtb0bBDkdcACgfSj40fKLkzcJdgwC00IidZO75fm5QuR8XZUUCMFRVJr2_n9ehj60g', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:14910319951496629294', 1274.99, NULL, NULL, 24, NULL),
(128, 'Lacrosse Stationery Set of 40', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTmMTdUf9aARyPD_PNAPryVPLJyETsfbjRULWSoZ2B-N9u8HSUYCwobUMomLVXGGOVQRAnNXqePTzoSOrJztKnXKzP88Q_xtDTChRn36Ta1OKC3m8SSbPlwSeo', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6873693879958340963', 1274.99, NULL, NULL, 24, NULL),
(129, 'MKS Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTOGgPHbplzR-LhF-VJwyKTQAFYPHJN8Y-9KWbXdg-aswmxRDczJxDP6Dpeq62A52L-uMTag6jkfDXSbx6afj1xNzp_cGuN6oCNIMdFi_TbWIZIaEs9u0x-', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6376422026454029259', 1001.78, NULL, NULL, 24, NULL),
(130, 'Golden Retriever Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTKXmKb8aK4AQAQjodvjQ3-o7q_IcxKemAHCT-xAZSeLmUvguwLiCcq1zLCqCsx-b458sqr-lCR9TKlHMw2KJ3CNmIHmMhWYqWVz6v3FPeQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1942313276993000511', 2513.55, NULL, NULL, 24, NULL),
(131, 'Chartreuse Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQtj3OeLn_iD_pE19bWkaByRU0brLaMWoIz_DopkPxWr0eiU4V_Vz6ODTVmhnjs5Bt1u7UZkAePOEZofgb18vgjCMKBBLpxc2bQAa6tj8hK', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10047157530153969473', 1001.78, NULL, NULL, 24, NULL),
(132, 'Sheepdog Stationery Set of 40', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSzwi6OD0MvLkCDtX5glKMr90VgYnzqAHU-gS7583iWF3sa0TM0HKMZrHHffKjNmPBWyORMh3AKMq4idB1wvVs-hb0S3KsMTdQqsuu67OrMyBog0yILZtGY', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10781335752390674364', 1274.99, NULL, NULL, 24, NULL),
(133, 'Teacher Stationery Set of 50', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTHNDQmZxMBWiyZJHrdS7hhCvIFrSBIWFEUpFOBhQJbV55lO8AYKWNanBSUhN9BjdMIKvYsUSTVLPl1eee8gpXuOMkTEIprM3_2X5I6NR5RYVekxXSvvSPtUg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11404194957983723038', 1457.13, NULL, NULL, 24, NULL),
(134, 'Cowboy Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSfuNkHS5iikm4_pPNhTzLzjZFE13pXeo-4LGEk3EaoLpS6mjQe-8RYZCyyUdgGtEOp8LnK6p0otgCfgVPk6gO-tsmlypw0M-VEQyGdYo_99gfhNmBoaFMpNA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11502728578669985831', 1001.78, NULL, NULL, 24, NULL),
(135, 'LEE BULLWINKEL Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRWiA09cBxsMAh7X7_79A5b5ywaSncnagCIc5uyp0C2-eqaRP38D-uZsrelJTLK7Aq4LNDegwUzPyW7VbP1r5XyKGzg6SXsXdjylzTKca7tAPjPHWcuhLjsaQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15285642900342413738', 1001.78, NULL, NULL, 24, NULL),
(136, 'Letter Flower Stationery I', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQuz3ZmkffRV_QMZOV4ajmpVgkz2hh5rm9aL47T0YsaF-0ttviXZ-N1gyZ_xg5IEezwqIO-kSajBT25fDilvoEYS28R4AS6BHXoHtbecajBodqUEu-KVkazTA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:687735772006012885', 273.21, NULL, NULL, 24, NULL),
(137, 'Football Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTYCpT_rnYhSoMnbMro0HNemXOJNSVjVxaYPU-8Uf8uJQvlg8tWndZ1yy0ux37gt-vr_-Egas4ooRbX5qX7cA6QcWHvnKI4xnAJndmEo_WBUFxSNLkJaSD-8Q', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10920512929675395030', 1001.78, NULL, NULL, 24, NULL),
(138, 'Quilt Stationery Set of 100', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcS6o6pHn7SbKpxVl-rHJauHpMiGEfNDVPw3xZHFsiFadRcGARbfqvvKI5q6Sp75gGXZfSE7KVRu4QC69sGkmEmFG_ZXn-CChdY50muFM4yBv0bVToc2-moOjQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9984130533374949539', 2513.55, NULL, NULL, 24, NULL),
(139, 'Scottie stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTgjNSnvzM2c2XdEwT6zIyHsEen1RyukfC2HYG6XQPTiGnwxNTjntk3MkdYQAUi4iitZOespdFuuoGNtf23Qcir-yNbQxAFEFfYkk1DVb4', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11047070362382176246', 2513.55, NULL, NULL, 24, NULL),
(140, 'Maria Tucker Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcSN0WZRz1G-D9mCcemHCFtSXpHb8x37vEWTGHKojGhEbi_04ZNevFFfeTW2eYMhf9odMuvkfIGjfsqy3lRdx-5ZRFJeOKQxQxMA_Hg0G2LYWARCj0-7GvU6', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:16428912467254097167', 1001.78, NULL, NULL, 24, NULL),
(141, 'Tide and Tail Stationery Set of 25', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRcqbwbBQrilgl4PAn6n1eR3gI_I3UC19AX5QbZ5syYKQwBIt5uEFg0TInEDGx26vfcZ_yfKcaHfGQ4bKMeSOdLLTJs1m66P--JpKRrgXyaQt5DjZiYhB1nJQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7389892942987838668', 1001.78, NULL, NULL, 24, NULL),
(142, 'Marketing Stationery Set of 25', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTWH-UOM73fJyfqR9wFjhtG6PJxofLpv5c1dRetzKXpIFO3uA2Nx_WaK3nbcyToElTHAVcsATv1J-okkmtVv9rNd22fGIDMyx7ZmDzX4_gGaEUpdWQEIBfi', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18006995878672949811', 1001.78, NULL, NULL, 24, NULL),
(143, 'ETS Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTDyBTbMrLtB9GhB0fbLiHuYRc74XQs2g3-E_617DhQBinmdLt3QEvL9MXOrksxSR10ahZE-gLJmNTiTnIiQEAHlknEE6W4HW79dLN93NAZzRn3h8Tk44NlUw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:16251690325335549651', 1001.78, NULL, NULL, 24, NULL),
(144, 'Powell Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRR413xK9LNNrjbEC4QZHQjJEQETPhK7qGLKAC-I_BqkbzQ0AMPOd3dz9sjRqPeJFPAv5bWNtQ6VAL4nZP2cm_luJCeJQwd1VbeN1b6RzOL89qz1JPLKRVq', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:3991538263330068010', 2513.55, NULL, NULL, 24, NULL),
(145, 'Business Logo Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRwNNHbJyJVlPP1ufN6s1cDI5gatIuqDpcQUh4AjTe6gvE1l3cru1FVB9Y468S0TKTKhzVEnCHcthTc2ed2zcyiY2eUUNnUcEcOSb5bjaLC5-iFNH1phgoW', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6256233147123075236', 2513.55, NULL, NULL, 24, NULL),
(146, 'Head of Boykin Spaniel Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSqWo23WWvh31bPc0-l1WT_6d98CPHnGUDt7OTwjwy7SoiZziL8qtq0wIVMeqQFjkZFJkCfVJVqSN-jVjYNu6v0_290OkMuY_Opqwtz4cR47M_AEO9p9-GlJw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5561260697631628851', 1001.78, NULL, NULL, 24, NULL),
(147, 'Kawaii Stationery Gift Set Blue', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQbpJPFe02k7JN22rMKTnnRiDfxuSva6gws1_c-w0LFVnux0-iyxWgF0vp-07Oq4Ck5EVmkuVzFHFNpRiGhq63rAKGuUyfiZTCQ8DBkmG3k', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5505233498331852640', 874.09, NULL, NULL, 41, NULL),
(148, 'Custom Stationery', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQ4US3AEmyixtvU8oQAFlhEIttERhPxZeeJ06_MJR2UPMeFkNzFFa9fyru3p_yTed7fz_FouOmQOrjYAlL8Tuj7CKQfWo_mkGRX8LIqCniJacOQ2xideRIB', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8343328825600307163', 85.86, NULL, NULL, 43, NULL),
(149, 'Blue Herend Bunny Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTj5qWU6nagzODWvAxNgPI132o2t7qxvFfOZils1KnfC7ZJQErRc6pZl-sX26sYKI4Gd-MjPagKoM5cRT1bhyeQXo7eWq54_VGBS1UjLxeD3BOwvfBX51q6aas', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8568386881353483825', 1001.78, NULL, NULL, 24, NULL);
INSERT INTO `products` (`product_id`, `title`, `thumbnail`, `launch_date`, `product_link`, `price`, `description`, `category`, `store_id`, `brand_id`) VALUES
(150, 'Bubblegumfringe Stationery Bundle - Neutral Elegance', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRqB-qb_vcCDXYnIWY_A30ztEPj4wtA909U8wCn_RyVi-akB3Hi2m2IupmwnjCzw6KB6gQu-isI_qeilRq2IRJYJj70_melMd-h38OfemlgguE3O3fQwL1z', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15218141108904893340', 390.00, NULL, NULL, 42, NULL),
(151, 'Hygge Jam Packed Stationery Box', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRvep75oty8Uk6qxjRmFE1nTgTmA3rFQRijtZQNC8l7UWDIRUaX_4wE4td2wRXaD3_HQxUdq3hAR3hvVp9U_3CtqN36Dlu-GMW4zMJous0i', '2025-05-20', 'https://www.google.com/shopping/product/16114892995639172922?gl=za', 607.50, NULL, NULL, 4, NULL),
(152, 'Personalised Sophisticated Stationery Set', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSQQ8eujc-EqDJJ21QAmozuZ_CoCry_2q32NQhDMCuJXlWrbi5-96iy54ggOYpoIWbwR1MgeOcGvv-EdcXch4FMWrfTZasBBEwNNUnTlNpA1usuU_NgsroBeg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18303792873322991034', 359.95, NULL, NULL, 44, NULL),
(153, 'Colorful Watercolor Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQ1-GkevYIVfOPxoTcMiqM25opW2V_r6Lv2tj187wxEetA8Mxk79qAuLZzKAq7HCEZQBDq7AepETdN7ntbOKy_lIDYyvtOL3M9HL5JyjbG9yK7F7Ju8JQ_U', '2025-05-20', 'https://www.google.com/shopping/product/1340375985922024165?gl=za', 399.00, NULL, NULL, 3, NULL),
(154, 'Better Office Products Mini Stationery Set 100 Piece Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTR9AYXxgH1nz8CJqVVfnVlstDf_ahayb8pVvbW_w6g3V_Z_Cj5iraXLHpwlapIRHxa6O6EvnOg1yf5vH6tYRQpk816BqLmSt0itwLkW0E', '2025-05-20', 'https://www.google.com/shopping/product/9568615880255376202?gl=za', 403.00, NULL, NULL, 25, NULL),
(155, 'Personalised Swirly Stationery Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRbe3Ca2eYmfpCufsj7s3lttUB04NkKIdxJkryOoxBvuJkj89zd5QeEl1desd8tDMj7VgIGvMcDuEo2AXg18AIrvcF__tEGuv29JeaHgEfw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9820202873098854040', 399.95, NULL, NULL, 44, NULL),
(156, 'White Rustic Wood Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRe4XBVkI1oUtait0yDiaGa1UlM6lIXZsE1Ymp25NlDqVexUrBhgKdVIVCfkpCzxdSL-nMZpNHFImrpqDZGOQHHkcVT8zd8JEISZlPWxh2kR2BikUpEgJve', '2025-05-20', 'https://www.google.com/shopping/product/6352098829915337754?gl=za', 399.00, NULL, NULL, 3, NULL),
(157, 'Variety Starter Stationary Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRMm9Bw1GmcEks1o-_jxD2OuXYvOty19FQhFKbbbXau2KAF1BhFB38FnDJHlC9YvbqoDTwwYzT2vmWK4w-BP8l4k1aYvEsVTjpURJ3SzGP7T_qBzRVcWi3Mdz8', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18144064197300663516', 95.00, NULL, NULL, 45, NULL),
(158, 'Personalized Note Cards Stationery for Men with Name and Double Line Border - Custom Stationary Set with Envelopes - Flat A2 Boxed Notecards, Choose y', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcR13xtPcOAwh9bjAlaLJX6SN8xg21JipvXZpg0XgUjEQhwrSLX6uxjuPLmbi7PriZO-rXhed96U9v5p62y3IbkWq_DdJEAEHyy_RZq5r8GDC6pmbpy9JoNq', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1789272125380074791', 1006.00, NULL, NULL, 26, NULL),
(159, 'STATIONERY SET ASSRTED ON CARD 6PC/CA', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRazhDNGUl5kTTbkLlYc0qnFQGrLGlNF76Fye2udUeLqfTcov2UxaXvbfVSC2GJcUbQOqxwwD976MGv0kQ6yQUZ0udAU0EsW0yu8fMBMZF6Bu_ZUnbSPNLv', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17613249351969122471', 25.00, NULL, NULL, 46, NULL),
(160, 'Vintage White Roses Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQ4hPPh8rkblV8RauOqywHsYdiTMGvD-PXZNsiAXcjlNhYnNYtAOR5Lh5CzOEgW-wFDqgxeTXHghFFyY5vftDvsBl0sAyZczAIRmXb-90PH1FjeARJvmfYaWA', '2025-05-20', 'https://www.google.com/shopping/product/8627879655852518731?gl=za', 399.00, NULL, NULL, 3, NULL),
(161, 'ETS Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTDyBTbMrLtB9GhB0fbLiHuYRc74XQs2g3-E_617DhQBinmdLt3QEvL9MXOrksxSR10ahZE-gLJmNTiTnIiQEAHlknEE6W4HW79dLN93NAZzRn3h8Tk44NlUw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:16251690325335549651', 1001.78, NULL, NULL, 24, NULL),
(162, 'Tide and Tail Stationery Set of 25', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRcqbwbBQrilgl4PAn6n1eR3gI_I3UC19AX5QbZ5syYKQwBIt5uEFg0TInEDGx26vfcZ_yfKcaHfGQ4bKMeSOdLLTJs1m66P--JpKRrgXyaQt5DjZiYhB1nJQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7389892942987838668', 1001.78, NULL, NULL, 24, NULL),
(163, 'Personalised Animal Print Stationery Set', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRZE072kzRFKovHt09eP81fiHZsiLwqZ_U1Ia9tO3YHjqg3smnK4ZATRiLHE4UlYLUgXtCKAkj2VCeANLE0tjLf1BdE7asE8HfUKLYdc3Eo1nLQRFMinq99QQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10659853743946605184', 229.95, NULL, NULL, 44, NULL),
(164, 'Business Logo Stationery Set of 100', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRwNNHbJyJVlPP1ufN6s1cDI5gatIuqDpcQUh4AjTe6gvE1l3cru1FVB9Y468S0TKTKhzVEnCHcthTc2ed2zcyiY2eUUNnUcEcOSb5bjaLC5-iFNH1phgoW', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:6256233147123075236', 2513.55, NULL, NULL, 24, NULL),
(165, 'Powell Stationery Set of 100', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRR413xK9LNNrjbEC4QZHQjJEQETPhK7qGLKAC-I_BqkbzQ0AMPOd3dz9sjRqPeJFPAv5bWNtQ6VAL4nZP2cm_luJCeJQwd1VbeN1b6RzOL89qz1JPLKRVq', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:3991538263330068010', 2513.55, NULL, NULL, 24, NULL),
(166, 'Blue Herend Bunny Stationery Set of 25', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTj5qWU6nagzODWvAxNgPI132o2t7qxvFfOZils1KnfC7ZJQErRc6pZl-sX26sYKI4Gd-MjPagKoM5cRT1bhyeQXo7eWq54_VGBS1UjLxeD3BOwvfBX51q6aas', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8568386881353483825', 1001.78, NULL, NULL, 24, NULL),
(167, 'Andrews Dog Stationery Set of 50', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTOdI1Z4gTbdpF5qz5CfVwm1fsUxStDA5NyePRJqQXSH62u-6eZMvVxN_VaQsoAqMFPDfDdX3e8gIvkCwwNQXxQEJFD0LyNWNHjrAXPQ5iewk-_rCxz-VgZ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17955549150314591288', 1457.13, NULL, NULL, 24, NULL),
(168, 'White Rustic Wood Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRe4XBVkI1oUtait0yDiaGa1UlM6lIXZsE1Ymp25NlDqVexUrBhgKdVIVCfkpCzxdSL-nMZpNHFImrpqDZGOQHHkcVT8zd8JEISZlPWxh2kR2BikUpEgJve', '2025-05-20', 'https://www.google.com/shopping/product/6352098829915337754?gl=za', 399.00, NULL, NULL, 3, NULL),
(169, 'Colorful Watercolor Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQ1-GkevYIVfOPxoTcMiqM25opW2V_r6Lv2tj187wxEetA8Mxk79qAuLZzKAq7HCEZQBDq7AepETdN7ntbOKy_lIDYyvtOL3M9HL5JyjbG9yK7F7Ju8JQ_U', '2025-05-20', 'https://www.google.com/shopping/product/1340375985922024165?gl=za', 399.00, NULL, NULL, 3, NULL),
(170, 'Hygge Jam Packed Stationery Box', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRvep75oty8Uk6qxjRmFE1nTgTmA3rFQRijtZQNC8l7UWDIRUaX_4wE4td2wRXaD3_HQxUdq3hAR3hvVp9U_3CtqN36Dlu-GMW4zMJous0i', '2025-05-20', 'https://www.google.com/shopping/product/16114892995639172922?gl=za', 607.50, NULL, NULL, 4, NULL),
(171, 'Head of Boykin Spaniel Stationery Set of 25', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSqWo23WWvh31bPc0-l1WT_6d98CPHnGUDt7OTwjwy7SoiZziL8qtq0wIVMeqQFjkZFJkCfVJVqSN-jVjYNu6v0_290OkMuY_Opqwtz4cR47M_AEO9p9-GlJw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5561260697631628851', 1001.78, NULL, NULL, 24, NULL),
(172, 'Vintage White Roses Stationery Paper Cute Letter Writing Paper for Home', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQ4hPPh8rkblV8RauOqywHsYdiTMGvD-PXZNsiAXcjlNhYnNYtAOR5Lh5CzOEgW-wFDqgxeTXHghFFyY5vftDvsBl0sAyZczAIRmXb-90PH1FjeARJvmfYaWA', '2025-05-20', 'https://www.google.com/shopping/product/8627879655852518731?gl=za', 399.00, NULL, NULL, 3, NULL),
(173, 'Bubblegumfringe Stationery Bundle - Neutral Elegance', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRqB-qb_vcCDXYnIWY_A30ztEPj4wtA909U8wCn_RyVi-akB3Hi2m2IupmwnjCzw6KB6gQu-isI_qeilRq2IRJYJj70_melMd-h38OfemlgguE3O3fQwL1z', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15218141108904893340', 390.00, NULL, NULL, 42, NULL),
(174, 'Personalised Sophisticated Stationery Set', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcSQQ8eujc-EqDJJ21QAmozuZ_CoCry_2q32NQhDMCuJXlWrbi5-96iy54ggOYpoIWbwR1MgeOcGvv-EdcXch4FMWrfTZasBBEwNNUnTlNpA1usuU_NgsroBeg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18303792873322991034', 359.95, NULL, NULL, 44, NULL),
(175, 'Kawaii Stationery Gift Set Blue', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQbpJPFe02k7JN22rMKTnnRiDfxuSva6gws1_c-w0LFVnux0-iyxWgF0vp-07Oq4Ck5EVmkuVzFHFNpRiGhq63rAKGuUyfiZTCQ8DBkmG3k', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:5505233498331852640', 874.09, NULL, NULL, 41, NULL),
(176, 'Custom Stationery', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQ4US3AEmyixtvU8oQAFlhEIttERhPxZeeJ06_MJR2UPMeFkNzFFa9fyru3p_yTed7fz_FouOmQOrjYAlL8Tuj7CKQfWo_mkGRX8LIqCniJacOQ2xideRIB', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8343328825600307163', 85.86, NULL, NULL, 43, NULL),
(177, 'STATIONERY SET ASSRTED ON CARD 6PC/CA', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRazhDNGUl5kTTbkLlYc0qnFQGrLGlNF76Fye2udUeLqfTcov2UxaXvbfVSC2GJcUbQOqxwwD976MGv0kQ6yQUZ0udAU0EsW0yu8fMBMZF6Bu_ZUnbSPNLv', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:17613249351969122471', 25.00, NULL, NULL, 46, NULL),
(178, 'Better Office Products Mini Stationery Set 100 Piece Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTR9AYXxgH1nz8CJqVVfnVlstDf_ahayb8pVvbW_w6g3V_Z_Cj5iraXLHpwlapIRHxa6O6EvnOg1yf5vH6tYRQpk816BqLmSt0itwLkW0E', '2025-05-20', 'https://www.google.com/shopping/product/9568615880255376202?gl=za', 403.00, NULL, NULL, 25, NULL),
(179, 'Variety Starter Stationary Set', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcRMm9Bw1GmcEks1o-_jxD2OuXYvOty19FQhFKbbbXau2KAF1BhFB38FnDJHlC9YvbqoDTwwYzT2vmWK4w-BP8l4k1aYvEsVTjpURJ3SzGP7T_qBzRVcWi3Mdz8', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:18144064197300663516', 95.00, NULL, NULL, 45, NULL),
(180, 'Unicorn Stationary Kit', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQLzxmbzxTTlYhRCfoLKBK6d9SHokUFiu6zs-phoat6iYNxBNb-04HnHZN_rukmcIyYK39XBTjIoC1Z2CjNsYTWPLnmPwOm4R94iOHoMaA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7879181748550404363', 220.00, NULL, NULL, 3, NULL),
(181, 'Personalised Swirly Stationery Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRbe3Ca2eYmfpCufsj7s3lttUB04NkKIdxJkryOoxBvuJkj89zd5QeEl1desd8tDMj7VgIGvMcDuEo2AXg18AIrvcF__tEGuv29JeaHgEfw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:9820202873098854040', 399.95, NULL, NULL, 44, NULL),
(182, 'Personalised Animal Print Stationery Set', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRZE072kzRFKovHt09eP81fiHZsiLwqZ_U1Ia9tO3YHjqg3smnK4ZATRiLHE4UlYLUgXtCKAkj2VCeANLE0tjLf1BdE7asE8HfUKLYdc3Eo1nLQRFMinq99QQ', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10659853743946605184', 229.95, NULL, NULL, 44, NULL),
(183, 'Hippie Zahra Stationery Pack', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcQXJKo4EgW_PrvIQEkDPveCQ1iz0iIy-_yZlQxMhTe83uVpJ0p-qjCE0z2GgfqYl6_3OfuNKI99CNI3AcsfSZQ7ogOjwEkDgKxJLLX6KpKDqkuoXlBLVjtHrw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15766318462166423487', 375.00, NULL, NULL, 43, NULL),
(184, 'Wildflower Garden Boxed Stationery', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQuxSHssB256cvggXSP4ApxDvzRLrW2V5OFLpBN49G2CKeHxWQM13GZxpAp4QULObErk_DxsVl3l4MYg34kLI1O1o4hL96G0YuqhmOZN65SJ4hZRnIv8ij2', '2025-05-20', 'https://www.google.com/shopping/product/16974502823907559262?gl=za', 236.60, NULL, NULL, 49, NULL),
(185, 'Flower Field Letter Writing Set', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcSBpzw6NfIAkDJaXbBfCVRgtM1W1zdH2pyU21RR7qmgubXfc3yXLRrDuPG67ZCJVRw-QceLrSZTJEUULbjBbVgLI_Hsy1Qn2SE4r-hlka8nV8C764Joo0Sk', '2025-05-20', 'https://www.google.com/shopping/product/5301669208780561149?gl=za', 279.00, NULL, NULL, 48, NULL),
(186, 'Sainsbury\'s Home Ombre Stationery Bundle', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTg6YPJw8V527UHZz-AZW4JNI6U7vxGyJTOIjRGs9Gphw8UW8E7MyPpS9fl2LKUO_FyQ9gVYkOF449sBQdmgIbE_Y9hqAv1Fl2DtN1bVBCd', '2025-05-20', 'https://www.google.com/shopping/product/1893568323304331015?gl=za', 313.80, NULL, NULL, 47, NULL),
(187, 'Rodeo Scrappy Stationery Pack', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQrANAI13GimLDtx1a2ONUJ2ZJpMzRQInLaAu3y7yWx6sPtN0waBF36qzgNxIhlva2u0Fs9Hc8eXdyEou6aOS_JoI5zClVv4-X4poB31LdpKOTIgqXdZWd-5w', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13462846364571376108', 375.00, NULL, NULL, 43, NULL),
(188, 'Unicorn Cosmic Galaxy Writing Set For Girls, 45 Piece Kids Stationary Set, Top Stationary Set For Writing Letters', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQD_lvkaTmAIZLas_7-FRCZy4DUYAIram_4GvCRC6ocn3_UyeyF3vekltlFAsodxhuRcm-6tALJJ1dAGQMM4rX6UWRLmNxNO4gyOfQUOEUZWt74x8nyyTywlg', '2025-05-20', 'https://www.google.com/shopping/product/7311285911024494044?gl=za', 1080.00, NULL, NULL, 25, NULL),
(189, 'Letter Writing Set- Kitten', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTGNNpJNABcS5aLvLGHqIBRu04Ooxk04wFX3mUkDw_URV1iyDgcXwRwRTIVoBo34GuChJiNzgSlB1PRv_oPfnZ48K_ah5nKJJBh-iOT_7XjGfwmd0gY_75Q', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:4741847516109002931', 49.00, NULL, NULL, 51, NULL),
(190, 'stationery shop', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcSp7Ym7CRc2NVgRob8ICla4a6gllYqu7AfgJl1UjIj1l3hSNRMP1PYZftrVvxBuypM1BJrsyMflPZiVty94HfSD_amsk9LapasBDR3a8Wd38MwujJwijFs4', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:1575917241656529370', 65.65, NULL, NULL, 32, NULL),
(191, 'Jumbo Stationery Set', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcS_RLc8St0gLFIUKzvfOsaIV-x2iuxV-2hhyrRSXDUHmlJ44sz1_gNQvHDwi6ea6BYgmJC9HMwEdtVelh3SnKXNamdUX5kLSBgZpW8yu5CQoRRB3Z16LSLj', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12676390501580958372', 36.84, NULL, NULL, 21, NULL),
(192, 'Stationery Variations:', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQ_p_JbwjofL0GL-vY-yfMYcA9OicnVhdf4y89vKgYRTquwedPz_UyzrI0pnPPOF29TPqROiS3AthvSVKrWtDPd7FFxtVmrQXl7xf_I9U0IYDxuJ6Kju30n', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:7737348041240208724', 66.93, NULL, NULL, 32, NULL),
(193, 'Paradise Flycatcher Collection - Stationery Bundle', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcTLZ4VVi33LHSuof04P40ljjIpgEY183xGF5Ft5JbCo7mUkbXc-6DdqMSZ2ZjxNHu8tkJ2DimuLvbYGRJl51E45X5GxbBxXZeCN3o97Rgxwg9VGsCM-Cd4i', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:8074445100717368332', 670.00, NULL, NULL, 50, NULL),
(194, 'Mr. Pen Stationery Writing Paper with Envelopes for Writing Letter, 30 Letter Writing Paper+18, Cute Stationary Set', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRuJlDqnP_BXVsO7Bhxdua-51BR4HY_KgL2gBA5-2dxIV1Lx-9bfkFTpyuhndxoOFHj2PyNEb0YGvRvsgvuGFtBPE5y1Q0HsToeoG3Sxy9V', '2025-05-20', 'https://www.google.com/shopping/product/2669805265368692412?gl=za', 250.56, NULL, NULL, 25, NULL),
(195, '90PCS Stationary Paper and Envelopes Set, writing paper stationery set,10 Different Style Cute Writing Stationery Paper Letter Set(60 stationery paper', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTWmbzrBGyaM4Vt_dh3sMEs-UweVzLJn9yLNAeWJOwjhFw2LWX_8OkQ3RaHRqa0vSQ5qEeUo-8YqQh4vNcGw54xZkeFf64ICPZS9lo_G5sg', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:12920213005500742127', 722.00, NULL, NULL, 29, NULL),
(196, '96 Parchment Stationary Paper Sheets Old Fashion Aged Classic Vintage Antique Writing', 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcRz8ZgEIdigh2fDlA4t4-JwRROR39GKoUvP83JOZ1K-Rwxx98W2rdWjXaD-IVSsz5VUjBZ6zLAJihSbQtKgBencR3ALQ4dHhFy2-TK6Rm9r6XOgigZlD8GD', '2025-05-20', 'https://www.google.com/shopping/product/11344553201993059384?gl=za', 897.00, NULL, NULL, 29, NULL),
(197, 'Stationary Paper And Envelopes Set, 48 Pcs Stationary Set For Women Cute Stationary Writing Stationery Paper With 16 Envelope - 32 Letter Paper 7.1', 'https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcRmWBWcArMvD2EhCuL9oCY69F9x4Rl67XlEKz2_WRa3mTF-7P-lWEvMEDHFPYA_NZWzTFr6IDoYICkfWOwHYPDNCqYpzdV8Y2yPxsMyVhng', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:15852360793791113062', 319.88, NULL, NULL, 25, NULL),
(198, 'Sakura Printable Stationary Bundle', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcSbqpQgiqxP3fDDadWYp10rCbrxAkLtUzvFlqX876ZTFOwrG5bBihgRWNWr9UusFvKLmU0_jfYZlg_oeArumX7uIaa-8ISp4CJ236N3d-au', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:11480540829925794608', 32.71, NULL, NULL, 52, NULL),
(199, 'Purple Agate Note Cards And Envelopes, Agate Stationary With Envelopes, Personalized Stationery For Women, Your Choice Of Colors And Quantity', 'https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcRHXv51yASPIGw-nKWYbMaaBbag5Ey2EtHfa2576CsJNIXqcTTKTnr1p_blgFe5NWGVASfK3pkmJ0X9-_QdQWv2_rMOGeGfbTo1170AGH6Rg9at9JKV5kyw', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:13275406220936042549', 901.46, NULL, NULL, 25, NULL),
(200, 'Rainbow Starter Stationery Pack Primary School Pack / No / Yes', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQbYlVszT2r6KZ_IQBLZdqybhpcGWQXvIXkaHeQJjEb67KRb7zmRSTmr2VFKK7h9mlXk4NqndIVzzDvLXy4Z0w9RQOSDNDEmfm5msvjzguA', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:2190287102616544293', 254.00, NULL, NULL, 53, NULL),
(201, 'Mini Stationery Set Of 100, Japanese Stationery Paper 50 Lined Sheets 50 Matching Envelopes, 5.5 X 8.25 Inch, 9 Designs, Double Sided Printing, One |', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcTkzDGG8g_zfjh6i2s1H6LFOBmok0SyyWTw6X-AIa2ygtVmqMKCCL9lFqRlya-oVSfHiMgs3rKycPRC1fsdrnPkjPAuIVGOOszsHq0C6j54', '2025-05-20', 'https://www.google.com/shopping/product/1?gl=za&prds=pid:10875642309455433710', 809.52, NULL, NULL, 25, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL CHECK (`rating` between 1 and 5),
  `date` date DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `name`, `type`, `url`) VALUES
(1, 'Game', NULL, NULL),
(2, 'Toys R Us', NULL, NULL),
(3, 'takealot.com', NULL, NULL),
(4, 'The Papery', NULL, NULL),
(5, 'Cotton On', NULL, NULL),
(7, 'Makro', NULL, NULL),
(8, 'Three6ixty Marketing & Branding', NULL, NULL),
(9, 'GeeWiz', NULL, NULL),
(10, 'Brandability', NULL, NULL),
(11, 'Space Camp', NULL, NULL),
(12, 'Bookmall.co.za', NULL, NULL),
(13, 'Gift Mania SA', NULL, NULL),
(14, 'Furbish', NULL, NULL),
(15, 'Shop n Scrap', NULL, NULL),
(16, 'Amazon.co.za', NULL, NULL),
(17, 'Scribble and Scratch', NULL, NULL),
(18, 'Love from Me', NULL, NULL),
(19, 'Creative Brands', NULL, NULL),
(20, 'Good Golly', NULL, NULL),
(21, 'pgifts.co.za', NULL, NULL),
(22, 'Just Labels', NULL, NULL),
(23, 'Write GEAR', NULL, NULL),
(24, 'Social Paper Company', NULL, NULL),
(25, 'Ubuy', NULL, NULL),
(26, 'Wantitall', NULL, NULL),
(27, 'Likhaan', NULL, NULL),
(28, 'eBay - grandeagleretail', NULL, NULL),
(29, 'Import It All', NULL, NULL),
(30, 'Value City Wholesalers CC', NULL, NULL),
(31, 'Art Shop', NULL, NULL),
(32, 'Gadgetseek', NULL, NULL),
(33, 'amuse', NULL, NULL),
(34, 'eBay - natshp-24', NULL, NULL),
(35, 'RM Signs', NULL, NULL),
(36, 'eBay - ranotlior.08', NULL, NULL),
(37, 'To Be Gift Boxes', NULL, NULL),
(38, 'Hyperli', NULL, NULL),
(39, 'Ribbens Office National', NULL, NULL),
(40, 'diabco', NULL, NULL),
(41, 'KoreKawaii.com', NULL, NULL),
(42, 'Bubblegumfringe', NULL, NULL),
(43, 'Valenna', NULL, NULL),
(44, 'NetFlorist', NULL, NULL),
(45, 'Happy Harvesting', NULL, NULL),
(46, 'Evermore ZA', NULL, NULL),
(47, 'McGrocer', NULL, NULL),
(48, 'Pause Room', NULL, NULL),
(49, 'Biblio.com - City Lights Bookstore', NULL, NULL),
(50, 'Claudi Lourens Creative Studio', NULL, NULL),
(51, 'Bob Shop', NULL, NULL),
(52, 'Kaito Japan Design', NULL, NULL),
(53, 'Coral Designs', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_owner`
--

CREATE TABLE `store_owner` (
  `user_id` int(11) NOT NULL,
  `registration_no` varchar(255) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `theme` enum('light','dark') NOT NULL DEFAULT 'light',
  `min_price` decimal(10,2) DEFAULT NULL,
  `max_price` decimal(10,2) DEFAULT NULL,
  `apiKey` varchar(255) DEFAULT NULL,
  `user_type` enum('customer','store_owner','admin','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`),
  ADD KEY `store_ibfk_1` (`store_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `store_owner`
--
ALTER TABLE `store_owner`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `brand`
--
ALTER TABLE `brand`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`brand_id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `store_owner`
--
ALTER TABLE `store_owner`
  ADD CONSTRAINT `store_owner_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
