<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220115020227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postagem (id INT AUTO_INCREMENT NOT NULL, categoria_id_id INT NOT NULL, titulo VARCHAR(50) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, tag VARCHAR(255) NOT NULL, conteudo LONGTEXT NOT NULL, created_at DATETIME NOT NULL, slug VARCHAR(30) NOT NULL, author VARCHAR(100) DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D0E384517E735794 (categoria_id_id), UNIQUE INDEX slug_unique (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nametag VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_postagem (tag_id INT NOT NULL, postagem_id INT NOT NULL, INDEX IDX_E6D2FF8ABAD26311 (tag_id), INDEX IDX_E6D2FF8A6DD36FEA (postagem_id), PRIMARY KEY(tag_id, postagem_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE postagem ADD CONSTRAINT FK_D0E384517E735794 FOREIGN KEY (categoria_id_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE tag_postagem ADD CONSTRAINT FK_E6D2FF8ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_postagem ADD CONSTRAINT FK_E6D2FF8A6DD36FEA FOREIGN KEY (postagem_id) REFERENCES postagem (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categoria ADD postagem_id INT DEFAULT NULL, ADD updated_at DATETIME NOT NULL, CHANGE date created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE categoria ADD CONSTRAINT FK_4E10122D6DD36FEA FOREIGN KEY (postagem_id) REFERENCES postagem (id)');
        $this->addSql('CREATE INDEX IDX_4E10122D6DD36FEA ON categoria (postagem_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoria DROP FOREIGN KEY FK_4E10122D6DD36FEA');
        $this->addSql('ALTER TABLE tag_postagem DROP FOREIGN KEY FK_E6D2FF8A6DD36FEA');
        $this->addSql('ALTER TABLE tag_postagem DROP FOREIGN KEY FK_E6D2FF8ABAD26311');
        $this->addSql('DROP TABLE postagem');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_postagem');
        $this->addSql('DROP INDEX IDX_4E10122D6DD36FEA ON categoria');
        $this->addSql('ALTER TABLE categoria ADD date DATETIME NOT NULL, DROP postagem_id, DROP created_at, DROP updated_at');
    }
}
