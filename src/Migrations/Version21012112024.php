<?php declare(strict_types=1);

namespace WebsiteSQL\Migrations;

use Medoo\Medoo;

class Version21012112024
{
    public function up(Medoo $database)
    {
        // Create 'WebSQL_AdminLog' table
        $database->create('WebSQL_AdminLog', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'Value' => ['TEXT', 'NOT NULL'],
            'Version' => ['VARCHAR(12)', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_Content' table
        $database->create('WebSQL_Content', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'AuthorID' => ['INT', 'NOT NULL'],
            'PostDate' => ['DATETIME', 'NOT NULL'],
            'PostTitle' => ['VARCHAR(225)', 'NOT NULL'],
            'PostContent' => ['TEXT', 'NOT NULL'],
            'PostExcerpt' => ['VARCHAR(225)', 'NOT NULL'],
            'PostStatus' => ['VARCHAR(40)', 'NOT NULL', 'DEFAULT '. $database->quote('publish')],
            'PostProtected' => ['INT', 'NOT NULL'],
            'PostSlug' => ['VARCHAR(225)', 'NOT NULL', 'UNIQUE KEY'],
            'PostTemplate' => ['VARCHAR(225)', 'NOT NULL'],
            'PostImage' => ['VARCHAR(225)', 'NOT NULL'],
            'PostCustom' => ['TEXT', 'NOT NULL'],
            'PostParent' => ['INT', 'NOT NULL', 'DEFAULT 0'],
            'PostType' => ['VARCHAR(255)', 'NOT NULL', 'DEFAULT false'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_ContentTypes' table
        $database->create('WebSQL_ContentTypes', [
            'ID' => ['VARCHAR(255)', 'NOT NULL', 'PRIMARY KEY'],
            'Icon' => ['VARCHAR(255)', 'NOT NULL'],
            'Color' => ['VARCHAR(40)', 'NOT NULL'],
            'PluginPath' => ['VARCHAR(255)', 'NOT NULL'],
        ]);

        // Create 'WebSQL_Settings' table
        $database->create('WebSQL_Settings', [
            'ID' => ['VARCHAR(255)', 'NOT NULL', 'PRIMARY KEY'],
            'Value' => ['TEXT', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_TempContent' table
        $database->create('WebSQL_TempContent', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'ContentID' => ['INT', 'NOT NULL'],
            'PostDate' => ['DATETIME', 'NOT NULL'],
            'PostTitle' => ['VARCHAR(225)', 'NOT NULL'],
            'PostContent' => ['VARCHAR(2000)', 'NOT NULL'],
            'PostExcerpt' => ['VARCHAR(225)', 'NOT NULL'],
            'PostStatus' => ['VARCHAR(40)', 'NOT NULL', 'DEFAULT '. $database->quote('publish')],
            'PostProtected' => ['INT', 'NOT NULL'],
            'PostSlug' => ['VARCHAR(225)', 'NOT NULL'],
            'PostTemplate' => ['VARCHAR(225)', 'NOT NULL'],
            'PostImage' => ['VARCHAR(225)', 'NOT NULL'],
            'PostCustom' => ['TEXT', 'NOT NULL'],
            'PostParent' => ['INT', 'NOT NULL', 'DEFAULT 0'],
            'PostType' => ['VARCHAR(255)', 'NOT NULL', 'DEFAULT false'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_Tokens' table
        $database->create('WebSQL_Tokens', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'UserID' => ['INT', 'NOT NULL'],
            'Service' => ['VARCHAR(25)', 'NOT NULL', 'DEFAULT '. $database->quote('login')],
            'Token' => ['VARCHAR(100)', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_TransactionLog' table
        $database->create('WebSQL_TransactionLog', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'UserID' => ['INT', 'NOT NULL'],
            'Action' => ['VARCHAR(225)', 'NOT NULL'],
            'Content' => ['TEXT', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_Uploads' table
        $database->create('WebSQL_Uploads', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'AuthorID' => ['INT', 'NOT NULL'],
            'FileSize' => ['VARCHAR(40)', 'NOT NULL'],
            'FileHash' => ['VARCHAR(40)', 'NOT NULL'],
            'FileName' => ['VARCHAR(255)', 'NOT NULL'],
            'FileExtension' => ['VARCHAR(255)', 'NOT NULL'],
            'FileDescription' => ['TEXT', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_UserPermissions' table
        $database->create('WebSQL_UserPermissions', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'UserID' => ['INT', 'NOT NULL'],
            'CapabilityName' => ['VARCHAR(255)', 'NOT NULL'],
            'Browse' => ['INT', 'NOT NULL'],
            'Edit' => ['INT', 'NOT NULL'],
            'Add' => ['INT', 'NOT NULL'],
            'Delete' => ['INT', 'NOT NULL'],
        ]);        

        // Create 'wsql_users' table
        $database->create('wsql_users', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'firstname' => ['VARCHAR(100)', 'NOT NULL'],
            'lastname' => ['VARCHAR(100)', 'NOT NULL'],
            'email' => ['VARCHAR(250)', 'NOT NULL', 'UNIQUE'],
            'realm' => ['VARCHAR(50)', 'NOT NULL'],
            'password' => ['TEXT', 'NOT NULL'],
            'approved' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'locked' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'email_verified' => ['INT(11)', 'NOT NULL', 'DEFAULT 0'],
            'email_verify_code' => ['VARCHAR(250)', 'NULL'],
            'email_verify_expiry' => ['TIMESTAMP', 'NULL'],
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'],
        ]);

        // Create 'WebSQL_WebsiteLog' table        
        $database->create('WebSQL_WebsiteLog', [
            'ID' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'Value' => ['TEXT', 'NOT NULL'],
            'Version' => ['VARCHAR(12)', 'NOT NULL'],
            'TimeStamp' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP', 'ON UPDATE CURRENT_TIMESTAMP'],
        ]);
        
        // Create 'wsql_modules' table
        $database->create('wsql_modules', [
            'id' => ['INT', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
            'slug' => ['VARCHAR(100)', 'NOT NULL', 'UNIQUE'], // Slug should be unique for each module
            'name' => ['VARCHAR(100)', 'NOT NULL'],
            'description' => ['TEXT', 'NULL'], // Description is optional
            'version' => ['VARCHAR(20)', 'NOT NULL'],
            'author' => ['VARCHAR(250)', 'NOT NULL'],
            'enabled' => ['INT', 'NOT NULL', 'DEFAULT 0'], // Indicates if the module is enabled or not
            'created_at' => ['TIMESTAMP', 'NOT NULL', 'DEFAULT CURRENT_TIMESTAMP'], // Timestamp for module creation
        ]);
    }

    public function down(Medoo $database)
    {
        // Drop 'WebSQL_AdminLog' table
        $database->drop('WebSQL_AdminLog');

        // Drop 'WebSQL_Content' table
        $database->drop('WebSQL_Content');

        // Drop 'WebSQL_ContentTypes' table
        $database->drop('WebSQL_ContentTypes');

        // Drop 'WebSQL_Settings' table
        $database->drop('WebSQL_Settings');

        // Drop 'WebSQL_TempContent' table
        $database->drop('WebSQL_TempContent');

        // Drop 'WebSQL_Tokens' table
        $database->drop('WebSQL_Tokens');

        // Drop 'WebSQL_TransactionLog' table
        $database->drop('WebSQL_TransactionLog');

        // Drop 'WebSQL_Uploads' table
        $database->drop('WebSQL_Uploads');

        // Drop 'WebSQL_UserPermissions' table
        $database->drop('WebSQL_UserPermissions');

        // Drop 'WebSQL_WebsiteLog' table
        $database->drop('WebSQL_WebsiteLog');

        // Drop 'wsql_modules' table
        $database->drop('wsql_modules');

        // Drop 'wsql_users' table
        $database->drop('wsql_users');
    }
}