-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 02 mai 2022 à 10:17
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddqcm`
--

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

DROP TABLE IF EXISTS `etudiants`;
CREATE TABLE IF NOT EXISTS `etudiants` (
  `idEtudiant` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(15) NOT NULL,
  `motDePasse` varchar(15) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `grade` int(1) DEFAULT '0',
  PRIMARY KEY (`idEtudiant`),
  UNIQUE KEY `idEtudiant` (`idEtudiant`)
) ENGINE=InnoDB AUTO_INCREMENT=7768 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`idEtudiant`, `login`, `motDePasse`, `nom`, `prenom`, `email`, `grade`) VALUES
(1, 'ben', '1234a', 'Alison', 'Benjamin', 'alison.benjamin@hotmail.fr', 1),
(5, 'tof', '1234', 'Gand', 'Christophe', 'gand.christophe@free.fr', 0),
(6, 'lulu', '1234', 'Gand', 'Lucile', 'gand.lucile@bbox.fr', 0),
(2384, 'Jano', '12345', 'GARRIGUES', 'Jean', 'gj@mail.com', 0),
(2601, 'Aymeric', '1234', 'BECART', 'Aymeric', 'ba@mail.com', 0),
(7153, 'rob1', 'rob2', 'toz', 'rob', 'rob@mail.com', 0),
(7767, 'Gwen', 'alo', 'Deniel', 'Gwenael', 'dg@mail.com', 1);

-- --------------------------------------------------------

--
-- Structure de la table `qcmfait`
--

DROP TABLE IF EXISTS `qcmfait`;
CREATE TABLE IF NOT EXISTS `qcmfait` (
  `idEtudiant` int(100) NOT NULL,
  `idQuestionnaire` int(100) NOT NULL,
  `idFait` int(100) NOT NULL AUTO_INCREMENT,
  `points` int(11) DEFAULT NULL,
  `dateFait` date DEFAULT NULL,
  PRIMARY KEY (`idFait`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `qcmfait`
--

INSERT INTO `qcmfait` (`idEtudiant`, `idQuestionnaire`, `idFait`, `points`, `dateFait`) VALUES
(2601, 1, 1, 4, '2022-03-18'),
(2601, 2, 2, 2, '2022-03-18'),
(2601, 2, 3, 2, '2022-03-18'),
(2601, 2, 4, 2, '2022-03-18'),
(2601, 2, 5, 0, '2022-03-18'),
(2601, 2, 6, 0, '2022-03-18'),
(2601, 2, 18, 2, '2022-03-18'),
(7767, 1, 8, 3, '2022-03-18'),
(7767, 1, 9, 3, '2022-03-18'),
(7767, 1, 10, 3, '2022-03-18'),
(7767, 1, 11, 0, '2022-03-18'),
(7767, 1, 12, 3, '2022-03-18'),
(7767, 1, 13, 4, '2022-03-18'),
(7767, 2, 14, 3, '2022-03-18'),
(2601, 3, 15, 1, '2022-03-18'),
(2601, 1, 16, 0, '2022-03-18'),
(2601, 2, 17, 1, '2022-03-18'),
(2601, 2, 19, 1, '2022-03-18'),
(2384, 1, 20, 3, '2022-03-18'),
(2384, 1, 21, 4, '2022-03-18'),
(2384, 1, 22, 1, '2022-03-18'),
(2384, 2, 23, 1, '2022-03-18'),
(2384, 1, 24, 1, '2022-03-18'),
(7153, 3, 25, 2, '2022-03-18'),
(7153, 3, 26, 3, '2022-03-18'),
(7153, 1, 27, 2, '2022-03-18'),
(7153, 4, 28, 2, '2022-03-18'),
(7153, 2, 29, 3, '2022-03-18'),
(7767, 1, 30, 2, '2022-03-20'),
(7767, 3, 31, 3, '2022-03-20'),
(7767, 1, 32, 0, '2022-03-21'),
(7767, 2, 33, 0, '2022-03-21'),
(7767, 5, 34, 0, '2022-03-21'),
(7767, 5, 35, 2, '2022-03-21'),
(7767, 5, 36, 2, '2022-03-21'),
(2601, 5, 37, 1, '2022-03-21'),
(2601, 5, 38, 3, '2022-03-21'),
(2601, 1, 39, 4, '2022-03-21'),
(2601, 6, 40, 2, '2022-05-02'),
(2601, 6, 41, 0, '2022-05-02');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `idQuestion` int(11) NOT NULL AUTO_INCREMENT,
  `libelleQuestion` varchar(100) NOT NULL,
  `type` int(11) NOT NULL,
  `nbReponse` int(11) NOT NULL,
  `nbBonneReponse` int(11) NOT NULL,
  `cheminImgQuestion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idQuestion`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `question`
--

INSERT INTO `question` (`idQuestion`, `libelleQuestion`, `type`, `nbReponse`, `nbBonneReponse`, `cheminImgQuestion`) VALUES
(1, 'Complétez le titre du film \"L\'étrange histoire de ...\"', 1, 4, 1, 'https://www.telerama.fr/sites/tr_master/files/styles/968x546/public/medias/2015/03/media_124352/brad-pitt-3-vieux-croulant-rajeunissant-a-vue-d-oeil%2CM207138.jpg?itok=f6bEHybz'),
(2, 'En quelle année est né Woody Alen ?', 1, 4, 1, 'https://www.gala.fr/imgre/fit/http.3A.2F.2Fprd2-bone-image.2Es3-website-eu-west-1.2Eamazonaws.2Ecom.2Fprismamedia_people.2F2017.2F06.2F30.2Fca3de640-7756-4b73-b696-b10e8c621f4e.2Ejpeg/2216x1536/quality/80/woody-allen.jpeg'),
(3, 'Quel est le premier film de Léonardo Di Caprio ?', 1, 3, 1, 'https://fr.web.img5.acsta.net/pictures/15/06/24/14/36/054680.jpg'),
(4, 'Qui jouaient dans le film \"Rock N Roll\" ?', 2, 4, 3, 'https://fr.web.img6.acsta.net/pictures/16/12/08/13/39/179029.jpg'),
(5, 'Qui est l’entraîneur d\'Arsenal ?', 1, 4, 1, 'https://upload.wikimedia.org/wikipedia/fr/thumb/5/53/Arsenal_FC.svg/1200px-Arsenal_FC.svg.png'),
(6, 'En quelle année Nantes a été champion de Fance ?', 2, 6, 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Logo_FC_Nantes_%28avec_fond%29_-_2019.svg/1200px-Logo_FC_Nantes_%28avec_fond%29_-_2019.svg.png'),
(7, 'Qui est le meilleur buteur de ligue 1 pour l\'année 2015-2016 ?', 1, 3, 1, 'https://peuple-vert.fr/wp-content/uploads/2021/06/ligue-1.jpg'),
(8, 'Quelle est la hauteur de la tour Eiffel ?', 1, 3, 1, NULL),
(9, 'Qui a écrit l\'étranger ?', 1, 3, 1, NULL),
(10, 'Quelle est la capitale de la Roumanie ?', 1, 3, 1, NULL),
(11, 'Quel type d\'objectif correspond à cette longueur focale : 24mm', 1, 3, 1, 'https://apprendre-la-photo.fr/wp-content/uploads/2021/02/exemples_objectifs-821x548.png'),
(12, 'Qu\'est-ce qui produit le plus de bruit ?', 1, 2, 1, 'https://img.bfmtv.com/c/630/420/8e2/bb89d8d7fe7f850c94216bc50254c.jpg'),
(13, 'Quel est le point faible des endermans ?', 1, 3, 1, 'https://br.atsit.in/fr/wp-content/uploads/2021/12/comment-tuer-facilement-un-enderman-dans-minecraft.jpg'),
(14, 'Quelles créatures fuient les chats ?', 2, 4, 2, 'https://thenerdstash.com/wp-content/uploads/2021/11/minecraft-tame-cat.jpg'),
(15, 'Combien faut-il de lingots de fer pour fabriquer une armure complète ?', 1, 3, 1, 'https://minecraft-forum.net/wp-content/uploads/2012/11/7c448__You-Are-The-Zombie-Mod-4.png'),
(22, 'Comment s\'appelle Gwenaël ?', 1, 2, 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `questionnaire`
--

DROP TABLE IF EXISTS `questionnaire`;
CREATE TABLE IF NOT EXISTS `questionnaire` (
  `idQuestionnaire` int(11) NOT NULL,
  `libelleQuestionnaire` varchar(100) NOT NULL,
  `cheminImageQuestionnaire` varchar(255) DEFAULT NULL,
  `idAuteur` int(5) DEFAULT NULL,
  PRIMARY KEY (`idQuestionnaire`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questionnaire`
