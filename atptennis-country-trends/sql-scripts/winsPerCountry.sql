select count(a.Winner),b.country from tennisMatch a inner join PlayerCountry b on a.Winner = b.player group by b.country;
