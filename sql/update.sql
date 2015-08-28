update super_stock_db.symbol_name t1
inner join (
	SELECT  symbol
	FROM super_stock_db.history
	group by symbol
	having count(*) > 600
) t2 ON ( t1.symbol = t2.symbol)
SET IS_USE = 1

SET SQL_SAFE_UPDATES = 0;




update SYMBOL_NAME SET IS_USE = 1 where id in (
216
,218
,219
,222
,226
,227
,229
,231
,232
,235
,236
,237
,238
,239
,240
,241
,242
,244
,316
,317
,318
,319
)