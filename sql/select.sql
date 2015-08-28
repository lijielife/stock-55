SELECT * FROM TABLE_NAME

SELECT COUNT(*) FROM TABLE_NAME

SELECT * FROM SYMBOL_NAME WHERE IS_USE = 1 limit 1 

SELECT distinct SYMBOL FROM history  WHERE ORIGIN = 'investor' 


SELECT * FROM history WHERE ORIGIN = 'ruayhoon'  AND SYMBOL = 'CPF'

SELECT COUNT(distinct SYMBOL, ORIGIN ) FROM history

SELECT COUNT(distinct SYMBOL) FROM history WHERE ORIGIN = 'ruayhoon' AND SYMBOL = 'ZMICO'


SELECT SYMBOL, volume FROM history WHERE ORIGIN = 'ruayhoon'  AND SYMBOL = 'ZMICO'

SELECT SYMBOL, volume FROM history WHERE ORIGIN = 'investor' 


DROP TABLE SVOA_HISTORY

SELECT  t2.Id, t2.SYMBOL 
FROM (
	SELECT SYMBOL, COUNT(*)
	FROM history WHERE ORIGIN = 'ruayhoon' AND TIME = '2015-08-05'
	GROUP BY SYMBOL
	HAVING  COUNT(*)  > 1
)t1 JOIN  SYMBOL_NAME t2 ON(t1.SYMBOL = t2.SYMBOL)

ALTER TABLE `test_stock`.`.users`
RENAME TO  `test_stock`.`users` ;


SELECT * FROM NO_DATA                                                                                                                                                                                                                                                                                                                                                                                                                                                                 