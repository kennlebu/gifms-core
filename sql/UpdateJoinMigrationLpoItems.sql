update lpo_items i 
	JOIN lpos l 
	on i.lpo_migration_id = l.migration_id
	set i.lpo_id = l.id 