select count(a.winner),b.country,a.surface from tennisMatch a inner join PlayerCountry b on a.winner = b.player where a.surface="Clay" group by b.country,a.surface;
