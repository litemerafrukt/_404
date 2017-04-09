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

Jag tycker inte om att lägga in html-genererande funktioner i klasser, känslan blir att jag blandar ihop logik och presentation. Därför har jag löst min navbar på ett lite speciellt sätt. Min navbar-klass har en metod som heter `mapView` som tar en callback som körs en gång för var route i navbaren. Detta gör att jag kan dela upp logik och presentation och ha flera olika vyer för samma meny (två stycken på min me-sida.) Egentligen anser jag dock att vilka menyer som ska finnas hör till presentationslagret. I tex jinja2 och twig kan man definiera makron i presentationslagret för att uppnå något liknande som här.

Min navbar har inget stöd för undermenyer, det får komma när det behövs.

För att förstå mer av ramverkets legobitar satt jag en eftermiddag och pillade runt med Url-klassen. Jag gjorde inga medvetna ändringar av logiken men lade till en klass för att underlätta läsandet av beslutsträd och konstruerandet av url-er. Alla tester går igenom så Url-klassens api bör fortfarande stämma. Refaktorering när det finns fullständiga test-case är så trevligt!

Hur som helst vill jag ju inte bara testa min modifierade Url-legobit mot test-cases utan även i mitt ramverk. Därför har jag bytt ut anax-light standard-variant mot min egen.
