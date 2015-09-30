<?php

use yii\db\Schema;
use yii\db\Migration;

class m150929_055530_create_table_Monument extends Migration
{
    public function safeUp()
    {
        $sql=<<<SQL
CREATE TABLE monument(
        "id" BIGSERIAL,
        "team" CHARACTER VARYING (100),
		"date" DATE ,
		"monument_id" CHARACTER VARYING (100),
		"monument_name" CHARACTER VARYING (100),
		"monument_type" INTEGER ,
		"monument_type_other" CHARACTER  VARYING (100),
		"artitecture_style" CHARACTER VARYING (100),
		"dimension" CHARACTER VARYING (100),
		"no_of_storey" INTEGER ,
		"no_of_similar_heritages" INTEGER ,
		"damage_level" INTEGER ,
		"pinnacle" INTEGER,
		"roof" INTEGER,
		"wall" INTEGER,
		"door" INTEGER,
		"wooden_pillar" INTEGER,
		"plinth" INTEGER,
		"parts_others" INTEGER,
		"ward_no" INTEGER,
		"tole" CHARACTER  VARYING (100),
		"current_use" CHARACTER  VARYING (200),
		"latest_maintenance_date" CHARACTER  VARYING (100),,
		"latest_maintenance_by" CHARACTER  VARYING (100),
		"additional_security" INTEGER,
		"lost_artifacts" TEXT,
		"monuments_storage" CHARACTER  VARYING (100),
		"local_contact" CHARACTER  VARYING (100),
		"ownership" CHARACTER  VARYING (100),
		"management_committee" CHARACTER  VARYING (100),
		"builders_name" CHARACTER  VARYING (100),
		"built_year" CHARACTER  VARYING (100),
		"cultural_elements" TEXT,
		"history" TEXT,
		"religious" TEXT,
		"social" TEXT,
		"festival_date" CHARACTER  VARYING (100),
		"ethnic_group" CHARACTER  VARYING (100),
		"degradation" TEXT,
		"latitude" DOUBLE PRECISION ,
		"longitude" DOUBLE  PRECISION ,
		"user_id" BIGINT,

		 CONSTRAINT pk_monument_id PRIMARY KEY (id),
         CONSTRAINT fk_monument_user_id FOREIGN KEY (user_id)
      		REFERENCES user (id) MATCH SIMPLE
      		ON UPDATE CASCADE ON DELETE SET NULL

);
SQL;

    }

    public function safeDown()
    {
        echo "m150929_055530_create_table_Monument cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
