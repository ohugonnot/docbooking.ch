<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411160234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctor_connections (doctor_id INT NOT NULL, connected_doctor_id INT NOT NULL, INDEX IDX_38C948EE87F4FB17 (doctor_id), INDEX IDX_38C948EEAA32E620 (connected_doctor_id), PRIMARY KEY(doctor_id, connected_doctor_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE doctor_connections ADD CONSTRAINT FK_38C948EE87F4FB17 FOREIGN KEY (doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE doctor_connections ADD CONSTRAINT FK_38C948EEAA32E620 FOREIGN KEY (connected_doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE doctor ADD rcc_number VARCHAR(255) DEFAULT NULL, ADD reimbursed_by VARCHAR(255) DEFAULT NULL, ADD payment_by VARCHAR(255) DEFAULT NULL, ADD member_of VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE doctor_connections DROP FOREIGN KEY FK_38C948EE87F4FB17');
        $this->addSql('ALTER TABLE doctor_connections DROP FOREIGN KEY FK_38C948EEAA32E620');
        $this->addSql('DROP TABLE doctor_connections');
        $this->addSql('ALTER TABLE doctor DROP rcc_number, DROP reimbursed_by, DROP payment_by, DROP member_of');
    }
}
