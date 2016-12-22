select count(a.winner),b.player,b.country,a.surface from tennisMatch a inner join PlayerCountry b on a.winner = b.player group by a.winner,a.surface;
