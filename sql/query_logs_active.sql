SELECT msy.symbol, ms.side_name as side, dl.volume, dl.price, dl.amount, dl.vat, dl.net_amount, dl.date, mbk.broker_name  as broker, us.name
FROM DATA_LOG dl
LEFT JOIN MAS_SYMBOL msy ON (dl.SYMBOL_ID = msy.ID)
LEFT JOIN MAS_BROKER mbk ON (dl.BROKER_ID = mbk.ID)
LEFT JOIN MAS_SIDE ms ON (dl.SIDE_ID = ms.ID)
LEFT JOIN USERS us ON (dl.USER_ID = us.ID)
WHERE dl.UPDATED_AT = (
		SELECT MAX(UPDATED_AT) FROM DATA_LOG WHERE UPDATED_AT IS NOT NULL
) 
AND dl.USER_ID = 1
ORDER BY SYMBOL, dl.date, SIDE, BROKER