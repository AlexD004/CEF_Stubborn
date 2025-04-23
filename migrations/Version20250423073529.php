<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423073529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD stock_xs INT NOT NULL, ADD stock_s INT NOT NULL, ADD stock_m INT NOT NULL, ADD stock_l INT NOT NULL, ADD stock_xl INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_cart DROP created_at
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_list ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP stock_xs, DROP stock_s, DROP stock_m, DROP stock_l, DROP stock_xl
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_cart ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        SQL);
    }
}
