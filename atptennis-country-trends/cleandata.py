__author__ = 'daviddalisay'

def getDB():
    import MySQLdb as mysql
    db = mysql.connect(host="localhost",user="root",db="ATPTennis")
    return db

def pullPlayerCountryData():
    import urllib2 as ul
    f = ul.urlopen("http://www.tennis.com/players/ATP/")
    html = f.readlines()
    players = {}
    db = getDB()
    for line in html:
        if "/player/" in line and " - " in  line:
            lSplit = line.split(">")
            player = lSplit[1].split("<")[0].strip()
            fname = player.split(" ")[0][0] + "."
            lname = player.split(" ")[1]
            player = lname + " " + fname
            country = lSplit[2].split("- ")[1].strip()
            x = db.cursor()
            try:
                x.execute("INSERT INTO PlayerCountry VALUES (%s,%s)",(player,country))
                db.commit()
            except:
                db.rollback()
                db.close()
