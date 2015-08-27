update super_stock_db.symbol_name t1
inner join (
	SELECT  symbol
	FROM super_stock_db.history
	group by symbol
	having count(*) > 600
) t2 ON ( t1.symbol = t2.symbol)
SET IS_USE = 1

SET SQL_SAFE_UPDATES = 0;
