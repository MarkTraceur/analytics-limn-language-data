select date('{from_timestamp}') as date,
	count(*) as {wiki_db}
	from {wiki_db}.image;
