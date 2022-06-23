<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513142526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E91ED93D47');
        $this->addSql('CREATE TABLE `member_groups` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D4BBBB435E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `members` (id INT AUTO_INCREMENT NOT NULL, member_group_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_45A0D2FF9897AAD (member_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `members` ADD CONSTRAINT FK_45A0D2FF9897AAD FOREIGN KEY (member_group_id) REFERENCES `member_groups` (id)');
        $this->addSql('DROP TABLE user_groups');
        $this->addSql('DROP INDEX IDX_1483A5E91ED93D47 ON users');
        $this->addSql('ALTER TABLE users ADD email VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, DROP user_group_id, DROP lastname, CHANGE firstname password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `members` DROP FOREIGN KEY FK_45A0D2FF9897AAD');
        $this->addSql('CREATE TABLE user_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_953F224D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE `member_groups`');
        $this->addSql('DROP TABLE `members`');
        $this->addSql('DROP INDEX UNIQ_1483A5E9E7927C74 ON `users`');
        $this->addSql('ALTER TABLE `users` ADD user_group_id INT DEFAULT NULL, ADD lastname VARCHAR(255) DEFAULT NULL, DROP email, DROP roles, CHANGE password firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `users` ADD CONSTRAINT FK_1483A5E91ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_groups (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E91ED93D47 ON `users` (user_group_id)');
    }
}
