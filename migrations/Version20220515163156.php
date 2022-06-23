<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515163156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member_group_member (member_group_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_D6D1BC209897AAD (member_group_id), INDEX IDX_D6D1BC207597D3FE (member_id), PRIMARY KEY(member_group_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member_group_member ADD CONSTRAINT FK_D6D1BC209897AAD FOREIGN KEY (member_group_id) REFERENCES `member_groups` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_group_member ADD CONSTRAINT FK_D6D1BC207597D3FE FOREIGN KEY (member_id) REFERENCES `members` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE members DROP FOREIGN KEY FK_45A0D2FF9897AAD');
        $this->addSql('DROP INDEX IDX_45A0D2FF9897AAD ON members');
        $this->addSql('ALTER TABLE members DROP member_group_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE member_group_member');
        $this->addSql('ALTER TABLE `members` ADD member_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `members` ADD CONSTRAINT FK_45A0D2FF9897AAD FOREIGN KEY (member_group_id) REFERENCES member_groups (id)');
        $this->addSql('CREATE INDEX IDX_45A0D2FF9897AAD ON `members` (member_group_id)');
    }
}
