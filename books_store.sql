-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 août 2021 à 04:08
-- Version du serveur :  10.4.19-MariaDB
-- Version de PHP : 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `books_store`
--

-- --------------------------------------------------------

--
-- Structure de la table `aboutus`
--

CREATE TABLE `aboutus` (
  `shop_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `phone` varchar(50) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `face` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `admin_email` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `aboutus`
--

INSERT INTO `aboutus` (`shop_id`, `name`, `text`, `phone`, `insta`, `face`, `email`, `address`, `admin_email`) VALUES
(1, '404', '404 Gallery is a Decoration Gallery. We are an Egyptian team of interior and graphic designers ... Our pieces made especially for you.. We made it with love and we hope it\'ll make you feel happy every time you see it.', '+201033778585 ', 'https://www.instagram.com/mobinmdshl/', 'https://twitter.com/mobinmdshl', 'decorationgallery404@gmail.com', '6 October, egypt', 'decorationgallery404@gmail.com, 404gallerydina@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`) VALUES
(7, 22),
(9, 12),
(10, 13),
(11, 14);

-- --------------------------------------------------------

--
-- Structure de la table `book_borrowings`
--

CREATE TABLE `book_borrowings` (
  `book_borrowing_id` int(11) NOT NULL,
  `borrowing_date` date NOT NULL,
  `borrowing_expected_return_date` date DEFAULT NULL,
  `borrowing_return_date` date DEFAULT NULL,
  `borrowing_deposit_tax` varchar(20) NOT NULL DEFAULT '0',
  `product_qty` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrowing_status` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `book_borrowings`
--

INSERT INTO `book_borrowings` (`book_borrowing_id`, `borrowing_date`, `borrowing_expected_return_date`, `borrowing_return_date`, `borrowing_deposit_tax`, `product_qty`, `product_id`, `user_id`, `borrowing_status`, `created_at`, `updated_at`) VALUES
(13, '2021-08-13', '2021-08-15', NULL, '100', 2, 72, 22, 'returned', '2021-08-13 00:17:04', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` int(11) NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `product_qty`, `date`, `status`, `note`) VALUES
(283, 22, 69, 1, '2021-08-10 23:20:35', 1, ''),
(284, 22, 69, 2, '2021-08-11 00:52:20', 1, ''),
(285, 22, 69, 1, '2021-08-13 01:52:21', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(50) NOT NULL,
  `cat_image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_image`) VALUES
(1, 'SOFTWARE', 'https://imgv2-2-f.scribdassets.com/img/word_document/239088050/original/216x287/9d0d463c1c/1579763660?v=1'),
(2, 'ECONOMIC', 'https://m.media-amazon.com/images/I/41YhgR+0tPL._AC_UL320_.jpg'),
(3, 'HISTORICAL', 'https://m.timesofindia.com/img/78055116/Master.jpg'),
(4, 'POLITICAL', 'localhost/PHP-/api/catimages/POLITICAL.jpg'),
(5, 'PSYCHOLOGY', 'https://images-platform.99static.com//jebug2CwIGbPujJ7Xvyz6fTdI94=/0x6:2029x2035/fit-in/500x500/99designs-contests-attachments/92/92795/attachment_92795314'),
(14, 'ROMANTIC', 'https://images.unsplash.com/photo-1509873889234-3cdbfe2e6740?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MzZ8fGNvdXBsZXxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&w=1000&q=80');

-- --------------------------------------------------------

--
-- Structure de la table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_type` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `discount` int(11) NOT NULL DEFAULT 2,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `member`
--

INSERT INTO `member` (`id`, `user_id`, `member_type`, `status`, `discount`, `added_date`) VALUES
(1, 22, 0, 0, 2, '2021-08-08 04:11:30');

-- --------------------------------------------------------

--
-- Structure de la table `membershipcat`
--

CREATE TABLE `membershipcat` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `newarrivals`
--

CREATE TABLE `newarrivals` (
  `new_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `newarrivals`
--