--

INSERT INTO `questionnaire` (`idQuestionnaire`, `libelleQuestionnaire`, `cheminImageQuestionnaire`, `idAuteur`) VALUES
(1, 'Cinéma', 'https://icisete.fr/wp-content/uploads/2020/12/Les-Cinemas-a-Sete-Horaires-Programmes.jpg', 1),
(2, 'Foot', 'https://districtfoot85.fff.fr/wp-content/uploads/sites/37/2021/07/ballon-filet-611x378.jpeg', 1),
(3, 'Culture générale', 'https://cdn.radiofrance.fr/s3/cruiser-production/2020/12/654fcaa6-d70a-4666-afd3-a664c4c07678/870x489_gettyimages-170535603.jpg', 1),
(4, 'Photographie', 'https://comparateur.leparisien.fr/wp-content/uploads/2019/11/Meilleurs-appareil-photo.jpg', 7767),
(5, 'Minecraft', 'https://image.api.playstation.com/vulcan/img/cfn/11307x4B5WLoVoIUtdewG4uJ_YuDRTwBxQy0qP8ylgazLLc01PBxbsFG1pGOWmqhZsxnNkrU3GXbdXIowBAstzlrhtQ4LCI4.png', 7767),
(6, 'test', 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Gull_portrait_ca_usa.jpg', 7767);

-- --------------------------------------------------------

--
-- Structure de la table `questionquestionnaire`
--

DROP TABLE IF EXISTS `questionquestionnaire`;
CREATE TABLE IF NOT EXISTS `questionquestionnaire` (
  `idQuestionnaire` int(11) NOT NULL,
  `idQuestion` int(11) NOT NULL,
  `odreQuestion` int(10) DEFAULT NULL,
  PRIMARY KEY (`idQuestionnaire`,`idQuestion`),
  KEY `idQuestion` (`idQuestion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questionquestionnaire`
--

INSERT INTO `questionquestionnaire` (`idQuestionnaire`, `idQuestion`, `odreQuestion`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(2, 5, 1),
(2, 6, 2),
(2, 7, 3),
(3, 8, 1),
(3, 9, 2),
(3, 10, 3),
(4, 11, 1),
(4, 12, 2),
(5, 13, 1),
(5, 14, 2),
(5, 15, 3),
(6, 4, 1),
(6, 14, 2);

-- --------------------------------------------------------

--
-- Structure de la table `questionreponse`
--

DROP TABLE IF EXISTS `questionreponse`;
CREATE TABLE IF NOT EXISTS `questionreponse` (
  `idQuestion` int(11) NOT NULL,
  `idReponse` int(11) NOT NULL,
  `ordre` int(11) NOT NULL,
  `bonne` int(11) NOT NULL,
  PRIMARY KEY (`idQuestion`,`idReponse`),
  KEY `idReponse` (`idReponse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questionreponse`
--

INSERT INTO `questionreponse` (`idQuestion`, `idReponse`, `ordre`, `bonne`) VALUES
(1, 1, 1, 0),
(1, 2, 2, 1),
(1, 3, 3, 0),
(1, 4, 4, 0),
(2, 5, 1, 0),
(2, 6, 2, 1),
(2, 7, 3, 0),
(2, 8, 4, 0),
(3, 9, 1, 0),
(3, 10, 2, 1),
(3, 11, 3, 0),
(4, 12, 1, 1),
(4, 13, 2, 0),
(4, 14, 3, 1),
(4, 15, 4, 1),
(5, 16, 1, 0),
(5, 17, 2, 0),
(5, 18, 3, 1),
(5, 19, 4, 0),
(6, 20, 1, 0),
(6, 21, 2, 1),
(6, 22, 3, 1),
(6, 23, 4, 1),
(6, 24, 5, 0),
(6, 25, 6, 1),
(7, 26, 1, 1),
(7, 27, 2, 0),
(7, 28, 3, 0),
(8, 29, 1, 0),
(8, 30, 2, 1),
(8, 31, 3, 0),
(9, 32, 1, 0),
(9, 33, 2, 0),
(9, 34, 3, 1),
(10, 35, 1, 1),
(10, 36, 2, 0),
(10, 37, 3, 0),
(11, 38, 1, 0),
(11, 39, 2, 0),
(11, 40, 3, 1),
(12, 41, 1, 0),
(12, 42, 2, 1),
(13, 43, 1, 0),
(13, 44, 2, 1),
(13, 45, 3, 0),
(14, 46, 1, 1),
(14, 47, 2, 0),
(14, 48, 3, 0),
(14, 49, 4, 1),
(15, 50, 1, 1),
(15, 51, 2, 0),
(15, 52, 3, 0),
(22, 53, 1, 1),
(22, 54, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `idReponse` int(11) NOT NULL AUTO_INCREMENT,
  `valeur` text NOT NULL,
  `cheminImage` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`idReponse`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`idReponse`, `valeur`, `cheminImage`) VALUES
(1, 'Benji', ''),
(2, 'Benjamin Button', ''),
(3, 'Benjamin Gates', ''),
(4, 'Benjamin Constant', ''),
(5, '1930', ''),
(6, '1935', ''),
(7, '1940', ''),
(8, '1945', ''),
(9, 'Piranha 2', ''),
(10, 'Critters 3', ''),
(11, 'Amityville II', ''),
(12, 'Guillaume Canet', ''),
(13, 'Clovis Cornillac', ''),
(14, 'Marion Cotillard', ''),
(15, 'Gilles Lelouche', ''),
(16, 'Raymond Domenech', ''),
(17, 'Elie Baup', ''),
(18, 'Arsène Wenger', ''),
(19, 'José Mourinho', ''),
(20, '1964', ''),
(21, '1965', ''),
(22, '1980', ''),
(23, '1983', ''),
(24, '1986', ''),
(25, '1995', ''),
(26, 'Edinson Cavani', ''),
(27, 'Alexandre Lacazette', ''),
(28, 'Bafetimbi Gomis', ''),
(29, '320', ''),
(30, '324', ''),
(31, '328', ''),
(32, 'Victor Hugo', ''),
(33, 'Boris Vian', ''),
(34, 'Albert Camus', ''),
(35, 'Bucarest', ''),
(36, 'Budapest', ''),
(37, 'Sofia', ''),
(38, 'téléphoto', ''),
(39, 'fisheye', ''),
(40, 'grand-angle', ''),
(41, 'iso 100', ''),
(42, 'iso 800', ''),
(43, 'le feu', ''),
(44, 'l eau', ''),
(45, 'les flèches', ''),
(46, 'les creepers', ''),
(47, 'les cochons', ''),
(48, 'les zombies', ''),
(49, 'les phantoms', ''),
(50, '24', ''),
(51, '35', ''),
(52, '11', ''),
(53, 'Gwenaël', ''),
(54, 'Gwenaol', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `questionquestionnaire`
--
ALTER TABLE `questionquestionnaire`
  ADD CONSTRAINT `questionquestionnaire_ibfk_1` FOREIGN KEY (`idQuestionnaire`) REFERENCES `questionnaire` (`idQuestionnaire`),
  ADD CONSTRAINT `questionquestionnaire_ibfk_2` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`idQuestion`);

--
-- Contraintes pour la table `questionreponse`
--
ALTER TABLE `questionreponse`
  ADD CONSTRAINT `questionreponse_ibfk_1` FOREIGN KEY (`idQuestion`) REFERENCES `question` (`idQuestion`),
  ADD CONSTRAINT `questionreponse_ibfk_2` FOREIGN KEY (`idReponse`) REFERENCES `reponse` (`idReponse`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
