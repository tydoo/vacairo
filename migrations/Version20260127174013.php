<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127174013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vacation (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, state VARCHAR(255) DEFAULT \'draft\' NOT NULL, hours INT NOT NULL, type_id INT NOT NULL, INDEX IDX_E3DADF75C54C8C93 (type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE vacation_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE vacation ADD CONSTRAINT FK_E3DADF75C54C8C93 FOREIGN KEY (type_id) REFERENCES vacation_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vacation DROP FOREIGN KEY FK_E3DADF75C54C8C93');
        $this->addSql('DROP TABLE vacation');
        $this->addSql('DROP TABLE vacation_type');
    }
}
