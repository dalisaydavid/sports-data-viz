SELECT t1.winnerCount, t1.country, t1.player
FROM (
	SELECT count(a.winner) as winnerCount,b.country,b.player FROM tennisMatch a inner join PlayerCountry b ON  a.winner = b.player GROUP BY b.player
) t1
INNER JOIN (
	SELECT max(winnerCount) as maxWinnerCount,country 
	FROM (SELECT count(a.winner) as winnerCount,b.country,b.player FROM tennisMatch a inner join PlayerCountry b ON  a.winner = b.player GROUP BY b.player) AS t
	GROUP BY country
) t2 on t1.winnerCount = t2.maxWinnerCount and t1.country = t2.country
ORDER BY t1.country;
