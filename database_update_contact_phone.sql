-- Script de mise à jour pour ajouter le champ phone à la table contact_messages
-- Exécutez ce script si votre base de données existe déjà

USE phone_store;

-- Ajouter la colonne phone si elle n'existe pas
ALTER TABLE contact_messages 
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) DEFAULT NULL AFTER email;

