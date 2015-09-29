<?php
namespace console\controllers;

use yii\helpers\ArrayHelper;
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
    public $host;
    public $port;
    public $username;
    public $db;

    public function options($actionID)
    {
        // $actionId might be used in subclasses to provide options specific to action id
        return [
            'host', 'port', 'username', 'db'
        ];
    }
    /**
     * Initializes the Database
     */
    public function actionInit()
    {
        $command="createdb -h $this->host -p $this->port -U $this->username  $this->db";
        exec($command);
    }

    /**
     * Initializes the Database
     */
    public function actionDrop()
    {
        $command="dropdb -h $this->host -p $this->port -U $this->username  $this->db";
        exec($command);
    }

}