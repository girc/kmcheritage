<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_232747_create_table_monument_photo extends Migration
{
    public function up()
    {
        $tableOptions = null;

        $this->createTable('{{%monument_photo}}', [
            'id' => Schema::TYPE_BIGPK,
            'monument_id'=>Schema::TYPE_BIGINT,
            'title'=>Schema::TYPE_STRING.'(255)',
            'description'=>Schema::TYPE_TEXT,
            'category' => Schema::TYPE_STRING.'(10)',
            'rank'=>Schema::TYPE_INTEGER,
            'filename_original'=>Schema::TYPE_STRING.'(25)',
            'filename_medium'=>Schema::TYPE_STRING.'(25)',
            'filename_thumb'=>Schema::TYPE_STRING.'(25)',
            'latitude'=>Schema::TYPE_DOUBLE,
            'longitude'=>Schema::TYPE_DOUBLE,
            'verified'=>Schema::TYPE_BOOLEAN.' DEFAULT FALSE',
            'created_at'=>'TIMESTAMP WITH TIME ZONE',
            'updated_at'=>'TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()',
            'FOREIGN KEY (monument_id) REFERENCES {{%monument}} (id)
                ON DELETE SET NULL ON UPDATE CASCADE'
        ], $tableOptions);
    }

    /**
    CREATE FUNCTION check_is_timestamp_offset_0(column_name text) RETURNS BOOL
    LANGUAGE plpgsql AS $$
    begin
    IF EXTRACT(TIMEZONE FROM $1) <> '0' THEN
    RAISE EXCEPTION 'Error:  % is required', $2;
    END IF;
    RETURN TRUE;
    END;
    $$;
     */

    public function down()
    {
        $this->dropTable('{{%monument_photo}}');
    }
}
