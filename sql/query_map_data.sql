SELECT da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price
FROM super_stock_db.data_log da
LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_SRC)
LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price
