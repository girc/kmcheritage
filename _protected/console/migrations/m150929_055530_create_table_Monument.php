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
        "team" CHARACTER VARYING (100),--ok
		"date" DATE ,--ok
		"monument_id" CHARACTER VARYING (100),--ok
		"monument_name" CHARACTER VARYING (100),--ok
		"monument_type" CHARACTER VARYING (50) , --ok,
		"monument_type_other" CHARACTER  VARYING (100),
		"artitecture_style" CHARACTER  VARYING (100),--ok
		"artitecture_style_other" CHARACTER VARYING (100),
		"dimension" CHARACTER VARYING (100),--ok
		"no_of_storey" INTEGER ,--ok
		"no_of_similar_heritages" INTEGER ,--ok
		"damage_level" CHARACTER VARYING (50) , --ok
		"damage_level_other" CHARACTER  VARYING (100),
		"damage_pinnacle"  CHARACTER VARYING (50), --ok
		"damage_roof" CHARACTER VARYING (50), --ok
		"damage_wall" CHARACTER VARYING (50), --ok
		"damage_door" CHARACTER VARYING (50), --ok
		"damage_wooden_pillar" CHARACTER VARYING (50), --ok
		"damage_plinth" CHARACTER VARYING (50), --ok
		"damage_parts_other" CHARACTER VARYING (100),--ok
		"damage_description" INTEGER,
		"ward_no" INTEGER,--ok
		"tole" CHARACTER  VARYING (100),--ok
		"current_use" CHARACTER  VARYING (200), --ok
		"latest_maintenance_date" CHARACTER  VARYING (100),--ok
		"latest_maintenance_by" CHARACTER  VARYING (100),--ok
		"additional_security" BOOLEAN,--ok may be small int
		"additional_detail" TEXT,--ok may be small int
		"lost_artifacts" BOOLEAN, --ok
		"lost_artifacts_detail" TEXT, --ok
		"monuments_storage" CHARACTER  VARYING (100), --OK
		"local_contact" CHARACTER  VARYING (100),--ok
		"ownership" CHARACTER  VARYING (100),--ok
		"management_committee" CHARACTER  VARYING (100),--ok
		"builders_name" CHARACTER  VARYING (100),--ok
		"built_year" CHARACTER  VARYING (100),--ok
		"cultural_elements" TEXT,--ok
		"history" TEXT,--ok
		"religious" TEXT,--ok
		"social" TEXT,--ok
		"festival_date" CHARACTER  VARYING (255),--ok
		"ethnic_group" CHARACTER  VARYING (100),--ok
		"degradation" TEXT, --ok
		"latitude" DOUBLE PRECISION ,--ok
		"longitude" DOUBLE  PRECISION ,--ok
		"user_id" BIGINT DEFAULT NULL ,--ok

		 CONSTRAINT pk_monument_id PRIMARY KEY (id),
         CONSTRAINT fk_monument_user_id FOREIGN KEY (user_id)
      REFERENCES "user" (id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE SET NULL

);
SQL;
		Yii::$app->db->createCommand($sql)->execute();

    }

    public function safeDown()
    {
		return $this->dropTable('{{%monument}}');

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
