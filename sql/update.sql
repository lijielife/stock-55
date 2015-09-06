update super_stock_db.MAS_SYMBOL t1
inner join (
	SELECT  symbol
	FROM super_stock_db.history
	group by symbol
	having count(*) > 600
) t2 ON ( t1.symbol = t2.symbol)
SET IS_USE = 1

SET SQL_SAFE_UPDATES = 0;

update data_log set MAP_VOL = 0 , MAP_AVG = 0