<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190206074634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $sql=<<<'SQL'
ALTER TABLE `profiles`
RENAME TO `profile`;

ALTER TABLE `views`
RENAME TO `profile_view`;

ALTER TABLE `profile_view`
ADD `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `profile_view`
ADD INDEX `profile_id` (`profile_id`),
ADD INDEX `date` (`date`);


ALTER TABLE `profile`
CHANGE `profile_id` `id` int NOT NULL FIRST,
CHANGE `profile_name` `name` varchar(100) COLLATE 'utf8_general_ci' NOT NULL AFTER `id`;


ALTER TABLE `profile`
ADD PRIMARY KEY `id` (`id`);


SQL;
       $this->addSql($sql);

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
