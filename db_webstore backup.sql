/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `Cart` (
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `units_in_cart` int DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `Cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  CONSTRAINT `Cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `Order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `OrderDetails` (
  `product_id` int DEFAULT NULL,
  `total` int NOT NULL,
  `user_id` int DEFAULT NULL,
  KEY `product_id` (`product_id`),
  KEY `FK_ORDERDETAILS` (`user_id`),
  CONSTRAINT `FK_ORDERDETAILS` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  CONSTRAINT `OrderDetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `Product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `units_in_storage` int DEFAULT '0',
  `dimension` varchar(255) DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `image_path` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ProductFavorite` (
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `ProductFavorite_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  CONSTRAINT `ProductFavorite_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `ProductRating` (
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int NOT NULL,
  `would_buy_again` char(1) DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `ProductRating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  CONSTRAINT `ProductRating_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `User` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `pass_word` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `webstoreBalance` int DEFAULT '500',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Cart` (`user_id`, `product_id`, `units_in_cart`) VALUES
(31, 10, NULL);
INSERT INTO `Cart` (`user_id`, `product_id`, `units_in_cart`) VALUES
(31, 11, NULL);
INSERT INTO `Cart` (`user_id`, `product_id`, `units_in_cart`) VALUES
(35, 1, NULL);
INSERT INTO `Cart` (`user_id`, `product_id`, `units_in_cart`) VALUES
(9, 16, NULL),
(48, 10, NULL),
(52, 33, NULL);



INSERT INTO `OrderDetails` (`product_id`, `total`, `user_id`) VALUES
(4, 120, 8);
INSERT INTO `OrderDetails` (`product_id`, `total`, `user_id`) VALUES
(14, 29, 31);
INSERT INTO `OrderDetails` (`product_id`, `total`, `user_id`) VALUES
(13, 38, 31);
INSERT INTO `OrderDetails` (`product_id`, `total`, `user_id`) VALUES
(15, 95, 31),
(15, 95, 31),
(15, 95, 31),
(12, 99, 31),
(6, 46, 8),
(30, 16, 8),
(4, 120, 9),
(16, 120, 9),
(14, 29, 9),
(14, 29, 9),
(15, 95, 43),
(27, 25, 48),
(17, 39, 48),
(4, 120, 50),
(12, 99, 51),
(5, 49, 52),
(29, 100, 51),
(4, 120, 53);

INSERT INTO `Product` (`id`, `name`, `price`, `weight`, `units_in_storage`, `dimension`, `description`, `image_path`, `category`, `brand`) VALUES
(1, 'Uliptz Wireless', '22', '6.3', 1, '5.7 x 4.5 x 3.1 inches', 'HiFi Sound & Deep Bass, 65 Hours Playtime, 6 EQ Sound Modes, Bluetooth 5.2, Ultra-Comfortable Over Ear Headphones\r\nJust 1-3 seconds to quickly pair Bluetooth.\r\nBluetooth 5.2 version makes sure a fast and stable connection, no delay and no caton.\r\nWith a memory function, just turn on the wireless headphones to automatically connect for the next time.\r\nMemory foam ear-cups filter out more than 50% of ambient sound, making you immerse yourself in the music world.\r\n6 EQ modes for balanced sound/deep bass sound/crisp treble sound by switching the EQ button. EQ sound mode extended frequency range for a heightened listening experience.', 'https://m.media-amazon.com/images/I/91EtroVo7FL._AC_SL1500_.jpg', 'Electronics', 'Uliptz');
INSERT INTO `Product` (`id`, `name`, `price`, `weight`, `units_in_storage`, `dimension`, `description`, `image_path`, `category`, `brand`) VALUES
(2, 'RORSOU R10', '17', '7.6', 1, '7.09 x 3.15 x 7.87 inches', 'About this item\r\nHi-fi Stereo Sound:The High-fidelity stereo sound benefits from the 40 mm audio driver, which deliver exceptionally clear sound with full dynamic range and rich bass and crisp mids and you can enjoy gorgeous deep bass / Hi-Fi stereo sound feast. Just lose yourself in the music.\r\nUltra-soft ear cushions and padded headband provide you a fatigue-free listening experience even wearing these on-ear headphones during a long session. Adjustable slider helps you achieve the perfect fit without constraint. You can easily use these stereo headphones for your workouts, job commute or just for listening at home.', 'https://m.media-amazon.com/images/I/61z-rTknwVS._AC_SL1500_.jpg', 'Electronics', 'RORSOU ');
INSERT INTO `Product` (`id`, `name`, `price`, `weight`, `units_in_storage`, `dimension`, `description`, `image_path`, `category`, `brand`) VALUES
(3, 'iJoy', '25', '9', 1, '7 x 3 x 8 inches', 'Superior Comfort: Our newest iJoy Ultra wireless headphones feature exceptional padding on both the ear cushions and headband. Wear our bluetooth over the ear headphones for hours without any strain\r\nWireless over the ear headphones: Block out the world and immerse yourself in your music. Our over ear bluetooth headphones are designed to fully dull external noise so you can kick back, relax and forget about everything else.', 'https://m.media-amazon.com/images/I/51V2n790r8L.__AC_SX300_SY300_QL70_FMwebp_.jpg', 'Electronics', 'iJoy ');
INSERT INTO `Product` (`id`, `name`, `price`, `weight`, `units_in_storage`, `dimension`, `description`, `image_path`, `category`, `brand`) VALUES
(4, 'Anker', '120', '5', 2, '10 x 4 x 8 inches', 'ASTONISHING SOUND: Breathtaking stereo sound with deep bass is delivered with exceptional clarity and zero distortion by two high-sensitivity drivers and a patented bass port.', 'https://m.media-amazon.com/images/I/61y+b4M0RZL._AC_SL1200_.jpg', 'Electronics', 'Anker'),
(5, 'Echho', '49', '3', 2, '4 x 5 x 6 inches', 'Rich, detailed sound that automatically adapts to any room. Supports lossless HD audio available on select streaming services such as Amazon Music HD.', 'https://m.media-amazon.com/images/I/61y4J2vTwFL._AC_SL1000_.jpg', 'Electronics', 'Amazon'),
(6, 'Watch', '46', '1', 0, '1 x 2 x 5 inches', 'Access key Halo health metrics like heart rate, Activity points, Sleep score, and on-demand blood oxygen levels on the Halo View color touch display.', 'https://m.media-amazon.com/images/I/51UEWknqw1L._AC_SL1000_.jpg', 'Electronics', 'Halo'),
(7, 'AUTOMET Shirt ', '40', '1', 0, '15.47 x 11.57 x 2.17 inches; 1.04 Pounds', 'Fabric: 55% Nylon/45% Spandex\r\nImported\r\nButton closure\r\nMachine Wash\r\n★Material: Made of high quality 55% Nylon/ 45% Spandex fabric, skin-friendly, soft and comfortable to wear, will keep your warm and fashion style in fall,spring and cold winter.\r\n★Feature:It features soft fuzzy fabric, front button closure , and midweight feel for keeping warm all day. It\'ll be the prefect addition to your wardrobe.Two buttoned chest pockets / long sleeves with button cuffs / plaid pattern / loose fit shacket / collared.This shacket is a must have in your closet!\r\n★Match: You can pair this shirts with variety of tank tops, cami, bodysuit , jeans, denim shorts, jeggings, sneakers or boots to complete casual look that\'s easy to love all season long.', 'https://m.media-amazon.com/images/I/61wJffyZzeL._AC_UY550_.jpg', 'Fashion', 'Automet'),
(8, 'Chain Necklace', '15', '1', 0, '3.8 x 3 x 1.2 inches; 2.1 Ounces', 'Main Material:link chain,crystal,wood\r\nSize:necklace length is 37.8 inch, the weight is 2.15 oz\r\nOccasion: Casual, Party, Prom, Class, Wedding, Bridal, Office\r\nNickel and Lead free. Conform California proposition 65.\r\nComes in a gift box. Ideal gift for your girl, love and friend', 'https://m.media-amazon.com/images/I/71T7cY34gVL._AC_UX569_.jpg', 'Fashion', 'BOCAR 2'),
(9, 'Slim Wallet ', '26', '0.5', 0, '5.7 x 5.5 x 2.1 inches', 'Carbon Leather\r\nLeather lining\r\nbifold closure\r\nSlim Wallet for Men - RUNBOX large capacity wallets offer 12+ cards pockets and 1 bill compartment to meet everyday carry needs, the money pocket help comfortably store up to 15 bills, banknotes, the thin bifold wallet is ideal for carrying business cards, credit and debit cards. Comfortable in the front and rear pockets.\r\nRfid Blocking Protection - Our rfid mens wallets are equipped with advanced RFID security technology, unique metal composites, Your credit cards, debit cards, driving license and ID cards will be protected effectively. Block the 13.56 MHz band and protect against data theft by RFID scanners.\r\nMore Durable & Stronger - Made with the finest vegan leather material and stitched with precision and detail. Even if you friction it many times, it will not be easily damaged. The first class craftsmanship, soft and comfortable, the leather gives a premium look while making it durable.\r\nA Great Present - This men slim wallet comes in nice gift box packaging, is a perfect present for birthday, anniversaries, Fathers Day, Christmas or just because, so you can easily gift it to your bf, hubby, dad, friends, co-workers.\r\nLifetime Guarantee - We attached great importance to the quality of the bifold wallet and provide a lifetime warranty. Should you have any quality problems with RUNBOX wallets, you can contact us and get a 100% refund.', 'https://m.media-amazon.com/images/I/81ylcEZZGZL._AC_UX569_.jpg', 'Fashion', 'RUNBOX '),
(10, 'Wassmin Tumbler ', '25', '1.9', 0, '3.25\"W x 6.75\"H', 'Please note that the gem tumblers attached on the product are flat printing which are drawn realistically. They are neither 3D print nor real stones/gems.\r\nOptional Sizes And Color As You Wish: We have both 20 or 30 oz with with a variety of colors. Our personalized tumbler fits most of the cup holders and is perfect for use anywhere - home, school, office, workplace or outdoor activities.\r\nVacuum Insulation Technology: Each Wassmin tumbler has double-walled, vacuum insulated design, which helps keep your favorite beverage cold & hot for hours.\r\nConstructed Of Stainless Steel: Known for its strength and purity, our tumbler won’t break, rust, retain or transfer flavors. Therefore, our personalized tumbler cups allow you to drink without worrying about rust stains or metal taste.\r\nShatterproof And Durable: Each tumbler comes with an Anti-Leak. Our Skinny Tumbler\'s lid is designed to be leak-resistant to prevent any major spills.', 'https://m.media-amazon.com/images/I/61ta0iUqJML._AC_SX569_.jpg', 'Tumblers', 'Wassmin '),
(11, 'Kodrine Tumbler', '26', '2.05', 4, '2.9\"W x 7.4\"H', 'Natural bamboo lid. Made of food grade silicone which is a non-toxic type of silicone that does not contain any chemical fillers or byproducts, making it safe for use with food. Silicon sleeves for better grip', 'https://m.media-amazon.com/images/I/71+Q0KukM5L._AC_UL640_QL65_.jpg', 'Tumblers', 'Kodrine '),
(12, 'Beats by Dr. Dre', '99', '4.5', 0, '2.83 x 2.01 x 1 inches', 'Custom acoustic platform delivers powerful, balanced sound. Control your sound with two distinct listening modes: Active Noise Cancelling (ANC) and Transparency mode. Up to 8 hours of listening time (up to 24 hours combined with pocket-sized charging case)', 'https://m.media-amazon.com/images/I/41m81cYUgpL._AC_UY436_QL65_.jpg', 'Electronics', 'Beats by Dr. Dre'),
(13, 'Tote bag', '38', '5.61', 2, '9.06 x 6.3 x 2.36 inches', 'Durable fabric with large main compartment. Training bag with  woven polyester lining.', 'https://m.media-amazon.com/images/I/81kTSBuf7+L._AC_UX679_.jpg', 'Bags', 'Nike'),
(14, 'High Sweatpants', '29', '2.0', 2, '16.73 x 13.68 x 4. 34 inches', 'Skin-friendly Material. Super soft & comfortable. Can hold mobile phones, wallets, keys and cards, freeing your hands', 'https://m.media-amazon.com/images/I/71JKGqlYYRL._AC_UY879_.jpg', 'Fashion', 'Yovela'),
(15, 'Scuffette Slipper', '95', '6.4', 2, ' 7.87 x 7.87 x 7.87 inches', 'Featuring a fluffy collar, this essential house slipper is cast in soft suede and our signature sheepskin. Finished with a rubber sole, it pairs with our cozy robes or matching cashmere sets for maximal leisure. This product was made in a factory that supports women in our supply chain with the help of HERproject, a collaborative initiative that creates partnerships with brands like ours to empower and educate women in the workplace.', 'https://m.media-amazon.com/images/I/81C-wvxvYML._AC_UX695_.jpg', 'Fashion', 'Ugg'),
(16, 'Zibtes Tumbler ', '23', '1.5', 9, '10.98 x 5.94 x 4.49 inches', '40oz Insulated tumbler has a vacuum double wall design which ensures good insulation. No matter which season you are in, you can enjoy your favorite hot and cold drinks for a long time keeping hot drinks hot for up to 12 hours and cold for 34 hours.', 'https://m.media-amazon.com/images/I/61p1nOSIf7L._AC_UL640_QL65_.jpg', 'Tumblers', 'Zibtes'),
(17, 'Acrylic Tumbler', '39', '3.84', 7, '14.33 x 10.35 x 7.52 inches', '18 ounce capacity and redesigned press on lid allows you to sip directly or use the included matching straw.  Double wall construction provides insulation of all your hot and cold liquids, such as coffee, iced tea, water and smoothies. All our new matte colors have a soft touch finish and are free of any logos to allow you to create your own custom designs and personalized diy gifts for friends and family.', 'https://m.media-amazon.com/images/I/71-uTMwFGlS._AC_UL640_QL65_.jpg', 'Tumblers', 'Maars'),
(18, 'Hefty Trash Bag', '8', '1.84', 7, '4.4 x 9.5 x 4.7 inches', '40 count box of 13 gallon tall kitchen trash bags. Hefty Strength at a great value. Made in USA. Has Citrus scent.', 'https://m.media-amazon.com/images/I/81gY+ULI2mL._AC_UL640_QL65_.jpg', 'Bags', 'Hefty'),
(19, 'Straw Woven Bag', '32', '8.4', 3, '14.65 x 12.13 x 3.46 inches', 'Handmade straw backpack withTwo shoulder straps,light weight,this beach handbag is fashionable and easy to carry. Lovely and trendy to wear. It\'s only available for daily small stuffs,such as cards,phone, lipstick, tissues, keys,small cosmetics etc. We don\'t recommend putting something heavy,such as wet swimsuit,bottle,book etc. ', 'https://m.media-amazon.com/images/I/719o+ycB1nS._AC_UL640_QL65_.jpg', 'Bags', 'CEMDER'),
(20, 'My Hero Academia', '33', '16', 4, '16.5 x 12.2 x 2.2 inches', 'This backpack set includes a 16’’ backpack with character art of Deku, Todoroki, and Bakuo, an insulated lunch bag that will keep your food cold until lunchtime, a molded rubber keychain, a green utility case, and a carabiner. The padded back on this backpack will keep you feeling comfortable as you carry your textbooks, notebooks, and other school supplies down the hallway. Spend your school days in comfort with this My Hero Academia backpack.', 'https://m.media-amazon.com/images/I/71nFcB+pbYL._AC_UL640_QL65_.jpg', 'Bags', 'BIOWORLD'),
(24, 'DragonBall Sleeve', '25', '8', 5, '15.47 x 11.57 x 2.17 inches;', '100% Cotton with pull on closure. This Dragon Ball Z graphic tee is officially licensed and 100% authentic and is machin wash safe.', 'https://m.media-amazon.com/images/I/61jI5yvoE0L._AC_UL640_QL65_.jpg', 'Fashion', 'Bioworld'),
(25, 'Insulated Tumbler', '23', '2.68', 5, '2.9\"W x 7.4\"H', 'The Simple Modern Classic Tumbler is double walled and vacuum insulated to keep your drink hot or cold for hours. The cup you can enjoy your favorite beverage the way you like. The Classic is made from 18/10 stainless steel and fits most cupholders.', 'https://m.media-amazon.com/images/I/51DnaY-TjKL._AC_UL640_QL65_.jpg', 'Tumblers', 'Vnurnrn'),
(26, 'Vacuum Tumbler', '35', '1.39', 6, '10.91 x 4.72 x 4.61 inches', 'Offering unrivaled value for money: 2 unbreakable straws: 1 curved; great for water + 1 wider & straight; ideal for shakes & smoothies + a straw brush & our upgraded pop up lid for super easy cleaning. Our Tumblers match performance all day long and with our splash proof closable lid you can keep your drinks insulated and protected for longer!', 'https://m.media-amazon.com/images/I/71gjFz0kvxL._AC_UL640_QL65_.jpg', 'Tumblers', 'Beast'),
(27, 'One Piece Hoodie', '25', '2.59', 3, '15.47 x 11.57 x 2.17 inches;', '100% High quality Polyester and machine wash safe. Do not bleach and do not iron.', 'https://m.media-amazon.com/images/I/610zqTOvbAL._AC_UL640_QL65_.jpg', 'Fashion', 'Sybnwnwm'),
(28, 'Vintage Backpack', '39', '2.1', 2, '20.3 x 3 x 12.4 inches', 'Canvas with leather straps with polyurethane lining. Big enough to fit everyday essentials such as laptop, notebook, book, iPad, lunch, camera, shaving equipment, garments, or other accessories that you need. Hand Wash Only.', 'https://m.media-amazon.com/images/I/71AqsQyZQmL._AC_UL640_QL65_.jpg', 'Bags', 'Gearonic'),
(29, 'Stainless Watch ', '100', '8.8', 3, ' 2.91 x 5.75 x 4.8 inches', 'Each of our Fossil mens\' watches are built to last and embody the bold, creative spirit of their wearers. Through quality craftsmanship, premium features and a keen attention to detail, our watches are made for the everyday-wearer, the adventurer and everyone in between. Water resistant to 50m (165ft)', 'https://m.media-amazon.com/images/I/91NIn9qP-rL._AC_UL640_QL65_.jpg', 'Fashion', 'Fossil'),
(30, 'Glass Tumbler', '16', '1.12', 3, '3.2\"W x 3.2\"H', '18 oz glass tumbler made of odor-free, stain-free, and clean tasting glass. No plastic or metallic ingredients touching your drink. Perfect for juices, smoothies, tea, water and more! ', 'https://m.media-amazon.com/images/I/81nEGgpkYJL._AC_UL640_QL65_.jpg', 'Tumblers', 'Ello '),
(31, 'Kavu Rope Bag', '60', '12.4', 4, '20 inches x 11 inches x 5 inches ', '100% Cotton with cotton lining. Two main pockets pack clothes or water bottles, with an internal zip pocket and two front pockets for cell phones, wallets, or other essentials.', 'https://m.media-amazon.com/images/I/71eYbtoM5vL._AC_UL640_QL65_.jpg', 'Bags', 'Kavu'),
(32, 'Gold Necklace', '15', '2.3', 3, '3.8 x 3 x 1.2 inches', 'Gold layered paperclip chain necklace with 18mm coin initial pendant is engraved heart at the reverse side, adjustable, fits most people and is hypoallergenic. ', 'https://m.media-amazon.com/images/I/61hU1mOPtgL._AC_UL640_QL65_.jpg', 'Fashion', 'Yoosteel'),
(33, 'Waterproof Crocs', '22', '2.72', 0, '3.94 x 0.79 x 1.06 inches', 'Easy on, easy off! Just like the adult Classic, this version offers amazing comfort and support, thanks to the light, durable Croslite material and design. People can customize their Crocs clog however they like; ventilation holes accommodate Jibbitz brand charms.', 'https://m.media-amazon.com/images/I/61cQiMQkP3L._AC_UL640_QL65_.jpg', 'Fashion', 'Crocs');

INSERT INTO `ProductFavorite` (`user_id`, `product_id`) VALUES
(20, 1);
INSERT INTO `ProductFavorite` (`user_id`, `product_id`) VALUES
(22, 1);
INSERT INTO `ProductFavorite` (`user_id`, `product_id`) VALUES
(32, 24);
INSERT INTO `ProductFavorite` (`user_id`, `product_id`) VALUES
(35, 1),
(9, 2),
(43, 3),
(48, 10),
(48, 27),
(48, 17),
(42, 2),
(51, 29),
(52, 5),
(53, 4);

INSERT INTO `ProductRating` (`user_id`, `product_id`, `rating`, `would_buy_again`) VALUES
(29, 5, 4, '1');
INSERT INTO `ProductRating` (`user_id`, `product_id`, `rating`, `would_buy_again`) VALUES
(9, 3, 4, 'Y');
INSERT INTO `ProductRating` (`user_id`, `product_id`, `rating`, `would_buy_again`) VALUES
(9, 2, 3, 'N');
INSERT INTO `ProductRating` (`user_id`, `product_id`, `rating`, `would_buy_again`) VALUES
(27, 13, 5, 'Y'),
(11, 1, 3, 'N'),
(32, 24, 5, 'Y'),
(8, 3, 3, NULL),
(8, 2, 5, 'Y'),
(8, 7, 3, 'N'),
(35, 1, 3, NULL),
(9, 16, 5, 'N'),
(9, 17, 2, 'N'),
(9, 14, 5, 'Y'),
(35, 2, 3, 'Y'),
(44, 1, 5, NULL),
(32, 25, 5, NULL),
(32, 27, 5, 'Y'),
(50, 4, 4, 'Y'),
(51, 12, 5, 'Y'),
(52, 30, 3, 'Y'),
(53, 4, 4, 'Y');

INSERT INTO `User` (`id`, `full_name`, `email`, `pass_word`, `phone`, `webstoreBalance`) VALUES
(4, 'user1', 'user1@gmail.com', '$2y$10$fIhag1rSD4/j5tc9YaRTPeBhldi/7288PVCw.GbqiPaNTiFTyb.Xu', NULL, 500);
INSERT INTO `User` (`id`, `full_name`, `email`, `pass_word`, `phone`, `webstoreBalance`) VALUES
(5, 'user1', 'user1@gmail.com', '$2y$10$5VAnX.QDhbOM0MxiYfnaJORsOL.VnLAjr6nzFz0.jhCXvDTpIkjq2', NULL, 500);
INSERT INTO `User` (`id`, `full_name`, `email`, `pass_word`, `phone`, `webstoreBalance`) VALUES
(6, 'user1', 'user1@gmail.com', '$2y$10$dLs/97s4hbKOsW3XnzCOkOEBYflqUgzohB2LZ..KHYK7tGE223DBy', NULL, 500);
INSERT INTO `User` (`id`, `full_name`, `email`, `pass_word`, `phone`, `webstoreBalance`) VALUES
(7, 'user3', 'user3@gmail.com', '$2y$10$QficOksfrkN9a8TpLZBQo.2gM2lVz3P/aVsEA7shIVHSP1ay8Ek16', NULL, 500),
(8, 'user3000', 'user3000@gmail.com', '$2y$10$7lpWKbk2IF8oIrPy9A9Uw.1fO86fzEDPi/UBSvKGhAdaM19rkOAVa', NULL, 2468),
(9, 'user6565', 'bingus@gmail.com', '$2y$10$O74fnVvmtZJ5uOlY2Hks4.vlfwfdYWLpeTSJ/enVDtJPaUWdRFS7i', NULL, 2302),
(10, 'testing', 'testing@gmail.com', '$2y$10$8ieIbDNLt8W.j4sgV1laa.f0yBpE.SWy8VWEeN4TWtPbY20aw1ikm', NULL, 500),
(11, '1', 'testaccount', '$2y$10$aNyAZHzpz1nDymNQSZtgJucBs4VBIIzff5fQ.4nAWy5tZgkRvSs/K', NULL, 500),
(12, 'test', 'usertest', '$2y$10$KeIVbAZIjefWvK5sALtPMO6vfkD9no9v3Hrbej2GS3.bfjdCPvNBC', NULL, 1150),
(13, '123', '123', '$2y$10$G8d8n0MnTrtWWldW92ZLJuRDcVY2hkYVTMdBfcJHsB4loUz.lyhqi', NULL, 500),
(14, '1', '1', '$2y$10$DXMK7SP.jOYnDtC0zffjKO0yMLYi.6yjC88nSvV/SHBxYYsY/ojGO', NULL, 500),
(15, '1', '2', '$2y$10$H1rNUDz3Tt21C6KqHgAte.RdEmRp4gu1TO.e0tv6djK6OYYf3F/4m', NULL, 500),
(16, 'thanh123', 'thanh310895@123', '$2y$10$H7JQ3hHpai.s7y685QbP6.9epjxmgT/smNbT4i6KhSSx5rjAdXV7q', NULL, 500),
(17, 'Testing fullname', 'usertest@123', '$2y$10$9xcPglrL.2cj5fY7fzgy5uIwOgUmCBVCvdQud3Br8uTqNxCJY3u1i', NULL, 6347),
(18, 'test', 'testrate01@123', '$2y$10$6LF2ZENXG1OAK2y9ITh9Hezp8zDL3bNte/.SlxsWwrmBBCS0BGKFW', NULL, 500),
(19, '123', 'testuser02@gmail.com', '$2y$10$JCK5mELKZHcT.gL4ea5gbeMOAfAi/BeS..nDzx3tO59OsmpXV2Lzy', NULL, 500),
(20, 'thanh123', 'testuser01@123', '$2y$10$55A79FDZehrk7JZ2QxxCVu9E4ct1snmBHYE7/MTL3xkueze6mNj6K', NULL, 760),
(21, 'thanh vu', 'testuser@123', '$2y$10$KxT4AUx6gyyjTwaTj.ZJIufXoyBeZNt4T1FsqPFE4V4879p7meL2.', NULL, 3360),
(22, 'sagaksjg', 'email@gmail.com', '$2y$10$wLjg20YERTYUdRdGSXfUEeRYNwYJx6M2cXZx5xwKQ/kaV8vyQI8ue', NULL, 650),
(23, 'zzxczxc', '21@123', '$2y$10$pFuKqdWqZQ5WdXvacQIhK.3Er/nv937nAjoDsfpgdZlFT5r7862FS', NULL, 500),
(24, 'a', '123@123', '$2y$10$ldy8HpGMXYlSP3u6VjIh4u42mwXEmSy22Jpef14pQA3SbCapV/9/6', NULL, 500),
(25, 'test', 'test@yahoo.com', '$2y$10$NDkRgNahJEAPRFfTbrvQzOQNAI.8x0an2D7.KcszzCDAj4FPOjs/G', NULL, 1200),
(26, 'thanh', 'usertest@1234', '$2y$10$c0JM8ZE0COBYyVCcm6TP6eYO4LKJjmxSxjajNSTefks.4R9ftBbCO', NULL, 500),
(27, 'Kyle', 'fluffy@gmail.com', '$2y$10$PvswvBb2WBAuxJBGgWxFj.Kxq3C2qByKe9elIntuV66jyCGO.IyQ.', NULL, 500),
(28, 'thanh', 'thanh1@123', '$2y$10$iSoOnTsxHwzvs7Wx8BJxn.QDsdp8goJEG.H5.nthhyg67x8lhQ7Vu', NULL, 500),
(29, 'William', 'William@gmail.com', '$2y$10$adlrShKGM7tiraFmnzCkROyslpisXpKt5YZHL2X/pR3cvOUoUgQmu', NULL, 1200),
(31, 'Usaid Bin Shafqat', 'usaid.shafqat@gmail.com', '$2y$10$b2y9u5hk1sggVU.LRCOQpOFd9eyb0bwrj4J.h3hgEkRZUWiKd9U3q', NULL, 2249),
(32, 'Fatima Ortega', 'fatimarortega@gmail.com', '$2y$10$0xmxqCn0OsNEKfCFbOlzPuJf2kopAgC6HZuQTnglHhn42p3IpOwS6', NULL, 700),
(35, '123', 'test@123', '$2y$10$3mZ.rfgureUQCUcyKT3W7.F022ep2IRfw7GsZXKbWorLSWH4OkcRi', NULL, 500),
(36, '123', 'user@1234', '$2y$10$nFaPRrfYjemlvT5CAeuT9eGdHCHFGfSbHgsq9wFuJVhf9jD3ZVuqG', NULL, 500),
(37, '12', 'user@12', '$2y$10$rTO5DzwRqYMUN3jRsihmS.7p39K5lfr.C4.HuteaTHvrGmU42FiK2', NULL, 500),
(38, '123', 'usertest@12345', '$2y$10$ShKfBnwKL26CAjSQwIDuQuyYlerykU5OrO3ZrDYdmutFO.5egnuqS', NULL, 500),
(39, '123', 'user@123456123', '$2y$10$cS.aCVAK1IiXiErNjteXzeo8PH/scL0UwJBwSOHv0NqXw9ZW7vMTq', NULL, 500),
(40, '1', 'usertest@1231', '$2y$10$ttcogUyBf/WbZ0kNWXq/8O9HSx80EBBW9u8YuoZcBK3UZo8XxUf4C', NULL, 500),
(41, 'Phumuzile Moyo', 'Phumuzile.moyo19@kzoo.edu', '$2y$10$KhiS5G3fOVqGGkGBmNryx.PyU.XsMa/BMDqrt58O1lxGostcOqwNO', NULL, 500),
(42, 'Caroline', 'carolinelamb36@gmail.com', '$2y$10$LGxblwA3yY3YHGSqXls58u6m9J.tv4kiTQSmfYMdMnrZlocHFi46a', NULL, 1100),
(43, 'Александр Молчагин', 'aleksandr.molchagin19@kzoo.edu', '$2y$10$zyKjre1MUbl0Q7PVQoKkCOLRk.PZO2Mo8eUerDN7JgL5kPkGf4xZm', NULL, 405),
(44, 'Hanis', 'hanis@kzoo.edu', '$2y$10$KWX6qYmT9aq.iZUlb2tThepX/oZlP1gRcaVcaKCwt.2v.r/tE59Cu', NULL, 500),
(45, '123', 'usertesting@123', '$2y$10$N0301GxOdPYfGt5orGjj..mHJZVhBTcpIoZ0xEbZGlrOzNbbDhUb.', NULL, 500),
(46, 'usertest', 'usertest@gmail.com', '$2y$10$YW.XRGEjGq8h8FbxC9WvUO.p.lN7cMgn9RS3/V0yrepArVqJ77.W.', NULL, 500),
(47, 'robert yu', '1234@gmail.com', '$2y$10$UTkBzoGcm8V4a6HLrB2vy.NLffTGCk4UD1V7jCxxUJajLLVD4pTIa', NULL, 1000),
(48, 'Thanhhhh', 'rhanvu@gmail.com', '$2y$10$lgbJ/JAUxrG/yRFnXbzfB.HOfy8tV9PtYqdVGMYyRC2kXPk1gz3s.', NULL, 436),
(49, 'binko', 'binko@gmail.com', '$2y$10$og5Mf/xccIaKu4tZxPcZDu4/RXYI9rtvk/cWKdYXtVLbY4jWftRhG', NULL, 500),
(50, 'Thanh test', 'usertest@1', '$2y$10$zdYJGD834RDupYd2Vg6HMu.zfZFCT0qAszjJM.7yPF0kp7wUaWoN.', NULL, 1380),
(51, 'Sandino Vargas-Pérez', 'sandino.vargasperez@kzoo.edu', '$2y$10$GikDqj9XknRD5Yrx987toeIraHDu4yyaOU8.v.mZRJ6wrNr9Pn8QO', NULL, 301),
(52, 'Tabitha', 'tabitha.rowland19@kzoo.edu', '$2y$10$VQlzBQIYzybENOKRYkKJseHuKOxO1cDKr/QRVMtYWrjuRC5h6BivS', NULL, 30451),
(53, 'thanh`', 'thanhtest@1', '$2y$10$uhNkWadPHx7V/mJIQgdsuOi9fOFrxrtm/qU.CocRL3Sr0GvFa46By', NULL, 1380);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;