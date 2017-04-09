I mitt sökande efter objektorienterings själ och kärna har jag gjort en del kodövningar där jag använt interfaces och abstrakta klasser. Trait däremot har jag inte testat ännu, kanske för att kod-övningar ofta är ganska korta och inte behöver en sådan konstruktion.

I navbar v2 valde jag att injicera hela $app i navbaren för jag tyckte det stämde bäst med hur resten av ramverket ser ut för tillfället.

Jag vill egentligen ha ett lite annat api till mina ramverksdelar än vad mos verkar föredra. För till exempel navbar skulle jag velat att alla delar som navbar behöver injeceras vid skapandet. Men jag har valt att följa apiet till resten av ramverket, såsom jag uppfattat det, att sätta ihop allt lite procedurellt.

Jag tror inte jag själv skulle välja att ha navbaren som en klass på det sättet som vi gör här. Jag förstår inte riktigt varför? Personligen tycker jag det tillhör vy-delen mer än php-delen. Jag har inte sett denna lösning i något annat ramverk. Som exempel, jag vill ha menyn på två ställen men bara på det ena stället vill jag att det ska markeras vilken sida som användaren är på. Jag vill inte heller att html-kod ska skapas av navbar-klassen då jag anser att detta absolut hör till vy-lagret. Med dessa uppfattningar av hur det ska fungera är navbar-klassen inte stort mer än en glorifierad array som dessutom är krångligare att använda för en ickeprogrammerare.

Oj vad phpcs vill göra koden verbose. Vill jag använda ens lite tekniker från funktionell programmering tycker jag att phpcs slår sönder läsbarheten.
```
Unge-
	fär
		som att
			tvingas 
			skriva
			men-
			ingar
	på 
	detta
viset.
```
Enligt mig verkar det i phpcs inte bara vara inbyggt en kodstil utan även ett kod-paradigm. Phpcs slg till och med sönder den naturliga indenteringen på ett ställe så koden gick från lättläst till nästan oläsbar.