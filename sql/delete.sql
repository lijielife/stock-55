

DELETE FROM super_stock_db.history WHERE symbol IN (

	SELECT * FROM (
		SELECT  symbol
		FROM super_stock_db.history
		group by symbol
		having count(*) > 600
    ) AS p
    
)