INSERT INTO `newarrivals` (`new_id`, `product_id`) VALUES
(21, 69),
(23, 70),
(22, 72),
(11, 74);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `order_num` int(11) NOT NULL,
  `order_date` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` int(20) NOT NULL,
  `borrowing_price` varchar(50) NOT NULL,
  `product_image` varchar(500) NOT NULL,
  `product_description` varchar(900) NOT NULL,
  `product_copies` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`product_id`, `cat_id`, `product_name`, `product_price`, `borrowing_price`, `product_image`, `product_description`, `product_copies`) VALUES
(65, 14, 'Frames set F1 ', 1550, '1550', 'https://images.unsplash.com/photo-1619631428089-813ef0fca73b?ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw4fHx8ZW58MHx8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60', 'Wooden shelf and wooden frames with glass and print pictures ', 3),
(69, 1, 'C++', 335, '335', 'https://imgv2-1-f.scribdassets.com/img/word_document/382269418/original/216x287/7723ccfab5/1579150149?v=1', 'MDF 18 m and glass 3 m', -5),
(70, 1, 'JAVA', 150, '150', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_WsrU23B6S5Xsm5ZK0KGDTvuTX-Ofu1yXb-uNQMuUdqRA8oSDbIrs2r4BpVxnxOUSC7q9FSuU&usqp=CAc', 'Java is a set of computer software and specifications developed by James Gosling at Sun Microsystems, which was later acquired by the Oracle Corporation, that provides a system for developing application software and deploying it in a cross-platform computing environment', 1),
(72, 1, 'JAVASCRIPT', 475, '475', 'https://imgv2-1-f.scribdassets.com/img/word_document/376443107/original/216x287/6d3d3ec402/1617227515?v=1', 'MDF 30 m and glass 4m', 1),
(73, 1, 'HTML5', 675, '675', 'https://www.templatemonster.com/blog/wp-content/uploads/2011/01/Sams-Teach-Yourself-HTML5-in-10-Minutes.jpg', 'MDF 30 m and glass 4 m', 0),
(74, 1, 'PYTHON', 775, '775', 'https://images-na.ssl-images-amazon.com/images/I/61gBVmFtNpL.jpg', 'MDF 18 m and glass 4 m', 0),
(75, 1, 'FULTTER', 1850, '1850', 'https://images-na.ssl-images-amazon.com/images/I/51AiHWxOzcL._SX396_BO1,204,203,200_.jpg', 'MDF 30 m and glass 4 m', 1),
(76, 1, 'SWIFT', 775, '775', 'https://images-eu.ssl-images-amazon.com/images/I/51h3z9rRy9L._AC_UL600_SR480,600_.jpg', 'MDF 18 m and glass 4 m', 1),
(77, 1, 'KOTLIN', 775, '775', 'https://images-na.ssl-images-amazon.com/images/I/418fafvbxeL.jpg', 'MDF 18 m and glass 4 m', 1),
(78, 1, 'PHP', 775, '775', 'https://image.isu.pub/130208084308-53c3e7ec9deb42a2b74a7f510025ca57/jpg/page_1.jpg', 'MDF 18 m and glass 4 m', 1),
(79, 1, 'ASP.NET', 875, '875', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJUtPPaSLWWQQ-mjtMuLARgVWcVUcFeeYRjZ6DoMSlBN0cHWExKZBS22J4DA&usqp=CAc', 'MDF 18 m and glass 4 m', 1),
(83, 1, 'JQUERY', 900, '900', 'https://m.media-amazon.com/images/I/41uM5YO8VUL.jpg', 'MDF 30 m and glass 4 m', 1),
(86, 1, 'BOOTSTRAP', 2500, '2500', 'https://imgv2-1-f.scribdassets.com/img/word_document/382268865/original/133741089b/1618721328?v=1', 'MDF 30 m.glass 4 m and lamp 3w', 1),
(88, 1, 'CSS', 1400, '1400', 'https://m.media-amazon.com/images/I/41HStLY9oCL._SL500_.jpg', 'MDF 18 m and glass 3 m', 1),
(91, 2, 'Shelves Su-P1', 470, '470', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14m and 6m ', 1),
(93, 2, 'Shelves KP-A2', 475, '475', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14 m and 6m', 1),
(94, 2, 'Shelves HA-L3', 475, '475', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14 m and 6 m', 1),
(95, 2, 'Shelves CA-T4', 475, '475', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14 m and 6 m', 1),
(96, 2, 'Shelves BA-T5', 440, '440', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14 m', 1),
(97, 2, 'Shelves BU-F5', 200, '200', 'https://st.depositphotos.com/1000441/1359/i/600/depositphotos_13590596-stock-photo-bookshelf.jpg', 'MDF 14 m', 1),
(98, 2, 'Shelves OW-L6', 520, '520', 'https://msi-cs.com/404gallery/api/productimages/Shelves OW-L6.jpg', 'MDF 14 m', 1),
(99, 2, 'Shelves DO-L6', 600, '600', 'https://msi-cs.com/404gallery/api/productimages/Shelves DO-L6.jpg', 'MDF 14 m', 1),
(101, 2, 'Shelves EN-T8', 475, '475', 'https://msi-cs.com/404gallery/api/productimages/Shelves EN-T8.jpg', 'MDF 14 m', 1),
(102, 2, 'Shelves SH-E10', 250, '250', 'https://msi-cs.com/404gallery/api/productimages/Shelves SH-E10.jpg', 'MDF 14 M', 1),
(103, 2, 'Shelves EN-T11', 450, '450', 'https://msi-cs.com/404gallery/api/productimages/Shelves EN-T11.jpg', 'MDF 14 m and 35m', 1),
(104, 2, 'shelves MG-12', 650, '650', 'https://msi-cs.com/404gallery/api/productimages/shelves MG-12.jpg', 'MDF 14 m', 1),
(105, 3, 'Frame  30M', 120, '120', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 30 m', 1),
(106, 3, 'Frames 30M2', 220, '220', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 30 m', 1),
(107, 3, 'Frames 18M1', 170, '170', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 18 m and glass 3m', 1),
(108, 3, 'Frames PHO1', 180, '180', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 10 m and photos', 1),
(110, 3, 'Frames 01', 185, '185', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 14 m . glass and photos', 1),
(111, 3, 'Frames PR-UV 1', 175, '175', 'http://pngimg.com/uploads/picture_frame/picture_frame_PNG215.png', 'MDF 10 m and Print UV photo', 1),
(112, 4, 'Table A1', 2900, '2900', 'https://msi-cs.com/404gallery/api/productimages/Table A1.jpg', 'MDF 10 m and beech wood ', 1),
(113, 4, 'Table A2', 1290, '1290', 'https://msi-cs.com/404gallery/api/productimages/Table A2.jpg', 'MDF 18 m', 1),
(114, 4, 'Table A3', 1500, '1500', 'https://msi-cs.com/404gallery/api/productimages/Table A3.jpg', 'MDF 18 m', 1),
(115, 4, 'Table TV-U1', 3750, '3750', 'https://msi-cs.com/404gallery/api/productimages/Table TV-U1.jpg', 'Countertops and beechwood', 1),
(117, 4, 'Table T5', 2700, '2700', 'https://msi-cs.com/404gallery/api/productimages/Table T5.jpg', 'Countertops', 1),
(118, 4, 'Table T6', 2950, '2950', 'https://msi-cs.com/404gallery/api/productimages/Table T6.jpg', 'beechwood and Manual drawing', 1),
(119, 5, 'Name N1', 170, '170', 'https://msi-cs.com/404gallery/api/productimages/Name N1.jpg', 'MDF 14 m 45 cm', 1),
(120, 5, 'Name N2', 260, '260', 'https://msi-cs.com/404gallery/api/productimages/Name N2.jpg', 'MDF 14 m 65cm ', 1),
(121, 5, 'Logo L1', 900, '900', 'https://msi-cs.com/404gallery/api/productimages/Logo L1.jpg', 'MDF 120cm 24m', 1),
(122, 5, 'Name N5', 250, '250', 'https://msi-cs.com/404gallery/api/productimages/Name N5.jpg', 'MDF 14 m 65cm', 1),
(124, 5, 'Name N7', 280, '280', 'https://msi-cs.com/404gallery/api/productimages/Name N7.jpg', 'MDF 14 m 80cm', 1),
(125, 5, 'Logo L8', 450, '450', 'https://msi-cs.com/404gallery/api/productimages/Logo L8.jpg', 'MDF 14 m', 1),
(126, 14, 'Frame sets F2', 1750, '1750', 'https://cdn.bagnodesignlondon.com/media/catalog/product/cache/25/small_image/500x/9df78eab33525d08d6e5fb8d27136e95/V/A/VAC-BT-0060-2271-S.jpg', 'wooden frames with glass and print pictures', 1),
(127, 14, 'Frames sets F3', 1250, '1250', 'https://msi-cs.com/404gallery/api/productimages/Frames sets F3.jpg', 'wooden frames with glass and print pictures', 1),
(129, 14, 'Frames sets F4', 1250, '1250', 'https://msi-cs.com/404gallery/api/productimages/Frames sets F4.jpg', ' wooden frames with glass and print pictures', 1),
(130, 14, 'Frames sets F5', 1000, '1000', 'https://msi-cs.com/404gallery/api/productimages/Frames sets F5.jpg', 'Wooden shelf and wooden frames with glass and print pictures', 1),
(131, 14, 'Frames sets Co-6', 1000, '1000', 'https://msi-cs.com/404gallery/api/productimages/Frames sets Co-6.jpg', 'Wooden shelf and wooden frames with glass and print pictures', 1),
(132, 14, 'Frames sets Logo L7', 2800, '2800', 'https://msi-cs.com/404gallery/api/productimages/Frames sets Logo L7.jpg', 'wooden frames with glass and print pictures', 1),
(133, 2, 'nothingsssssssssss', 1111, '1111', 'http://localhost/PHP-Slim-Restful/api/productimages/nothingsssssssssss.jpg', 'sdasdasdasdasdasds', 1),
(134, 4, 'hghghg', 10, '10', 'http://localhost/PHP-Slim-Restful/api/productimages/hghghg.jpg', 'bnbnbnnbnbn', 1),
(145, 4, 'nhjhjjhjhjhj', 10, '1522', 'http://localhost/PHP-Slim-Restful/api/productimages/nhjhjjhjhjhj.jpg', 'bnjj', 1),
(146, 4, 'nhjhjjhjhjhj', 10, '1522', 'http://localhost/PHP-Slim-Restful/api/productimages/nhjhjjhjhjhj.jpg', 'bnjj', 1),
(147, 5, 'cvcv', 10, '5', 'http://localhost/PHP-Slim-Restful/api/productimages/cvcv.jpg', 'dfggfgf', 1);

-- --------------------------------------------------------

--
-- Structure de la table `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `imagepath` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `slides`
--

