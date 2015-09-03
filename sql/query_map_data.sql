SELECT 
	da.`ID` as ID_SRC , da.`SIDE_ID` as SIDE_ID_SRC 
    , ms.`SIDE_CODE` as SIDE_CODE_SRC, ms.`SIDE_NAME` as SIDE_NAME_SRC
    , da.`SYMBOL_ID` as SYMBOL_ID_SRC, msy.`SYMBOL` as SYMBOL_SRC
    , da.`VOLUME` as VOLUME_SRC
    , da.`PRICE` as PRICE_SRC, da.`AMOUNT` as AMOUNT_SRC, da.`VAT` as VAT_SRC
	, da.`NET_AMOUNT` as NET_AMOUNT_SRC, da.`DATE` as DATE_SRC, da.`BROKER_ID` as BROKER_ID_SRC 
        , da.`MAP_VOL` as MAP_VOL_SRC
  
        , ma.`ID`as MAP_ID , ma.`MAP_SRC` , ma.`MAP_DESC` , ma.`MAP_VOL` 
  
	,dad.`ID` as ID_DESC , dad.`SIDE_ID` as SIDE_ID_DESC
    , msd.`SIDE_CODE` as SIDE_CODE_DESC, msd.`SIDE_NAME` as SIDE_NAME_DESC
    , dad.`SYMBOL_ID` as SYMBOL_ID_DESC, msyd.`SYMBOL` as SYMBOL_DESC
    , dad.`VOLUME` as VOLUME_DESC
    , dad.`PRICE` as PRICE_DESC, dad.`AMOUNT` as AMOUNT_DESC, dad.`VAT` as VAT_DESC
	, dad.`NET_AMOUNT` as NET_AMOUNT_DESC , dad.`DATE` as DATE_DESC , dad.`BROKER_ID` as BROKER_ID_DESC 
        , da.`MAP_VOL` as MAP_VOL_DESC

        FROM super_stock_db.data_log da
        LEFT JOIN super_stock_db.log_map ma on (da.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.data_log dad on (dad.ID = ma.MAP_DESC)
        LEFT JOIN super_stock_db.mas_side ms on (ms.id = da.SIDE_ID)
        LEFT JOIN super_stock_db.mas_side msd on (msd.id = dad.SIDE_ID)
        LEFT JOIN super_stock_db.mas_symbol msy on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.mas_symbol msyd on (msyd.id = dad.SYMBOL_ID)
        LEFT JOIN super_stock_db.mas_broker mbk on (msy.id = da.SYMBOL_ID)
        LEFT JOIN super_stock_db.mas_broker mbkd on (msyd.id = dad.SYMBOL_ID)
        ORDER BY da.BROKER_ID, da.symbol_id, da.SIDE_ID, da.price