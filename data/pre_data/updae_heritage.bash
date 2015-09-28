#!/bin/bash
psql -U postgres -h localhost -d kmc_dev -c "
	CREATE TEMP TABLE 
		tmp_x (
			inventory_id  text,
			house_no text,
			ward_no integer,
			owner_name character varying(255),
			contact_no character varying(255),
			present_use text,
			construction_date_age character varying(255),
			renovation_history text,
			architectural_style character varying(255),
			important_features text,
			present_physical_conditions text,
			historical_socio_cultural_significance text,
			description text,
			items_to_be_preserved_before text,
			surveyor_opinion_before text,
			recorded_by text,
			old_date text,
			photo_before1 text,
			photo_before2 text,
			photo_before3 text
		);
	
	COPY 
		tmp_x(
			inventory_id,
			house_no,
			ward_no,
			owner_name,
			contact_no,
			present_use,
			construction_date_age,
			renovation_history,
			architectural_style,
			important_features,
			present_physical_conditions,
			historical_socio_cultural_significance,
			description,
			items_to_be_preserved_before,
			surveyor_opinion_before,
			recorded_by,
			old_date,
			photo_before1,
			photo_before2,
			photo_before3
		) 
	FROM 
		'C:\xampp\htdocs\kmc\data\pre_data\group2\predata_with_header.csv' WITH DELIMITER ',' CSV HEADER;

	WITH upd AS (
		UPDATE 
			building_before
		SET inventory_id 							=	tmp_x.inventory_id,
			house_no 								=	tmp_x.house_no,
			ward_no									=	tmp_x.ward_no,
			owner_name								=	tmp_x.owner_name,
			contact_no								=	tmp_x.contact_no,
			present_use								=	tmp_x.present_use,
			construction_date_age					= 	tmp_x.construction_date_age,
			renovation_history						=	tmp_x.renovation_history,
			architectural_style						=	tmp_x.architectural_style,
			important_features						=	tmp_x.important_features,
			present_physical_conditions				=	tmp_x.present_physical_conditions,
			historical_socio_cultural_significance  =	tmp_x.historical_socio_cultural_significance,
			description								=	tmp_x.description,
			items_to_be_preserved_before			=	tmp_x.items_to_be_preserved_before,
			surveyor_opinion_before					=	tmp_x.surveyor_opinion_before,
			recorded_by								= 	tmp_x.recorded_by,
			old_date								=	tmp_x.old_date,
			photo_before1								=	tmp_x.photo_before1,
			photo_before2								=	tmp_x.photo_before2,
			photo_before3								=	tmp_x.photo_before3
		FROM 
			tmp_x
		WHERE 
			building_before.inventory_id = tmp_x.inventory_id
		RETURNING 
			tmp_x.inventory_id, 
			tmp_x.house_no
		)
	
	INSERT INTO 
		building_before (
			inventory_id,
			house_no,
			ward_no,
			owner_name,
			contact_no,
			present_use,
			construction_date_age,
			renovation_history,
			architectural_style,
			important_features,
			present_physical_conditions,
			historical_socio_cultural_significance,
			description,items_to_be_preserved_before,
			surveyor_opinion_before,
			recorded_by,
			old_date,
			photo_before1,
			photo_before2,
			photo_before3
		)
	SELECT 
		tmp_x.inventory_id,
		tmp_x.house_no,
		tmp_x.ward_no,
		tmp_x.owner_name,
		tmp_x.contact_no,
		tmp_x.present_use,
		tmp_x.construction_date_age,
		tmp_x.renovation_history,
		tmp_x.architectural_style,
		tmp_x.important_features,
		tmp_x.present_physical_conditions,
		tmp_x.historical_socio_cultural_significance,
		tmp_x.description,
		tmp_x.items_to_be_preserved_before,
		tmp_x.surveyor_opinion_before,
		tmp_x.recorded_by,
		tmp_x.old_date,
		tmp_x.photo_before1,
		tmp_x.photo_before2,
		tmp_x.photo_before3
		
	FROM 
		tmp_x 
	LEFT JOIN 
		upd 
	ON 
		upd.inventory_id = tmp_x.inventory_id
	WHERE 
		upd.inventory_id IS NULL
		AND
		tmp_x.inventory_id IS NOT NULL
"