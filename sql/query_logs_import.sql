SELECT * FROM super_stock_db.data_log;

insert into `data_log` (`side_id`, `symbol_id`, `volume`, `price`, `amount`, `vat`
, `at_pay`, `net_amount`, `date`, `broker_id`, `is_dw`, `user_id`, `updated_at`
, `updated_by`, `created_at`, `created_by`) 
values (2, 10, 1, 237, 237, 0.05, 0, 236.6, 2015-08-14, 1, 0, 1, 2015-08-29 02:17:47, 1, 2015-08-29 02:17:47, 1));


SELECT * FROM WHERE 


SELECT ms.side_name as side, msy.symbol, dl.volume, dl.price, dl.amount, dl.vat
, dl.at_pay, dl.net_amount, dl.date, mbk.broker_name  as broker, us.name
FROM DATA_LOG dl
LEFT JOIN MAS_SYMBOL msy ON (dl.SYMBOL_ID = msy.ID)
LEFT JOIN MAS_BROKER mbk ON (dl.BROKER_ID = mbk.ID)
LEFT JOIN MAS_SIDE ms ON (dl.SIDE_ID = ms.ID)
LEFT JOIN USERS us ON (dl.USER_ID = us.ID)
WHERE dl.UPDATED_AT = (
	SELECT MAX(UPDATED_AT) FROM DATA_LOG WHERE UPDATED_AT IS NOT NULL
) AND dl.USER_ID = 1
ORDER BY SYMBOL, BROKER, VOLUME
;

update DATA_LOG set CREATED_AT = now()