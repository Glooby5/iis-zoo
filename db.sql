CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, species_id INT DEFAULT NULL, enclosure_id INT DEFAULT NULL, mother_id INT DEFAULT NULL, father_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sex VARCHAR(255) NOT NULL, birthday DATETIME DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, date_of_death DATETIME DEFAULT NULL, dead TINYINT(1) DEFAULT NULL, INDEX IDX_6AAB231FB2A1D860 (species_id), INDEX IDX_6AAB231FD04FE1E5 (enclosure_id), UNIQUE INDEX UNIQ_6AAB231FB78A354D (mother_id), UNIQUE INDEX UNIQ_6AAB231F2055B9A2 (father_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, species_id INT DEFAULT NULL, enclosure_type_id INT DEFAULT NULL, cleaning_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_219CDA4AA76ED395 (user_id), INDEX IDX_219CDA4AB2A1D860 (species_id), INDEX IDX_219CDA4A6740819A (enclosure_type_id), INDEX IDX_219CDA4AE912013A (cleaning_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE cleaning (id INT AUTO_INCREMENT NOT NULL, enclosure_id INT DEFAULT NULL, cleaning_type_id INT DEFAULT NULL, attendants_count INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, done TINYINT(1) NOT NULL, INDEX IDX_3F6C5CF9D04FE1E5 (enclosure_id), INDEX IDX_3F6C5CF9E912013A (cleaning_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE cleaning_user (cleaning_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D5A3C1C8E5EB27B (cleaning_id), INDEX IDX_D5A3C1CA76ED395 (user_id), PRIMARY KEY(cleaning_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE cleaning_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, tools VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE enclosure (id INT AUTO_INCREMENT NOT NULL, enclosure_type_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, size VARCHAR(255) NOT NULL, capacity INT NOT NULL, INDEX IDX_E0F730636740819A (enclosure_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE enclosure_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE feeding (id INT AUTO_INCREMENT NOT NULL, keeper_id INT DEFAULT NULL, animal_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, species VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, done TINYINT(1) NOT NULL, INDEX IDX_A70BE25C7B7C4783 (keeper_id), INDEX IDX_A70BE25C8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, occurrence VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, personal_number VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE;
ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FD04FE1E5 FOREIGN KEY (enclosure_id) REFERENCES enclosure (id) ON DELETE CASCADE;
ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB78A354D FOREIGN KEY (mother_id) REFERENCES animal (id) ON DELETE SET NULL;
ALTER TABLE animal ADD CONSTRAINT FK_6AAB231F2055B9A2 FOREIGN KEY (father_id) REFERENCES animal (id) ON DELETE SET NULL;
ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id);
ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE;
ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4A6740819A FOREIGN KEY (enclosure_type_id) REFERENCES enclosure_type (id) ON DELETE CASCADE;
ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AE912013A FOREIGN KEY (cleaning_type_id) REFERENCES cleaning_type (id) ON DELETE CASCADE;
ALTER TABLE cleaning ADD CONSTRAINT FK_3F6C5CF9D04FE1E5 FOREIGN KEY (enclosure_id) REFERENCES enclosure (id) ON DELETE CASCADE;
ALTER TABLE cleaning ADD CONSTRAINT FK_3F6C5CF9E912013A FOREIGN KEY (cleaning_type_id) REFERENCES cleaning_type (id) ON DELETE CASCADE;
ALTER TABLE cleaning_user ADD CONSTRAINT FK_D5A3C1C8E5EB27B FOREIGN KEY (cleaning_id) REFERENCES cleaning (id) ON DELETE CASCADE;
ALTER TABLE cleaning_user ADD CONSTRAINT FK_D5A3C1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE;
ALTER TABLE enclosure ADD CONSTRAINT FK_E0F730636740819A FOREIGN KEY (enclosure_type_id) REFERENCES enclosure_type (id) ON DELETE CASCADE;
ALTER TABLE feeding ADD CONSTRAINT FK_A70BE25C7B7C4783 FOREIGN KEY (keeper_id) REFERENCES user (id);
ALTER TABLE feeding ADD CONSTRAINT FK_A70BE25C8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE;

INSERT INTO `animal` (`id`, `species_id`, `enclosure_id`, `mother_id`, `father_id`, `name`, `sex`, `birthday`, `country`, `date_of_death`, `dead`) VALUES
(1, 1, 1, 2, NULL, 'Vorpel', 'male', '2015-10-14 00:00:00', 'Česká republika', NULL, 0),
(2, 1, 1, NULL, NULL, 'Agaue', 'female', '2012-12-19 00:00:00', 'Slovensko', NULL, 0);


INSERT INTO `certificate` (`id`, `user_id`, `species_id`, `enclosure_type_id`, `cleaning_type_id`, `name`, `start`, `end`, `discr`) VALUES
(1, 3, 1, NULL, NULL, 'Certifikát pro práci se slony', '2016-03-07 00:00:00', '2017-09-28 00:00:00', 'species'),
(2, 1, NULL, NULL, 1, 'Certifikát pro kydání hnoje', '2015-11-30 00:00:00', '2018-02-10 00:00:00', 'cleaning'),
(3, 1, NULL, 1, NULL, 'Certifikát pro vstup do výběhu slonů', '2016-02-01 00:00:00', '2018-01-21 00:00:00', 'enclosure');


INSERT INTO `cleaning` (`id`, `enclosure_id`, `cleaning_type_id`, `attendants_count`, `start`, `end`, `done`) VALUES
(1, 1, 1, 1, '2017-02-09 13:00:00', '2017-01-09 14:00:00', 0);


INSERT INTO `cleaning_type` (`id`, `name`, `tools`) VALUES
(1, 'Kydání hnoje', 'vidle, kolečko');


INSERT INTO `cleaning_user` (`cleaning_id`, `user_id`) VALUES
(1, 1);


INSERT INTO `enclosure` (`id`, `enclosure_type_id`, `label`, `size`, `capacity`) VALUES
(1, 1, 'Výběh slonů, 5B', '500 m2', 4);


INSERT INTO `enclosure_type` (`id`, `name`) VALUES
(1, 'Ohrada pro slony');


INSERT INTO `feeding` (`id`, `keeper_id`, `animal_id`, `start`, `end`, `species`, `amount`, `done`) VALUES
(1, 3, 1, '2017-02-17 09:30:00', '2017-02-17 09:55:00', 'salát', '3 nádoby', 0);


INSERT INTO `species` (`id`, `name`, `occurrence`) VALUES
(1, 'Slon testovací', 'Afrika');


INSERT INTO `user` (`id`, `email`, `password`, `firstname`, `lastname`, `personal_number`, `birthday`, `title`, `role`) VALUES
(1, 'admin@zoo.cz', '$2y$10$3FKwPjxjck2X5GeT8.LSWOg9Qj5/IGgyW5SGscGWYeCzEQSBkM.w6', 'Hlavní', 'Administrátor', '', '2016-12-04 19:41:49', 'Ing', 'admin'),
(3, 'osetrovatel@zoo.cz', '$2y$10$klzMsLp7n4QVvE6qT929/ee3gPBbEAWWzw6bEsjY8/HJI/onKAEYK', 'První', 'Ošetřovatel', '', '2016-12-04 19:49:09', '', 'attendant'),
(4, 'zaregistrovany@zoo.cz', '$2y$10$V3VfOU0iwEheE2gHonQdA.norICgc/5UqQHWEAjOv.5BL5l4QefjC', 'Pouze', 'Zaregistrovany', NULL, NULL, NULL, 'registered');