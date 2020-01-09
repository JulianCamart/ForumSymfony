<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200109130110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, avatar_img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cat_table (id INT AUTO_INCREMENT NOT NULL, cat_name VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_table (id INT AUTO_INCREMENT NOT NULL, forum_author_id INT DEFAULT NULL, category_id INT NOT NULL, forum_name VARCHAR(75) NOT NULL, forum_description TINYTEXT NOT NULL, INDEX IDX_EE5D2991C83C774D (forum_author_id), INDEX IDX_EE5D299112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_table (id INT AUTO_INCREMENT NOT NULL, thread_id INT NOT NULL, post_author_id INT DEFAULT NULL, post_text LONGTEXT NOT NULL, post_time DATETIME NOT NULL, report INT NOT NULL, INDEX IDX_613203A9E2904019 (thread_id), INDEX IDX_613203A9571B8DEC (post_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_message_table (id INT AUTO_INCREMENT NOT NULL, message_author_id INT DEFAULT NULL, message_receiver_id INT DEFAULT NULL, message_text LONGTEXT NOT NULL, message_time DATETIME NOT NULL, viewed TINYINT(1) NOT NULL, archivedByReceiver TINYINT(1) NOT NULL, archivedByAuthor TINYINT(1) NOT NULL, deletedByReceiver TINYINT(1) NOT NULL, deletedByAuthor TINYINT(1) NOT NULL, INDEX IDX_E7D76C299CCCF52D (message_author_id), INDEX IDX_E7D76C29AD2CB34F (message_receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_table (id INT AUTO_INCREMENT NOT NULL, report_author_id INT DEFAULT NULL, report_post_id INT DEFAULT NULL, report_thread_id INT DEFAULT NULL, report_user_id INT DEFAULT NULL, report_text TINYTEXT NOT NULL, validation TINYINT(1) NOT NULL, INDEX IDX_DC35883FD8C19E03 (report_author_id), INDEX IDX_DC35883F2B107E2C (report_post_id), INDEX IDX_DC35883FCC242D01 (report_thread_id), INDEX IDX_DC35883FC7F7AE95 (report_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thread_table (id INT AUTO_INCREMENT NOT NULL, forum_id INT NOT NULL, thread_author_id INT DEFAULT NULL, thread_name VARCHAR(100) NOT NULL, thread_text LONGTEXT NOT NULL, thread_time DATETIME NOT NULL, report INT NOT NULL, INDEX IDX_FCAD907D29CCBAD0 (forum_id), INDEX IDX_FCAD907D791FDCA7 (thread_author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(250) NOT NULL, roles JSON NOT NULL, report INT NOT NULL, verif_email TINYINT(1) DEFAULT \'0\' NOT NULL, member_since_time DATETIME NOT NULL, password_requested_at DATETIME DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_table ADD CONSTRAINT FK_EE5D2991C83C774D FOREIGN KEY (forum_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_table ADD CONSTRAINT FK_EE5D299112469DE2 FOREIGN KEY (category_id) REFERENCES cat_table (id)');
        $this->addSql('ALTER TABLE post_table ADD CONSTRAINT FK_613203A9E2904019 FOREIGN KEY (thread_id) REFERENCES thread_table (id)');
        $this->addSql('ALTER TABLE post_table ADD CONSTRAINT FK_613203A9571B8DEC FOREIGN KEY (post_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_message_table ADD CONSTRAINT FK_E7D76C299CCCF52D FOREIGN KEY (message_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_message_table ADD CONSTRAINT FK_E7D76C29AD2CB34F FOREIGN KEY (message_receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_table ADD CONSTRAINT FK_DC35883FD8C19E03 FOREIGN KEY (report_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report_table ADD CONSTRAINT FK_DC35883F2B107E2C FOREIGN KEY (report_post_id) REFERENCES post_table (id)');
        $this->addSql('ALTER TABLE report_table ADD CONSTRAINT FK_DC35883FCC242D01 FOREIGN KEY (report_thread_id) REFERENCES thread_table (id)');
        $this->addSql('ALTER TABLE report_table ADD CONSTRAINT FK_DC35883FC7F7AE95 FOREIGN KEY (report_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE thread_table ADD CONSTRAINT FK_FCAD907D29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum_table (id)');
        $this->addSql('ALTER TABLE thread_table ADD CONSTRAINT FK_FCAD907D791FDCA7 FOREIGN KEY (thread_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE forum_table DROP FOREIGN KEY FK_EE5D299112469DE2');
        $this->addSql('ALTER TABLE thread_table DROP FOREIGN KEY FK_FCAD907D29CCBAD0');
        $this->addSql('ALTER TABLE report_table DROP FOREIGN KEY FK_DC35883F2B107E2C');
        $this->addSql('ALTER TABLE post_table DROP FOREIGN KEY FK_613203A9E2904019');
        $this->addSql('ALTER TABLE report_table DROP FOREIGN KEY FK_DC35883FCC242D01');
        $this->addSql('ALTER TABLE forum_table DROP FOREIGN KEY FK_EE5D2991C83C774D');
        $this->addSql('ALTER TABLE post_table DROP FOREIGN KEY FK_613203A9571B8DEC');
        $this->addSql('ALTER TABLE private_message_table DROP FOREIGN KEY FK_E7D76C299CCCF52D');
        $this->addSql('ALTER TABLE private_message_table DROP FOREIGN KEY FK_E7D76C29AD2CB34F');
        $this->addSql('ALTER TABLE report_table DROP FOREIGN KEY FK_DC35883FD8C19E03');
        $this->addSql('ALTER TABLE report_table DROP FOREIGN KEY FK_DC35883FC7F7AE95');
        $this->addSql('ALTER TABLE thread_table DROP FOREIGN KEY FK_FCAD907D791FDCA7');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE cat_table');
        $this->addSql('DROP TABLE forum_table');
        $this->addSql('DROP TABLE post_table');
        $this->addSql('DROP TABLE private_message_table');
        $this->addSql('DROP TABLE report_table');
        $this->addSql('DROP TABLE thread_table');
        $this->addSql('DROP TABLE user');
    }
}
