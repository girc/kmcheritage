<?php
namespace console\controllers;

use yii\helpers\Console;
use yii\console\Controller;
use Yii;

/**
 * Creates base rbac authorization data for our application.
 * -----------------------------------------------------------------------------
 * Creates 6 roles:
 *
 * - theCreator : you, developer of this site (super admin)
 * - admin      : your direct clients, administrators of this site
 * - editor     : editor of this site
 * - support    : support staff
 * - premium    : premium member of this site
 * - member     : user of this site who has registered his account and can log in
 *
 * Creates 7 permissions:
 *
 * - usePremiumContent  : allows premium members to use premium content
 * - createArticle      : allows editor+ roles to create articles
 * - updateOwnArticle   : allows editor+ roles to update own articles
 * - updateArticle      : allows admin+ roles to update all articles
 * - deleteArticle      : allows admin+ roles to delete articles
 * - adminArticle       : allows admin+ roles to manage articles
 * - manageUsers        : allows admin+ roles to manage users (CRUD plus role assignment)
 *
 * Creates 1 rule:
 *
 * - AuthorRule : allows editor+ roles to update their own content
 */
class DatabaseController extends Controller
{
    /**
     * Initializes the Database
     */
    public function actionInit($host,$port,$db,$username,$password)
    {
        $command="createdb -h $host -p $port -U $username  $db";
        exec($command);
       // $this->createDatabase($host,$port, $username, $password);
       // $this->createExtensions($host,$port,$db, $username, $password,['postgis','hstore']);
    }

    private function createDatabase($host,$port,$username,$password){
        // connecting to database
        $dsn = "pgsql:host=$host;port=$port;user=$username;password=$password";
        // create a PostgreSQL database connection
        $conn = new \PDO($dsn);
        try{
            $conn->exec("CREATE DATABASE kmcheritage;");
            $this->stdout("\nDatabase created successfully.\n", Console::FG_GREEN);
        }catch (\PDOException $e){
            // report error message
            $this->stdout("\nError creating Database!.\n", Console::FG_RED);
            throw $e;
        }
    }

    private function createExtensions($host,$port,$db,$username,$password,$extensions){
        foreach($extensions as $extension){
            // connecting to database
            $dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$username;password=$password";
            // create a PostgreSQL database connection
            $conn = new \PDO($dsn);
            try{
                $conn->exec("CREATE EXTENSION $extension;");
                $this->stdout("\nExtension $extension installed successfully.\n", Console::FG_GREEN);
            }catch (\PDOException $e){
                // report error message
                $this->stdout("\nError installing Extension $extension!.\n", Console::FG_RED);
                throw $e;
            }
        }
    }
}