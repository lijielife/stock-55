SELECT DISTINCT
	da.*, msy.SYMBOL, msd.SIDE_NAME, GROUP_CONCAT(ma.MAP_DESC SEPARATOR ',') as MATCHER
	
FROM super_stock_db.DATA_LOG da
LEFT JOIN super_stock_db.LOG_MAP ma on (da.ID = ma.MAP_SRC)
LEFT JOIN super_stock_db.DATA_LOG dad on (dad.ID = ma.MAP_DESC)
LEFT JOIN super_stock_db.MAS_SIDE msd on (msd.id = da.SIDE_ID)
LEFT JOIN super_stock_db.MAS_SYMBOL msy on (msy.id = da.SYMBOL_ID)
LEFT JOIN super_stock_db.MAS_SYMBOL msyd on (msyd.id = dad.SYMBOL_ID)
LEFT JOIN super_stock_db.MAS_BROKER mbk ON (da.BROKER_ID = mbk.ID)
LEFT JOIN super_stock_db.MAS_BROKER mbkd ON (dad.BROKER_ID = mbkd.ID)
WHERE da.USER_ID = 1 
	
	AND (null IS null OR msy.SYMBOL = null)
	AND (null IS null OR mbk.ID = null)
	
GROUP BY da.ID
ORDER BY da.DATE, da.SIDE_ID