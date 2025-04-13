<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413145450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cart_list (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_cart_id INT NOT NULL, quantity INT NOT NULL, size VARCHAR(5) NOT NULL, INDEX IDX_AB2DF8214584665A (product_id), INDEX IDX_AB2DF82142D8D3B5 (user_cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_cart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7122C47EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list ADD CONSTRAINT FK_AB2DF8214584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list ADD CONSTRAINT FK_AB2DF82142D8D3B5 FOREIGN KEY (user_cart_id) REFERENCES user_cart (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_cart ADD CONSTRAINT FK_7122C47EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list DROP FOREIGN KEY FK_AB2DF8214584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list DROP FOREIGN KEY FK_AB2DF82142D8D3B5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_cart DROP FOREIGN KEY FK_7122C47EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_cart
        SQL);
    }
}
