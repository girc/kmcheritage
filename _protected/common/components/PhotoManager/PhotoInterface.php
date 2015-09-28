<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/10/2015
 * Time: 1:27 PM
 */

namespace common\components\PhotoManager;


interface PhotoInterface
{
    /**
     * Classes must implement this function to return the table name of database
     */
    public static function tableName();
}