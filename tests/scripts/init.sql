--
-- Structure de la table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `adresse` varchar(200) NOT NULL,
  `codepostal` varchar(10) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `motdepasse` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `adresse`, `codepostal`, `ville`, `motdepasse`) VALUES
(1, 'DUPONT', 'Robert', '40, rue de la Paix', '75007', 'Paris', 'secret');
INSERT INTO `client` (`id`, `nom`, `prenom`, `adresse`, `codepostal`, `ville`, `motdepasse`) VALUES
(2, 'JOUMEL', 'Corinne', '28, rue du Colonel Armand', '35400', 'Saint-Malo', 'mdp=secret');

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE IF NOT EXISTS `compte` (
  `numero` int(11) NOT NULL,
  `solde` decimal(10,2) NOT NULL,
  `idclient` int(11) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `idclient` (`idclient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`numero`, `solde`, `idclient`) VALUES
(78954263, '5000.00', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compte`
--
ALTER TABLE `compte`
  ADD CONSTRAINT `compte_ibfk_1` FOREIGN KEY (`idclient`) REFERENCES `client` (`id`);
COMMIT;
