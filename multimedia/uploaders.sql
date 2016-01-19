select date('{from_timestamp}') as date,
	count(distinct img_user) as {wiki_db}
	from {wiki_db}.image;
