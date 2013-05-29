-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Mag 29, 2013 alle 09:01
-- Versione del server: 5.5.24-log
-- Versione PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `alexxia`
--
CREATE DATABASE `alexxia` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `alexxia`;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__address`
--

CREATE TABLE IF NOT EXISTS `ale__nc__address` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `users_ref` int(5) unsigned DEFAULT NULL,
  `fname` varchar(120) NOT NULL,
  `address` varchar(200) NOT NULL,
  `address2` varchar(150) NOT NULL,
  `city` varchar(60) NOT NULL,
  `province` varchar(10) NOT NULL,
  `cap` varchar(12) NOT NULL,
  `state` varchar(6) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_refale__nc__address` (`users_ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `ale__nc__address`
--

INSERT INTO `ale__nc__address` (`id`, `users_ref`, `fname`, `address`, `address2`, `city`, `province`, `cap`, `state`, `telephone`) VALUES
(5, 1, 'Maurizio Carboni', 'Via Crispi 32', '', 'Solarussa', 'OR', '09077', 'IT', '346-6878018');

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__categories`
--

CREATE TABLE IF NOT EXISTS `ale__nc__categories` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `nc__categories_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__categories_refale__nc__categories` (`nc__categories_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `ale__nc__categories`
--

INSERT INTO `ale__nc__categories` (`id`, `nc__categories_ref`) VALUES
(1, NULL),
(2, NULL),
(3, NULL),
(4, NULL),
(5, NULL),
(6, NULL),
(7, NULL),
(8, NULL),
(9, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__categoriesu`
--

CREATE TABLE IF NOT EXISTS `ale__nc__categoriesu` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `fixed_sale` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Dump dei dati per la tabella `ale__nc__categoriesu`
--

INSERT INTO `ale__nc__categoriesu` (`id`, `name`, `fixed_sale`) VALUES
(1, 'Normale', 0),
(99, 'Rivenditore', 21);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__coupons`
--

CREATE TABLE IF NOT EXISTS `ale__nc__coupons` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL,
  `sale` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__creators`
--

CREATE TABLE IF NOT EXISTS `ale__nc__creators` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `ale__nc__creators`
--

INSERT INTO `ale__nc__creators` (`id`, `name`) VALUES
(1, 'Isola Dolce');

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__geographic`
--

CREATE TABLE IF NOT EXISTS `ale__nc__geographic` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__images`
--

CREATE TABLE IF NOT EXISTS `ale__nc__images` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(150) NOT NULL,
  `nc__products_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__products_refale__nc__images` (`nc__products_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dump dei dati per la tabella `ale__nc__images`
--

INSERT INTO `ale__nc__images` (`id`, `url`, `nc__products_ref`) VALUES
(1, 'http://tradizionesarda.it//undefined/ricottelleok.jpg', 1),
(2, 'http://tradizionesarda.it//./media/images//products/seadas.JPG', 2),
(3, 'http://tradizionesarda.it//./media/images//products/seadas.JPG', 4),
(33, 'http://tradizionesarda.it//./media/images//products/Bottarga.jpg', 10),
(5, 'http://tradizionesarda.it//./media/images//products/pabassinas.jpg', 6),
(6, 'http://tradizionesarda.it//./media/images//products/pabassinas.jpg', 7),
(41, 'http://localhost/alexxia/media/images//products/5.jpg', 8),
(17, 'http://tradizionesarda.it//./media/images//products/fregola.JPG', 11),
(23, 'http://tradizionesarda.it//./media/images//products/vermentino.jpg', 12),
(39, 'http://tradizionesarda.it//media/images//products/salsiccia_sarda.jpg', 13),
(36, 'http://tradizionesarda.it//media/images//products/pardulas.jpg', 14),
(37, 'http://tradizionesarda.it//media/images//products/ricottelleok.jpg', 14);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__orders`
--

CREATE TABLE IF NOT EXISTS `ale__nc__orders` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `total` float NOT NULL,
  `payed` tinyint(1) NOT NULL,
  `users_ref` int(5) unsigned DEFAULT NULL,
  `nc__payments_ref` int(5) unsigned DEFAULT NULL,
  `nc__shipments_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_refale__nc__orders` (`users_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `ale__nc__orders`
--

INSERT INTO `ale__nc__orders` (`id`, `total`, `payed`, `users_ref`, `nc__payments_ref`, `nc__shipments_ref`) VALUES
(1, 30, 0, 1, 1, NULL),
(2, 30, 0, 1, 1, NULL),
(3, 30, 0, 1, 1, NULL),
(4, 30, 0, 1, 1, NULL),
(5, 30, 0, 1, 1, NULL),
(6, 30, 0, 1, 1, NULL),
(7, 30, 0, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__payments`
--

CREATE TABLE IF NOT EXISTS `ale__nc__payments` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `UNI_ID` varchar(16) NOT NULL,
  `image` varchar(120) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `ale__nc__payments`
--

INSERT INTO `ale__nc__payments` (`id`, `price`, `UNI_ID`, `image`) VALUES
(1, 1, 'PAYPAL', 'paypal/image/logo.png');

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__prices`
--

CREATE TABLE IF NOT EXISTS `ale__nc__prices` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `price` float(7,2) NOT NULL,
  `q_min` int(10) unsigned NOT NULL,
  `q_max` int(10) unsigned NOT NULL,
  `nc__categoriesU_ref` int(5) unsigned DEFAULT NULL,
  `nc__products_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__categoriesU_refale__nc__prices` (`nc__categoriesU_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=110 ;

--
-- Dump dei dati per la tabella `ale__nc__prices`
--

INSERT INTO `ale__nc__prices` (`id`, `price`, `q_min`, `q_max`, `nc__categoriesU_ref`, `nc__products_ref`) VALUES
(1, 15.00, 1, 2, 1, 1),
(2, 10.00, 1, 2, 1, 2),
(3, 0.00, 1, 2, 1, 2),
(4, 0.00, 1, 2, 1, 3),
(5, 10.00, 1, 2, 1, 4),
(89, 11.85, 1, 2, 99, 6),
(7, 15.00, 1, 2, 1, 6),
(8, 12.00, 1, 2, 1, 6),
(9, 15.00, 1, 2, 1, 7),
(10, 12.00, 1, 2, 1, 7),
(88, 7.90, 1, 2, 99, 4),
(109, 9.48, 2, 5, 99, 8),
(108, 11.85, 1, 2, 99, 8),
(73, 17.00, 3, 0, 1, 10),
(87, 0.00, 1, 2, 99, 3),
(33, 3.00, 1, 2, 1, 11),
(86, 0.00, 1, 2, 99, 2),
(45, 7.00, 1, 2, 1, 12),
(72, 18.00, 1, 2, 1, 10),
(85, 7.90, 1, 2, 99, 2),
(77, 7.00, 1, 2, 1, 14),
(78, 6.00, 3, 0, 1, 14),
(84, 11.85, 1, 2, 99, 1),
(82, 10.00, 1, 2, 1, 13),
(90, 9.48, 1, 2, 99, 6),
(91, 11.85, 1, 2, 99, 7),
(92, 9.48, 1, 2, 99, 7),
(107, 12.00, 2, 5, 1, 8),
(106, 15.00, 1, 2, 1, 8),
(95, 13.43, 3, 0, 99, 10),
(96, 2.37, 1, 2, 99, 11),
(97, 5.53, 1, 2, 99, 12),
(98, 14.22, 1, 2, 99, 10),
(99, 5.53, 1, 2, 99, 14),
(100, 4.74, 3, 0, 99, 14),
(101, 7.90, 1, 2, 99, 13);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__products`
--

CREATE TABLE IF NOT EXISTS `ale__nc__products` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `stars` int(10) unsigned NOT NULL,
  `sells` int(10) unsigned NOT NULL,
  `duration` int(11) NOT NULL,
  `dimension_H` int(10) unsigned NOT NULL,
  `dimension_W` int(10) unsigned NOT NULL,
  `dimension_L` int(10) unsigned NOT NULL,
  `peso` float NOT NULL,
  `nc__categories_ref` int(5) unsigned DEFAULT NULL,
  `nc__creators_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__categories_refale__nc__products` (`nc__categories_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dump dei dati per la tabella `ale__nc__products`
--

INSERT INTO `ale__nc__products` (`id`, `stars`, `sells`, `duration`, `dimension_H`, `dimension_W`, `dimension_L`, `peso`, `nc__categories_ref`, `nc__creators_ref`) VALUES
(13, 0, 0, 30, 0, 0, 0, 0.4, 9, NULL),
(14, 0, 0, 0, 0, 0, 0, 0.3, 6, NULL),
(10, 0, 0, 0, 0, 0, 0, 0.15, 7, NULL),
(11, 0, 0, 0, 0, 0, 0, 0.5, 5, NULL),
(12, 0, 0, 0, 0, 0, 0, 1, 8, NULL),
(8, 0, 0, 0, 0, 0, 0, 1, 6, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__r_images`
--

CREATE TABLE IF NOT EXISTS `ale__nc__r_images` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(150) NOT NULL,
  `nc__recipes_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__recipes_refale__nc__r_images` (`nc__recipes_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dump dei dati per la tabella `ale__nc__r_images`
--

INSERT INTO `ale__nc__r_images` (`id`, `url`, `nc__recipes_ref`) VALUES
(16, 'http://tradizionesarda.it//./media/images//recipes/fregola.jpg', 2),
(14, 'http://tradizionesarda.it//media/images//recipes/spaghetti_bottarga1.jpg', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__recipes`
--

CREATE TABLE IF NOT EXISTS `ale__nc__recipes` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `ingredients` text NOT NULL,
  `preparation` text NOT NULL,
  `difficulty` int(11) NOT NULL,
  `tempo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `ale__nc__recipes`
--

INSERT INTO `ale__nc__recipes` (`id`, `name`, `ingredients`, `preparation`, `difficulty`, `tempo`) VALUES
(2, '', '', '', 3, 30),
(3, '', '', '', 1, 30);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__reviews`
--

CREATE TABLE IF NOT EXISTS `ale__nc__reviews` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vote` int(10) unsigned NOT NULL,
  `users_ref` int(5) unsigned DEFAULT NULL,
  `nc__translates_ref` int(5) unsigned DEFAULT NULL,
  `nc__products_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_refale__nc__reviews` (`users_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__sales`
--

CREATE TABLE IF NOT EXISTS `ale__nc__sales` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `sale` float NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `ale__nc__sales`
--

INSERT INTO `ale__nc__sales` (`id`, `sale`, `start`, `end`) VALUES
(1, 20, '2013-05-02', '2013-05-16'),
(2, 30, '2013-05-01', '2013-05-09');

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__shipments`
--

CREATE TABLE IF NOT EXISTS `ale__nc__shipments` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `time_min` int(10) unsigned NOT NULL,
  `time_max` int(10) unsigned NOT NULL,
  `nc__geographic_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__geographic_refale__nc__shipments` (`nc__geographic_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translates`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translates` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `descrizione` text NOT NULL,
  `lang` varchar(5) NOT NULL,
  `nc__products_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__products_refale__nc__translates` (`nc__products_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

--
-- Dump dei dati per la tabella `ale__nc__translates`
--

INSERT INTO `ale__nc__translates` (`id`, `name`, `descrizione`, `lang`, `nc__products_ref`) VALUES
(65, 'Bottarga di Muggine', '<div id="cke_pastebin">Prodotto di alta qualitÃ , bottarga prodotta nel pieno rispetto della tradizione culinaria ereditata dai vecchi pescatori sardi che, per la stagionatura, richiede solo l''uso di sale Sardegna.<br></div><div>Solo da triglia piÃ¹ grande e piÃ¹ sana, Ã¨ possibile non rimuovere anche il cordone ombelicale (sul biddiu), che conferisce al prodotto un grande valore gastronomico e di immagine.</div><div><br></div><div id="cke_pastebin">Produzione limitata di alta qualitÃ , prodotto riservato ai palati piÃ¹ esigenti.</div><div id="cke_pastebin">Bottarga prodotta, come al solito, con il sistema di asciugatura completamente naturale, senza coloranti, senza conservanti, soggetti a rigidi controlli di qualitÃ  per garantire la freschezza e la genuinitÃ .</div><div id="cke_pastebin"><br></div><div id="cke_pastebin"><br></div><div id="cke_pastebin">Scadenza: 18 mesi dalla data di confezionamento</div><div id="cke_pastebin">Conservazione: Conservare in frigorifero ad una temperatura tra +2 / +5 Â°</div>', 'it', 10),
(66, 'Muggine''s bottarga', '<div id="cke_pastebin"><span style="font-family: inherit; font-size: 1em;">High product quality mullet roe produced in full respect of the culinary tradition inherited from the old Sardinian fishermen who, for seasoning, requires only the use of salt Sardinia. Only from mullet larger and healthier, you can not remove even the umbilical cord (on biddiu), which gives the product a great gastronomic value and image.</span><br><p>Limited production of the highest quality, reserved for the most demanding palates.<br>Mullet roe produced, as usual, with the drying system all natural, no dyes, no preservatives, subject to strict quality controls to ensure the freshness and authenticity.</p><p>&nbsp;<br>Expiration: 18 months from date of packaging<br>Storage: Store in a refrigerator at a temperature between +2 / +5 Â°</p></div>', 'en', 10),
(77, 'Is pabassinas', '<p><span style="background-color:rgb(255, 255, 255); color:rgb(0, 53, 128); font-family:arial,sans-serif; font-size:small">Pabassinas: called also papassinas, according to the geographic areas, have common ingredients in all the recipes ( pieces of almonds, sultana, flour, sugar and eggs, all decorated with icing) but in certain parts of&nbsp;</span><strong>Sardinia</strong><span style="background-color:rgb(255, 255, 255); color:rgb(0, 53, 128); font-family:arial,sans-serif; font-size:small">, added to the paste are pine nuts and chopped walnuts, orange''s rind, nutmeg, sapa (or "saba": it is the cooked must), colorful sugar-candies. Pabassinas have a lengthened or rhomboidal shape. They are produced everywhere in&nbsp;</span><strong>Sardinia</strong><span style="background-color:rgb(255, 255, 255); color:rgb(0, 53, 128); font-family:arial,sans-serif; font-size:small">.</span></p>', 'en', 8),
(72, 'Salsiccia sarda', '<p>Eâ€™ un prodotto inconfondibile dellâ€™arte salumiera sarda, che tradizionalmente veniva confezionato in modo artigianale tra le mura domestiche. La salsiccia sarda classica Ã¨ stagionata, con macinatura a grana molto grossa e budello naturale, sapore tipicamente dolce.</p>', 'it', 13),
(73, 'Salsiccia sarda', '<p>Scrivi qui i dettagli del prodotto</p>', 'en', 13),
(69, 'Pardulas - Formaggelle', '<p>Le&nbsp;pardulas, in sardo sulcitano e campidanese, o&nbsp;casatinas, in sardo nuorese, casgiaddina o formaggelle in Sassarese e logudorese , sono un tipico&nbsp;dolce&nbsp;pasquale&nbsp;della tradizione&nbsp;sarda.Sono piccole tortine con ripieno di&nbsp;ricotta&nbsp;o di&nbsp;formaggio, molto delicate e gustose.A seconda della zona si possono vedere in una versione dolce o salata, all''aroma di&nbsp;arancia&nbsp;o&nbsp;limone&nbsp;e, piÃ¹ rara, una versione con l''uvetta.</p><p>Nonostante la preparazione identica, il gusto tra le pardulas di ricotta, delicatissime, e quelle di formaggio (formaggio fresco), che hanno un sapore piÃ¹ deciso, Ã¨ molto diverso.</p>', 'it', 14),
(70, 'Pardulas - formaggelle', '<div id="cke_pastebin">Pardulas, in Sardinian sulcitano and campidanese, or Casatinas in Sardinia Nuoro, Sassari and casgiaddina or formaggelle in logudorese, are a typical Easter cake tradition sarda.Sono little cakes filled with ricotta cheese or cheese, very delicate and gustose.A depending on the area you can see a version of sweet or savory aroma of orange or lemon, and more rare, a version with raisins.</div><div id="cke_pastebin"><br></div><div id="cke_pastebin">Despite the identical preparation, the taste between the pardulas ricotta, delicate, and those of cheese (fresh cheese), which have a stronger flavor, is very different.</div>', 'en', 14),
(34, 'Fregola sarda', '<p>Fregola Sarda â€œsa fregulaâ€</p><p>La fregola ("fregula", "pistizone") e'' una tipica preparazione artigianale sarda.</p><p>E'' una pasta secca molto simile al cuscus originaria del Campidano, di Cagliari ed Oristano, fatta con semola di grano duro lavorata a mano fino ad ottenere piccole sfere irregolari e rustiche dal sapore del tutto caratteristico assunto con la naturale essiccazione e tostatura.</p><p>Il termine deriva dal latino "frisare" (sminuzzare) e fregola, in lingua italiana, significa appunto piccolo frammento, briciola.</p><p>Deliziosa con i frutti di mare, specialmente vongole veraci e arselle.</p><p>Ottima anche con il minestrone di verdure o di patate.</p><p>Una confezione pesa 500gr e per una singola la spedizione ammonta a 15,00 euro.</p><p>La disponibilitÃ  Ã¨ alta, in caso di maggiori richieste non esitate a contattarci.</p><p>Il prodotto viene ritirato dal luogo di produzione il giorno in cui verrÃ  composto e spedito il pacco,</p><p>quindi oltre alla sua bontÃ  e genuinitÃ  Ã¨ garantita anche la freschezza!</p><p>Ogni prodotto Ã¨ confezionato, sigillato e etichettato dal produttore cosÃ¬ da garantirne lâ€™assoluta autenticitÃ  e lâ€™impossibilitÃ  di contaminazioni esterne.</p><p><br></p><p><br></p><p><br></p>', 'it', 11),
(35, 'Fregola sarda', '<p><span style="font-family: inherit; font-size: 1em;">The spawning season ("fregula", "pistizone") and ''a typical Sardinian handicraft preparation.</span><br></p><p>It ''a very dry pasta similar to couscous original Campidano of Cagliari and Oristano, made with durum wheat semolina handmade to obtain small irregular spheres and rustic flavor characteristic of all taken with the natural drying and roasting.</p><p>The term derives from the Latin "frisare" (shredding) and rutting, in Italian, means precisely small fragment, crumb.</p><p>Delicious with seafood, especially clams and mussels.</p><p>Also good with the soup of vegetables or potatoes.</p><p>A pack weighs 500g and for a single shipment amounted to â‚¬ 15.00.</p><p>The availability is high, in the case of increasing demands do not hesitate to contact us.</p><p>The product is withdrawn from the production site on the day will be made â€‹â€‹and shipped the parcel,</p><p>so in addition to his kindness and genuineness is also guaranteed freshness!</p><p>Each product is packaged, sealed and labeled by the manufacturer so as to guarantee the absolute authenticity and the impossibility of external contamination.</p><p></p>', 'en', 11),
(45, 'Vermentino', '<p><a data-cke-saved-href="http://tradizionesarda.it/admin" href="http://tradizionesarda.it/admin"></a></p><div id="cke_pastebin">The wines made â€‹â€‹from Vermentino are generally of dry whites gently but soft, pale yellow in color, with intense aromas of wildflowers and herbs and a hint of peach.</div><div id="cke_pastebin">They can be matched with seafood appetizers, fish dishes (like the classic combination of lobster and Sardinian Vermentino) and octopus but also with typical Ligurian pansoti with walnut sauce and other Ligurian dishes.</div><p><a data-cke-saved-href="http://tradizionesarda.it/admin" href="http://tradizionesarda.it/admin"></a></p>', 'en', 12),
(46, 'Vermentino', '<div id="cke_pastebin">I vini a base di Vermentino sono generalmente dei bianchi secchi ma delicatamente morbidi, di colore giallo paglierino, con profumi intensi di fiori di campo ed erbacei e una nota di pesca gialla.<br><div id="cke_pastebin">Possono essere abbinati con antipasti di mare, piatti di pesce (come il classico abbinamento tra aragosta e vermentino sardo) e polpo ma anche con i tipici&nbsp;pansoti&nbsp;liguri con salsa di noci e gli altri piatti della&nbsp;cucina ligure.</div></div>', 'it', 12),
(76, 'Is pabassinas', '<p><strong></strong>Pabassinas: chiamato anche papassinas, indipendentemente&nbsp;delle aree geografiche di produzione, ha ingredienti comuni in tutte le ricette (pezzi di mandorle, uva sultanina, farina, zucchero e uova, tutti decorati con glassa), ma in alcune parti della Sardegna, sono aggiunti&nbsp;alla pasta anche pinoli&nbsp;e noci tritate, la scorza di arancia, noce moscata, sapa (o "saba": Ã¨ il mosto cotto), colorate di zucchero caramelle.<br></p><p>Is pabassinas hanno una forma allungata o romboidale. Vengono prodotti ovunque in Sardegna.â€‹<strong></strong></p>', 'it', 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translatesc`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translatesc` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `lang` varchar(5) NOT NULL,
  `nc__categories_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__categories_refale__nc__translatesC` (`nc__categories_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `ale__nc__translatesc`
--

INSERT INTO `ale__nc__translatesc` (`id`, `name`, `lang`, `nc__categories_ref`) VALUES
(1, 'Pasta', 'it', 5),
(2, 'Pasta', 'en', 5),
(3, 'Dolci', 'it', 6),
(4, 'Sweets', 'en', 6),
(5, 'SpecialitÃ  di mare', 'it', 7),
(6, 'Sea speciality', 'en', 7),
(7, 'Vini', 'it', 8),
(8, 'Wine', 'en', 8),
(9, 'Salumi', 'it', 9),
(10, 'Meat', 'en', 9);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translatesg`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translatesg` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `nc__geographic_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__geographic_refale__nc__translatesG` (`nc__geographic_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translatesp`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translatesp` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `nc__payments_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__payments_refale__nc__translatesP` (`nc__payments_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `ale__nc__translatesp`
--

INSERT INTO `ale__nc__translatesp` (`id`, `name`, `lang`, `nc__payments_ref`) VALUES
(1, 'Paypal', 'it', 1),
(2, 'Paypal', 'en', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translatesr`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translatesr` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `ingredients` text NOT NULL,
  `preparation` text NOT NULL,
  `nc__recipes_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__recipes_refale__nc__translatesR` (`nc__recipes_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dump dei dati per la tabella `ale__nc__translatesr`
--

INSERT INTO `ale__nc__translatesr` (`id`, `name`, `lang`, `ingredients`, `preparation`, `nc__recipes_ref`) VALUES
(21, 'Fregola con gamberetti e cozze', 'it', '<p><span style="color:rgb(34, 34, 34)"></span><em>piatti abbondanti, per due persone</em><br></p><p><em></em><br></p><p><em></em><span style="color:rgb(34, 34, 34)">1/2 kg di cozze,</span><br><span style="color:rgb(34, 34, 34)">100 g di gamberetti,</span><br><span style="color:rgb(34, 34, 34)">100 g di fregola,</span><br><span style="color:rgb(34, 34, 34)">1/2 cipolla,</span><br><span style="color:rgb(34, 34, 34)">4 pomodori o un po'' di pomodori pelati,</span><br><span style="color:rgb(34, 34, 34)">aglio, prezzemolo, sale, olio e peperoncino.</span></p>', '<p>Aprire le cozze e conservare in un recipiente il brodo in esse contenute.<br></p><p>Far soffriggere in una padella antiaderente la cipolla e l''aglio e, una volta dorato, aggiungere il pomodoro.</p><p>Lasciare cuocere due minuti, poi aggiungere la fregola e &nbsp;il brodo delle cozze diluito con acqua in uguali misure.<br>Far cuocere la fregola fino a meta'' cottura, poi aggiungere i gamberetti e le cozze sgusciate.<br>Finire la cottura assaggiando di tanto in tanto, se serve aggiungere del sale.<br>Aggiungere il prezzemolo per ultimo.</p><p>Non lasciare asciugare troppo la fregola o si sfarina!</p><p>Servire e... Buon appetito !!!</p>', 2),
(17, 'Spaghetti con Bottarga', 'it', '<p>per <em style="font-weight: bold;">4 persone</em></p><p><strong><em></em></strong><br></p><p>400g di spaghetti,<br>bottarga quanto basta, circa 10g<br>1-2 spicchi d''aglio,<br>olio extravergine d''oliva</p>', '<p>Mettete sul fuoco l''acqua salata per cuocere gli spaghetti e portatela a bollore.<br>Mentre l''acqua Ã¨ sul fuoco, grattugiate una quantitÃ  abbondante di bottarga.<br>Sbucciate uno o due spicchi d''aglio, a piacere, tagliateli a metÃ  e togliete il germoglio verde al loro interno.<br>Tritate l''aglio grossolanamente e trasferitelo nell''insalatiera che vi servirÃ  per condire e servire la pasta.<br>Schiacciate, aiutandovi con una forchetta, l''aglio tritato su tutta la superficie interna dell''insalatiera, quindi raccoglietelo sul fondo.<br>Versate dell''olio extravergine d''oliva, meglio se fruttato, nell''insalatiera insieme all''aglio.<br>A questo punto aggiungete nell''insalatiera anche un bel pungo della bottarga che avete grattugiato precedentemente.<br>Mescolate con un cucchiaio di legno gli ingredienti nell''insalatiera affinchÃ© si amalgamino e muovete l''insalatiera in modo tale che anche le pareti della stessa siano ricoperte dal condimento.<br>Nel frattempo avrete lessato gli spaghetti che scolerete conservando un po'' dell''acqua di cottura.<br>Trasferite gli spaghetti nell''insalatiera e aggiungete, se necessario, altro olio e bottarga.</p>', 3),
(18, 'Spaghetti with Bottarga', 'en', '<p></p><p>for 4 persons</p><p><br></p><p>400g spaghetti,</p><p>bottarga enough, about 10g</p><p>1-2 cloves of garlic,</p><p>extra virgin olive oil</p><p></p>', '<div id="cke_pastebin">Put on the fire salt water to cook the noodles and bring to a boil.</div><div id="cke_pastebin">While the water is heating, a generous amount of grated bottarga.</div><div id="cke_pastebin">Peel one or two cloves of garlic, to taste, cut them in half and remove the green sprout inside.</div><div id="cke_pastebin">Coarsely chop the garlic and transfer it in the salad that will serve you for seasoning and serve pasta.</div><div id="cke_pastebin">Crushed, with a fork, chopped garlic on the entire inner surface dell''insalatiera, then pick it up on the bottom.</div><div id="cke_pastebin">Pour extra virgin olive oil, preferably fruity, together with the garlic in the salad.</div><div id="cke_pastebin">At this point add in the salad also quite a sting you have grated bottarga previously.</div><div id="cke_pastebin">Stir with a wooden spoon so that they can blend the ingredients in the salad and move the bowl in such a way that even the walls are covered with the same seasoning.</div><div id="cke_pastebin">In the meantime you boiled the spaghetti Strain them retaining a bit ''of the cooking water.</div><div id="cke_pastebin">Transfer the noodles in the salad and add, if necessary, other oil and bottarga.</div>', 3),
(22, 'Fregola with prawns and mussels', 'en', 'Ingredienti della ricetta', '<p>Scrivi qui la ricetta</p>', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__translatess`
--

CREATE TABLE IF NOT EXISTS `ale__nc__translatess` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `lang` varchar(5) NOT NULL,
  `nc__shipments_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__shipments_refale__nc__translatesS` (`nc__shipments_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nc__weights`
--

CREATE TABLE IF NOT EXISTS `ale__nc__weights` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL,
  `nc__shipments_ref` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_nc__shipments_refale__nc__weights` (`nc__shipments_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__couponsxnc__orders_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__couponsxnc__orders_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__orders` int(5) unsigned DEFAULT NULL,
  `nc__coupons` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__productsxnc__coupons_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__productsxnc__coupons_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__coupons` int(5) unsigned DEFAULT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__productsxnc__orders_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__productsxnc__orders_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__orders` int(5) unsigned DEFAULT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__productsxnc__payments_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__productsxnc__payments_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__payments` int(5) unsigned DEFAULT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__productsxnc__sales_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__productsxnc__sales_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__sales` int(5) unsigned DEFAULT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__productsxnc__shipments_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__productsxnc__shipments_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__shipments` int(5) unsigned DEFAULT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__nc__recipesxnc__products_sxs`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__nc__recipesxnc__products_sxs` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__products` int(5) unsigned DEFAULT NULL,
  `nc__recipes` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dump dei dati per la tabella `ale__nxn__nc__recipesxnc__products_sxs`
--

INSERT INTO `ale__nxn__nc__recipesxnc__products_sxs` (`id`, `nc__products`, `nc__recipes`) VALUES
(11, 11, 2),
(9, 10, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__nxn__quantity_nc__ordersxnc__products`
--

CREATE TABLE IF NOT EXISTS `ale__nxn__quantity_nc__ordersxnc__products` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nc__orders` int(5) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `nc__products` int(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dump dei dati per la tabella `ale__nxn__quantity_nc__ordersxnc__products`
--

INSERT INTO `ale__nxn__quantity_nc__ordersxnc__products` (`id`, `nc__orders`, `quantity`, `nc__products`) VALUES
(1, 1, 1, 8),
(2, 1, 2, 14),
(3, 2, 1, 8),
(4, 2, 2, 14),
(5, 3, 1, 8),
(6, 3, 2, 14),
(7, 4, 1, 8),
(8, 4, 2, 14),
(9, 5, 1, 8),
(10, 5, 2, 14),
(11, 6, 1, 8),
(12, 6, 2, 14),
(13, 7, 1, 8),
(14, 7, 2, 14);

-- --------------------------------------------------------

--
-- Struttura della tabella `ale__users`
--

CREATE TABLE IF NOT EXISTS `ale__users` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `password` varchar(125) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastVisit` timestamp NULL DEFAULT NULL,
  `registerDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actived` tinyint(1) NOT NULL,
  `verifyCode` varchar(10) NOT NULL,
  `cookieCode` varchar(60) NOT NULL,
  `level` int(10) unsigned DEFAULT '9',
  `founds` float NOT NULL,
  `lang` varchar(5) NOT NULL,
  `info` int(11) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  `nc_cat` int(10) unsigned NOT NULL,
  `nc_nation` varchar(3) NOT NULL DEFAULT 'IT',
  `nc_soc` varchar(60) NOT NULL,
  `nc_piva` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dump dei dati per la tabella `ale__users`
--

INSERT INTO `ale__users` (`id`, `name`, `lastname`, `nick`, `password`, `email`, `lastVisit`, `registerDate`, `actived`, `verifyCode`, `cookieCode`, `level`, `founds`, `lang`, `info`, `banned`, `nc_cat`, `nc_nation`, `nc_soc`, `nc_piva`) VALUES
(1, '', '', 'admin', '0e66b3fcee30406785207ec8a2280ea4:$2a$06$J5klufp7bj6iuBA3dHpz5.|$2a$07$kalhaaxPcOT5cxFJJlSadOtE8eOMrqdSvn5w.h983tP5aYoxhx4rK', 'maury91@gmail.com', NULL, '2013-04-18 15:54:34', 1, '', '$2a$06$tL5vHr9OhCTP3BHuKPR8j..vyw2sRWCHryl4fMQkpQs2sWKwton8O', 0, 0, '', 0, 0, 0, 'IT', '', ''),
(16, 'Maurizio', 'Carboni', 'maury91', 'fcc61c12bf0c55af97f7f6d5117c3f93:$2a$06$XFIys9vq7FWwuQvYA0ER4O|$2a$07$Xo2ZuoD1JoMfupRqYhBiJuxzSZ5YGBDlpAP9nY3NH5SRcBNYPv76C', 'alexxia.cms@gmail.com', NULL, '2013-05-15 09:38:41', 1, '', '', 9, 0, '', 0, 0, 0, 'IT', '', ''),
(17, 'Matteo', 'Zanda', 'matte', '52d24f1c3caec30d89464f1ce7481d4d:$2a$06$gt8WrHKOy9EYlc..DHl0uu|$2a$07$BvpoGhz9oYRyt0/HXwfDk.uik2rrxMo4PikTC2xIcruYPoBZqhxWq', 'matteo@zanda.it', NULL, '2013-05-19 19:31:13', 1, '', '', 9, 0, '', 0, 0, 99, 'IT', 'MatteCorp', 'IT10101010105'),
(18, 'Matteo', 'Zanda', 'azienda', '2cbdae740afb43fbd072713e4a08aed3:$2a$06$wSGTT42OrjY9R8Bw73tz0e|$2a$07$OCUnk5ixHs1In4YuGDg79eaAizCrXdkyQiajZFtZyho.9uXLRTYVa', 'matteozanda@hotmail.com', NULL, '2013-05-21 10:48:37', 1, '', '$2a$06$JH2Di4BkdL0dxSfyzeruLuiNYQci6rDiIuR2qOqmsCBVuZa13ry1.', 9, 0, '', 0, 0, 99, 'IT', 'MatteoCorp', 'IT10101010105'),
(19, 'Matteo', 'Zanda', 'Matt', 'f9de0bcaa56f38fb3f64e8d01e06a968:$2a$06$gK8QKz9EIF5jOYMeYPnKU.|$2a$07$zuievwkHniLYFlOLu3JVReWQUm6gZMNOg.z7y0yyv9uOKRWFKR/CK', 'emnoguitars@libero.it', NULL, '2013-05-21 10:53:07', 0, 'P/vmX/qmpc', '', 9, 0, '', 0, 0, 0, 'IT', '', 'IT'),
(21, 'maury', 'carbon', 'maury', '554ab3bb3e6ff124fa2927dd44281239:$2a$06$DyS3LI0qg1joGNHUiyENne|$2a$07$wzhu13SZkFSYFyEFYL5YTeoX6bkFqUlfGsnmxAHaujZGL45CIsCne', 'emnoguitars@gmail.com', NULL, '2013-05-22 15:00:27', 0, 'bUt5MxJOjv', '', 9, 0, '', 0, 0, 99, 'IT', 'maurycorp', 'IT10101010105');

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ale__nc__address`
--
ALTER TABLE `ale__nc__address`
  ADD CONSTRAINT `fk_users_refale__nc__address` FOREIGN KEY (`users_ref`) REFERENCES `ale__users` (`id`);
--
-- Database: `alexxia_admins`
--
CREATE DATABASE `alexxia_admins` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `alexxia_admins`;

-- --------------------------------------------------------

--
-- Struttura della tabella `admins__admins`
--

CREATE TABLE IF NOT EXISTS `admins__admins` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lastVisit` timestamp NULL DEFAULT NULL,
  `password` varchar(125) NOT NULL,
  `sessionCode` varchar(60) NOT NULL,
  `level` int(10) unsigned DEFAULT '3',
  `lang` varchar(5) NOT NULL,
  `banned` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `admins__admins`
--

INSERT INTO `admins__admins` (`id`, `nick`, `email`, `lastVisit`, `password`, `sessionCode`, `level`, `lang`, `banned`) VALUES
(1, 'admin', 'maury91@gmail.com', NULL, '481190bf4bc98a38afc0adf2d24eced1:$2a$06$FtQSY75Bvqsr2VK9lVcvue|$2a$07$ogDZFgLvN2YpOWLjwgZT6Olk9L/ZcXyaXmr9VBfTcjba7xPgrgnzS', '', 0, '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
