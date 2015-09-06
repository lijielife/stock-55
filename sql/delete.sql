

DELETE FROM super_stock_db.log_map


DELETE FROM super_stock_db.history WHERE symbol IN (

	SELECT * FROM (
		SELECT  symbol
		FROM super_stock_db.history
		group by symbol
		having count(*) > 600
    ) AS p
    
)


DELETE FROM super_stock_db.data_log WHERE ID in (1729
,1816
,2103
,2206)


DELETE FROM super_stock_db.history WHERE symbol in(
'CMO'
,'CNS'
,'CNT'
,'CPALL'
,'CPL'
,'CPN'
,'CRANE'
,'CSL'
,'CSP'
,'CTW'
,'CWT'
,'DCC'
,'DCON'
,'DELTA'
,'DEMCO'
,'DIMET'
,'DNA'
,'DRT'
,'INET'
,'INOX'
,'INSURE'
,'INTUCH'
)