INSERT INTO `slides` (`id`, `name`, `imagepath`) VALUES
(24, '2', 'https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxleHBsb3JlLWZlZWR8Mnx8fGVufDB8fHx8&w=1000&q=80'),
(26, '3', 'https://thevideoink.com/wp-content/uploads/2020/03/books-1024x576.jpg'),
(28, '4', 'https://img.pngio.com/educational-book-printing-book-printing-amba-offset-learning-books-png-398_365.png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(20) NOT NULL,
  `name` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(300) NOT NULL,
  `address` varchar(300) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `zip` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `code` varchar(25) NOT NULL,
  `user_photo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `phone`, `email`, `address`, `user_type`, `zip`, `city`, `country`, `code`, `user_photo`) VALUES
(12, 'Ibrahim Soliman Mohamed ', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '0182032371', 'ibrahimsoliman97@gmail.com', '132 penang, gucvu', 'user', '14255', 'Penang', 'Egypt', '+20', 'https://msi-cs.com/404gallery/api/usersimages/ibrahimsoliman97@gmail.com.jpg'),
(13, 'abdallah soliman', '10b1815c36f6d4e61f8c73c757298abf815b3d5a6d8622e905dbe00a2b56be2a', '1009279911', 'any@gmail.com', 'bbu', 'user', '75075', 'giza', 'Egypt', '+20', 'https://msi-cs.com/404gallery/api/usersimages/any@gmail.com.jpg'),
(16, 'abdelkader fikry', 'a53307e386a8ce1496c242dc21e17f21b13b27d76545e9cdfa72414c1eb3331d', '01009567675', 'abdelkaderfikry66@gmail.com', '????? ???????', 'user', '100', '?????', 'Egypt', '+20', ''),
(17, 'amin nabeel', '873e0f3e1edceda360fb4b75801fa80f70dfbadab3869c077395b253a84ed803', '01151115836', 'amin@gmail.com', 'bukit beruang utama apartment b2-7-7', 'user', '75075', 'melaka', 'Malaysia', '+20', ''),
(18, 'Abdalla Soliman Mohammed', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '1121115836', 'any11@gmail.com', 'ug', 'user', '75075', 'melaka', 'Malaysia', '+60', ''),
(20, 'jk;lkjhdsfg dsfdlkj', '8773b657d9c96bda24371496a775f5230edb40becb411f03875af81e70e71986', '2934758234', 'sldkfj@sdfljk.com', '9823475', 'user', '234', 'asdf', 'Egypt', '+20', ''),
(21, 'dsfsd ', '15e2b0d3c33891ebb0f1ef609ec419420c20e320ce94c65fbc8c3312448eb225', '0563646518', 'dfsc@gmail.com', 'adfasdsda', 'user', '1223', 'asdasd', 'Egypt', '+20', ''),
(22, 'muaadh ', '646742be0a8e119bd215293eb96ba664aea33fa23867caa9f825afe5a7b17306', '0563646518', 'mobinmdshl@gmail.com', 'madenah', 'user', '1', 'makkah', 'Egypt', '+20', 'http://localhost/PHP-Slim-Restful/api/usersimages/mobinmdshl@gmail.com.jpg'),
(23, 'salem ', 'd8465f335456d5d8171220b527bc02906be61a9c5a17344a0690f08ae1b92e90', '0508492488', 'qzalalrashd@gmail.com', 'melaka', 'user', '12', 'kuala', 'Egypt', '+20', 'http://localhost/PHP-Slim-Restful/api/usersimages/qzalalrashd@gmail.com.jpg'),
(25, 'anwer ', '84d9c4b849506b6d8f8075a9000e7e0a254be71060ea889fad3c88395988f4fc', '05636465188', 'mobinmdshlll@gmail.com', 'melaka ', 'user', '2', 'sds', 'malaysia', '+01', ''),
(26, 'mohammed ', '84d9c4b849506b6d8f8075a9000e7e0a254be71060ea889fad3c88395988f4fc', '0563646511', 'qzalalrashdd@gmail.com', 'sass', 'user', '12', 'sadasd', 'malaysia', '+01', 'Restful/api/usersimages/mobinmdshl@gmail.com.jpg'),
(27, 'anwer ', '9cc512ec26d63ae38bce0e9e479fcc1a6e417905be369e7cc8c6d4d962b0fdcb', '1111111111', 'mobinmdshll@gmail.com', 'dsdsf', 'user', '12', 'ewrw', 'malaysia', '+01', ''),
(28, 'abdulAziz', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '1231234456', 'tester@books.com', 'asdflkjas', 'user', '2132', 'ABc', 'malaysia', '+01', 'http://localhost/PHP-Slim-Restful/api/usersimages/tester@books.com.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `wish_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`wish_id`, `product_id`, `user_id`) VALUES
(29, 65, 13),
(30, 69, 13),
(37, 74, 23),
(49, 73, 25),
(50, 70, 27),
(51, 69, 28),
(52, 120, 28);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`shop_id`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Index pour la table `book_borrowings`
--
ALTER TABLE `book_borrowings`
  ADD PRIMARY KEY (`book_borrowing_id`),
  ADD UNIQUE KEY `borrowing_date` (`borrowing_date`,`product_id`,`user_id`);

--
-- Index pour la table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Index pour la table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membershipcat`
--
ALTER TABLE `membershipcat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `newarrivals`
--
ALTER TABLE `newarrivals`
  ADD PRIMARY KEY (`new_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_num`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Index pour la table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wish_id`),
  ADD UNIQUE KEY `wish_id` (`wish_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `book_borrowings`
--
ALTER TABLE `book_borrowings`
  MODIFY `book_borrowing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `membershipcat`
--
ALTER TABLE `membershipcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newarrivals`
--
ALTER TABLE `newarrivals`
  MODIFY `new_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_num` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT pour la table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `wish_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `newarrivals`
--
ALTER TABLE `newarrivals`
  ADD CONSTRAINT `newarrivals_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`);

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
