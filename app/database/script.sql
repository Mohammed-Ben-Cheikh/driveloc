CREATE DATABASE driveloc;

USE driveloc;


CREATE TABLE `categories` (
    `id_categorie` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,  
    `nom` VARCHAR(255) NOT NULL,                                
    `description` TEXT DEFAULT NULL,                               
    `image_url` VARCHAR(255) DEFAULT NULL,                         
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,             
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);


CREATE TABLE `vehicules` (
    `id_vehicule` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `nom` VARCHAR(255) NOT NULL,                    
    `prix_a_loue` DECIMAL(10, 2) NOT NULL,          
    `description` TEXT DEFAULT NULL,              
    `id_categorie_fk` INT(11) NOT NULL,
    `matriculation` VARCHAR(100) DEFAULT NULL,            
    `marque` VARCHAR(100) DEFAULT NULL,           
    `modele` VARCHAR(100) DEFAULT NULL,
    `annee` INT(4) DEFAULT NULL,
    `climatisation` ENUM('oui', 'non') DEFAULT 'non',        
    `type_vitesse` ENUM('Manuelle', 'Automatique', 'Semi-Automatique') DEFAULT 'Manuelle',
    `nb_vitesses` ENUM('3','4','5','6','7','8','9','10','11') DEFAULT '5', 
    `toit_panoramique` ENUM('Non', 'Fixe', 'Ouvrant') DEFAULT 'Non',            
    `kilometrage` DECIMAL(10, 2) DEFAULT NULL,   
    `carburant` ENUM('Essence', 'Diesel', 'Électrique', 'Hybride') DEFAULT 'Essence', 
    `nombre_de_places` INT(11) DEFAULT NULL,         
    `nombre_de_portes` INT(11) DEFAULT NULL,        
    `disponibilite` ENUM('Disponible', 'Indisponible', 'Réservé') DEFAULT 'Disponible',
    `nombre_image` INT(11) DEFAULT 0, 
    `image_url` VARCHAR(255) DEFAULT NULL,
    `reservations_par_vehicule` INT(11) DEFAULT 0,             
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`id_categorie_fk`) REFERENCES `categories`(`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE `roles` (
    `id_role` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,     
    `role` VARCHAR(255) NOT NULL 
);


CREATE TABLE `admins` (
    `id_admin` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,    
    `nom` VARCHAR(255) NOT NULL,                             
    `prenom` VARCHAR(255) NOT NULL,                            
    `username` VARCHAR(255) NOT NULL UNIQUE,                    
    `email` VARCHAR(255) NOT NULL UNIQUE,                   
    `telephone` VARCHAR(20) NOT NULL,                           
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,                       
    `id_role_fk` INT(11) NOT NULL,
    FOREIGN KEY (`id_role_fk`) REFERENCES `roles`(`id_role`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE `users` (    
    `id_user` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `nom` VARCHAR(255) NOT NULL,                  
    `prenom` VARCHAR(255) NOT NULL,                  
    `username` VARCHAR(255) NOT NULL UNIQUE,          
    `email` VARCHAR(255) NOT NULL UNIQUE,         
    `telephone` VARCHAR(20) NOT NULL,            
    `mot_de_passe` VARCHAR(255) NOT NULL,          
    `adresse` TEXT NOT NULL,                                   
    `pays` VARCHAR(255) NOT NULL,                  
    `ville` VARCHAR(255) NOT NULL,                              
    `code_postal` VARCHAR(5) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,                          
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,        
    `date_modification` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `id_role_fk` INT(11) NOT NULL,
    FOREIGN KEY (`id_role_fk`) REFERENCES `roles`(`id_role`) ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE `reservations` (
    `id_reservation` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,  
    `id_user_fk` INT(11) NOT NULL,                                 
    `id_vehicule_fk` INT(11) NOT NULL,                             
    `date_reservation` DATETIME NOT NULL,                                                   
    `statut`  ENUM('en attente', 'approuvée', 'refusée', 'terminée', 'annulée') DEFAULT 'en attente',
    `lieux` VARCHAR(255) NOT NULL,   
    `commentaire` TEXT,                                            
    `date_creation` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,           
    `if_reser_modif` ENUM('yes','no') DEFAULT 'no',                
    `date_modification` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,               
    `date_limite` DATETIME NOT NULL,                              
    FOREIGN KEY (`id_user_fk`) REFERENCES `users`(`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,  
    FOREIGN KEY (`id_vehicule_fk`) REFERENCES `vehicules`(`id_vehicule`) ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,                
    `id_user_fk` INT NOT NULL,                              
    `id_vehicule_fk` INT NOT NULL,
    `statut` ENUM('en attente', 'approuvée', 'refusée') DEFAULT 'en attente',                   
    `rating` INT CHECK (rating >= 1 AND rating <= 5),     
    `comment` TEXT,                                      
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (`id_user_fk`) REFERENCES `users`(`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,  
    FOREIGN KEY (`id_vehicule_fk`) REFERENCES `vehicules`(`id_vehicule`) ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE `statistiques` (
    `id_statistique` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    `total_clients` INT(11) DEFAULT 0,
    `total_categories` INT(11) DEFAULT 0,               
    `total_vehicules` INT(11) DEFAULT 0,               
    `total_reservations` INT(11) DEFAULT 0,               
    `reservations_en_attente` INT(11) DEFAULT 0,               
    `reservations_approuvees` INT(11) DEFAULT 0,        
    `reservations_refusees` INT(11) DEFAULT 0,               
    `reservations_terminee` INT(11) DEFAULT 0,                             
    `date_mise_a_jour` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);


CREATE TABLE `messages` (
    `id_message` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(255) NOT NULL,                
    `email` INT NOT NULL,                                  
    `comment` TEXT,                                      
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_resets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    used BOOLEAN DEFAULT FALSE
);

INSERT INTO `roles` (`role`) VALUES
('SuperAdmin'),
('Admin'),
('User');

DELIMITER //
CREATE PROCEDURE AjouterReservation(
    IN p_id_user INT,
    IN p_id_vehicule INT,
    IN p_date_reservation DATETIME,
    IN p_lieux VARCHAR(255),
    IN p_commentaire TEXT,
    IN p_date_limite DATETIME
)
BEGIN
    DECLARE vehicule_disponible ENUM('Disponible', 'Indisponible', 'Réservé');

    -- Vérifier la disponibilité du véhicule
    SELECT disponibilite INTO vehicule_disponible
    FROM vehicules
    WHERE id_vehicule = p_id_vehicule;

    IF vehicule_disponible = 'Disponible' THEN
        -- Ajouter la réservation
        INSERT INTO reservations (
            id_user_fk, id_vehicule_fk, date_reservation, 
            lieux, commentaire, date_limite
        ) VALUES (
            p_id_user, p_id_vehicule, p_date_reservation, 
            p_lieux, p_commentaire, p_date_limite
        );

        -- Mettre à jour le statut du véhicule
        UPDATE vehicules
        SET disponibilite = 'Réservé'
        WHERE id_vehicule = p_id_vehicule;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le véhicule sélectionné n\'est pas disponible.';
    END IF;
END //

DELIMITER ;


-- Insert data into `categories`
INSERT INTO `categories` (`nom`, `description`, `image_url`) VALUES
('SUV', 'Sport Utility Vehicles, ideal for families and off-road.', 'suv.jpg'),
('Compact', 'Compact cars, perfect for city driving.', 'compact.jpg'),
('Luxury', 'Luxury vehicles for premium experience.', 'luxury.jpg'),
('Electric', 'Environmentally friendly electric cars.', 'electric.jpg');

-- Insert data into `vehicules`
INSERT INTO `vehicules` (`nom`, `prix_a_loue`, `description`, `id_categorie_fk`, `matriculation`, `marque`, `modele`, `annee`, `climatisation`, `type_vitesse`, `nb_vitesses`, `toit_panoramique`, `kilometrage`, `carburant`, `nombre_de_places`, `nombre_de_portes`, `disponibilite`, `nombre_image`, `image_url`, `reservations_par_vehicule`) VALUES
('Toyota RAV4', 80.00, 'A reliable SUV with great fuel efficiency.', 1, 'ABC-1234', 'Toyota', 'RAV4', 2020, 'oui', 'Automatique', '6', 'Fixe', 25000.00, 'Essence', 5, 5, 'Disponible', 3, 'toyota_rav4.jpg', 15),
('Honda Civic', 50.00, 'A compact car perfect for city driving.', 2, 'DEF-5678', 'Honda', 'Civic', 2018, 'oui', 'Manuelle', '5', 'Non', 40000.00, 'Essence', 5, 4, 'Disponible', 2, 'honda_civic.jpg', 10),
('BMW 7 Series', 200.00, 'Luxury sedan with top-notch features.', 3, 'GHI-9101', 'BMW', '7 Series', 2022, 'oui', 'Automatique', '8', 'Ouvrant', 15000.00, 'Diesel', 5, 4, 'Disponible', 5, 'bmw_7series.jpg', 5),
('Tesla Model 3', 120.00, 'High-performance electric car.', 4, 'JKL-2345', 'Tesla', 'Model 3', 2021, 'oui', 'Automatique', '1', 'Fixe', 12000.00, 'Électrique', 5, 4, 'Disponible', 4, 'tesla_model3.jpg', 8);




