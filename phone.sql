-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 12 août 2025 à 12:17
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `phone`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `idabonnement` int(11) NOT NULL,
  `contact` text NOT NULL,
  `nom` text NOT NULL,
  `date_debut` date NOT NULL,
  `dure` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int(11) NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `entreprise` text NOT NULL,
  `dateinscription` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logo` text NOT NULL,
  `tel` text NOT NULL,
  `nif` text NOT NULL,
  `stat` text NOT NULL,
  `adresse` text NOT NULL,
  `teladmin` text DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`idAdmin`, `mail`, `pass`, `nom`, `prenom`, `entreprise`, `dateinscription`, `logo`, `tel`, `nif`, `stat`, `adresse`, `teladmin`, `mode`) VALUES
(1, 'teste@gmail.com', '$2y$10$dmPpgUzudENMa21CZTXx8.N6EHBBJqB0qKNPQrOcQ2CRA1Senzr2C', 'Votre nom', 'Votre prenom', 'Box', '2025-08-12 10:16:04', '', '', '', '', '', '034', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_admin` int(11) NOT NULL,
  `identifiant` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `appro`
--

CREATE TABLE `appro` (
  `idAppro` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `idunite` int(11) NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `dateAppro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idadmin` int(11) NOT NULL,
  `prix_unitaire` text NOT NULL,
  `idfournisseur` int(11) NOT NULL,
  `idtransfert` int(11) NOT NULL,
  `numero` text NOT NULL,
  `imei1` text NOT NULL,
  `imei2` text NOT NULL,
  `idcmfacture` int(11) NOT NULL,
  `couleur` text NOT NULL,
  `quantite` text NOT NULL,
  `montant` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClient` bigint(20) NOT NULL,
  `nomClient` text NOT NULL,
  `prenomClient` text NOT NULL,
  `adresseClient` text NOT NULL,
  `telClient` text NOT NULL,
  `emailClient` text NOT NULL,
  `idadmin` int(11) DEFAULT NULL,
  `nif` text NOT NULL,
  `stat` text NOT NULL,
  `r_social` text NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `cin_recto` varchar(200) NOT NULL,
  `cin_verso` varchar(200) NOT NULL,
  `image_profil` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `cmpanier`
--

CREATE TABLE `cmpanier` (
  `idcmfacture` int(11) NOT NULL,
  `idcommande` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `idunite` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `min_qte` int(11) NOT NULL,
  `prixunitaire` text NOT NULL,
  `idadmin` int(11) NOT NULL,
  `reference_fournisseur` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idcommande` int(11) NOT NULL,
  `cmfacture` text NOT NULL,
  `datecommande` timestamp NOT NULL DEFAULT current_timestamp(),
  `montant_total` text NOT NULL,
  `tva` text NOT NULL,
  `idfournisseur` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `frais` text NOT NULL,
  `recue` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

CREATE TABLE `depense` (
  `iddepense` int(11) NOT NULL,
  `raison` text NOT NULL,
  `montant` int(11) NOT NULL,
  `datedepense` timestamp NOT NULL DEFAULT current_timestamp(),
  `idadmin` int(11) NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idcommande` int(11) NOT NULL,
  `idAppro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `idfournisseur` int(11) NOT NULL,
  `nom_entr` text NOT NULL,
  `adresse_fournisseur` text NOT NULL,
  `tel_fournisseur` text NOT NULL,
  `nif_fournisseur` text NOT NULL,
  `stat_fournisseur` text NOT NULL,
  `mail_fournisseur` text NOT NULL,
  `idadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `modepaiement`
--

CREATE TABLE `modepaiement` (
  `idModePaiement` bigint(20) NOT NULL,
  `denom` text DEFAULT NULL,
  `numeroCompte` text DEFAULT NULL,
  `idadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `modepaiement`
--

INSERT INTO `modepaiement` (`idModePaiement`, `denom`, `numeroCompte`, `idadmin`) VALUES
(1, 'Espèce', '-', 1);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `idPanier` int(11) NOT NULL,
  `idfacture` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `numero` text NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `prixunitaire` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `remise` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `imei1` text NOT NULL,
  `imei2` text NOT NULL,
  `couleur` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `pointvente`
--

CREATE TABLE `pointvente` (
  `idPointVente` int(11) NOT NULL,
  `adressPv` text NOT NULL,
  `contactPv` text NOT NULL,
  `idadmin` int(11) NOT NULL,
  `denomination_pv` text NOT NULL,
  `type_pv` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `pointvente`
--

INSERT INTO `pointvente` (`idPointVente`, `adressPv`, `contactPv`, `idadmin`, `denomination_pv`, `type_pv`) VALUES
(1, 'Home', '', 1, 'Depot', 'Dépôt');

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `idPrix` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `dateAjoutPrix` date NOT NULL DEFAULT current_timestamp(),
  `prixProduit` text NOT NULL,
  `idunite` int(11) NOT NULL,
  `groupe` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idProduit` int(11) NOT NULL,
  `refProduit` varchar(255) NOT NULL,
  `designation` text NOT NULL,
  `fiche` text NOT NULL,
  `dateAjout` date NOT NULL DEFAULT current_timestamp(),
  `photo` text NOT NULL,
  `idadmin` int(11) NOT NULL,
  `seuil` int(11) NOT NULL,
  `idunite` int(11) NOT NULL,
  `seuil_min` int(11) NOT NULL,
  `type` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `proforma`
--

CREATE TABLE `proforma` (
  `idproforma` int(11) NOT NULL,
  `Facture` varchar(255) NOT NULL,
  `dateVente` timestamp NOT NULL DEFAULT current_timestamp(),
  `telClient` text NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `idadmin` int(11) NOT NULL,
  `tva` text NOT NULL,
  `montant_total` text NOT NULL,
  `montant_payer` text NOT NULL,
  `remarque` text NOT NULL,
  `frais` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `prpanier`
--

CREATE TABLE `prpanier` (
  `idprpanier` int(11) NOT NULL,
  `idproforma` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `numero` text NOT NULL,
  `prixunitaire` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `remise` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `imei1` text NOT NULL,
  `imei2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `serie`
--

CREATE TABLE `serie` (
  `idserie` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `etat_vente` tinyint(1) NOT NULL,
  `numero_serie` text NOT NULL,
  `group_serie` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `auto` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `imei1` text NOT NULL,
  `imei2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `transfert`
--

CREATE TABLE `transfert` (
  `idtransfert` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `qunatite_transfert` int(11) NOT NULL,
  `qte_min_transfert` int(11) NOT NULL,
  `idunite` int(11) NOT NULL,
  `idPointVente_source` int(11) NOT NULL,
  `idPointVente_destination` int(11) NOT NULL,
  `date_transfert` timestamp NOT NULL DEFAULT current_timestamp(),
  `idadmin` int(11) NOT NULL,
  `reception_transfert` tinyint(1) NOT NULL,
  `numero` text NOT NULL,
  `imei1` text NOT NULL,
  `imei2` text NOT NULL,
  `couleur` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tutoriel`
--

CREATE TABLE `tutoriel` (
  `idtuto` int(11) NOT NULL,
  `titre` text NOT NULL,
  `langue` text NOT NULL,
  `video` text NOT NULL,
  `numero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `unite`
--

CREATE TABLE `unite` (
  `idunite` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `denomination` text NOT NULL,
  `idparent` int(11) NOT NULL,
  `formule` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `nomUser` text NOT NULL,
  `prenomUser` text NOT NULL,
  `contact` text NOT NULL,
  `adress` text NOT NULL,
  `typeUser` text NOT NULL,
  `mail` text NOT NULL,
  `pass` text NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `mode` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `idfacture` int(11) NOT NULL,
  `Facture` varchar(255) NOT NULL,
  `dateVente` timestamp NOT NULL DEFAULT current_timestamp(),
  `telClient` text NOT NULL,
  `lieu_livraison` varchar(200) NOT NULL,
  `idPointVente` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `idadmin` int(11) NOT NULL,
  `tva` text NOT NULL,
  `montant_total` text NOT NULL,
  `montant_payer` text NOT NULL,
  `idModePaiement` int(11) NOT NULL,
  `frais` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`idabonnement`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `appro`
--
ALTER TABLE `appro`
  ADD PRIMARY KEY (`idAppro`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`);

--
-- Index pour la table `cmpanier`
--
ALTER TABLE `cmpanier`
  ADD PRIMARY KEY (`idcmfacture`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idcommande`);

--
-- Index pour la table `depense`
--
ALTER TABLE `depense`
  ADD PRIMARY KEY (`iddepense`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`idfournisseur`);

--
-- Index pour la table `modepaiement`
--
ALTER TABLE `modepaiement`
  ADD PRIMARY KEY (`idModePaiement`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`idPanier`);

--
-- Index pour la table `pointvente`
--
ALTER TABLE `pointvente`
  ADD PRIMARY KEY (`idPointVente`);

--
-- Index pour la table `prix`
--
ALTER TABLE `prix`
  ADD PRIMARY KEY (`idPrix`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idProduit`);

--
-- Index pour la table `proforma`
--
ALTER TABLE `proforma`
  ADD PRIMARY KEY (`idproforma`);

--
-- Index pour la table `prpanier`
--
ALTER TABLE `prpanier`
  ADD PRIMARY KEY (`idprpanier`);

--
-- Index pour la table `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`idserie`);

--
-- Index pour la table `transfert`
--
ALTER TABLE `transfert`
  ADD PRIMARY KEY (`idtransfert`);

--
-- Index pour la table `tutoriel`
--
ALTER TABLE `tutoriel`
  ADD PRIMARY KEY (`idtuto`);

--
-- Index pour la table `unite`
--
ALTER TABLE `unite`
  ADD PRIMARY KEY (`idunite`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`idfacture`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `idabonnement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `appro`
--
ALTER TABLE `appro`
  MODIFY `idAppro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `idClient` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cmpanier`
--
ALTER TABLE `cmpanier`
  MODIFY `idcmfacture` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idcommande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `depense`
--
ALTER TABLE `depense`
  MODIFY `iddepense` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `idfournisseur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modepaiement`
--
ALTER TABLE `modepaiement`
  MODIFY `idModePaiement` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `idPanier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pointvente`
--
ALTER TABLE `pointvente`
  MODIFY `idPointVente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `idPrix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idProduit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `proforma`
--
ALTER TABLE `proforma`
  MODIFY `idproforma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `prpanier`
--
ALTER TABLE `prpanier`
  MODIFY `idprpanier` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `serie`
--
ALTER TABLE `serie`
  MODIFY `idserie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transfert`
--
ALTER TABLE `transfert`
  MODIFY `idtransfert` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tutoriel`
--
ALTER TABLE `tutoriel`
  MODIFY `idtuto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `unite`
--
ALTER TABLE `unite`
  MODIFY `idunite` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `idfacture` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
