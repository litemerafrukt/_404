I mitt sökande efter objektorienterings själ och kärna har jag gjort en del kodövningar där jag använt interfaces och abstrakta klasser. Trait däremot har jag inte testat ännu, kanske för att kod-övningar ofta är ganska korta och inte behöver en sådan konstruktion.

I navbar v2 valde jag att injicera hela $app i navbaren för jag tyckte det stämde bäst med hur resten av ramverket ser ut för tillfället.

Jag tror inte jag själv skulle välja att ha navbaren som en klass på det sättet som vi gör här. Jag förstår inte riktigt varför? Personligen tycker jag det tillhör vy-delen mer än php-delen. Jag har inte sett denna lösning i något annat ramverk. Som exempel, jag vill ha menyn på två ställen men bara på det ena stället vill jag att det ska markeras vilken sida som användaren är på. Jag vill inte heller att html-kod ska skapas av navbar-klassen då jag anser att detta hör till vy-lagret. Med dessa uppfattningar av hur det ska fungera är navbar-klassen inte stort mer än en glorifierad array som dessutom är krångligare att använda för en ickeprogrammerare.

Jag tycker inte om att lägga in html-genererande funktioner i klasser, känslan blir att jag blandar ihop logik och presentation. Om jag till exempel vill ändra från bootstrap vill jag bara ändra i mina view-filer inte gå in i mina klasser och ändra. Därför har jag löst min navbar på ett lite speciellt sätt. Min navbar-klass har en metod som heter `mapView` som tar en callback som körs en gång för var route i navbaren. Detta gör att jag kan dela upp logik och presentation och ha flera olika vyer för samma meny (två stycken på min me-sida.) Egentligen anser jag dock att vilka menyer som ska finnas hör till presentationslagret. I tex jinja2 och twig kan man definiera makron i presentationslagret för att uppnå något liknande som här.

Ok, jag löste navbaren genom att ha viewfiler som definieras i config-filen och sedan kan kallas som metoder på navbarklassen genom den magiska __call() metoden. När jag var färdig blev det ett snyggt sätt att använda menyn tex `<?= $app->navbar->headerMenu() ?>` men det kändes väldigt överarbetat och för att lägga till en meny skulle man behöva editera på tre ställen, vyn, config-filen och skriva en ny vy-fil för menyn. Snacka om att gå över ån efter vatten.

Läste sedan att navbar-klassen bara ska underlätta för skapandet av html. Sagt och gjort, det blev en tredje implimentation av navbaren. Denna gång får navbar-klassen bara vara en wrapper över config-arrayen. Det innebär att vy-koden för  menyn skrivs där jag tycker den hör hemma, i vyn. Tredje implimentationen följer uppgiftens 5:e krav, "Välj ett sätt att använda navbaren som känns bekvämt".

Då jag inte riktigt vet om jag tolkat kraven på rätt sätt taggade jag min andra implimentation av navbaren med "grose-navbar" om det finns intresse att kolla hur det såg ut.

Min navbar har inget stöd för undermenyer, det får komma när det behövs.

Vad gäller logik i vyerna håller jag med om att hålla vyerna dumma. Jag försöker hålla logiken i mina egna vyer på en nivå som känns brukligt i de ramverk som jag tittat på tidigare. Det innebär nån if-sats här och där och en eller annan foreach. ---- Nödvändigt att ha med????????

Jag vet inte vad som avses med logik kontra vy i denna kursen men jag tror att min egen definition är färgad av hur jag sett andra ramverk lösa vy-delarna. Om vi tar jinja2, som har en själsfrände i Twig hos php, som exempel så finns där möjlighet till villkor, loopar, includes och makron. Detta kanske kan heta presentationslogik? Personligen anser jag att sådan presentationslogik får finnas i vy-lagret, tex att sätta en css-klass beroende av en medskickad variabel. Detta går igen i js-ramverk som mithril, vue, react med jsx och i flasks jinja2, laravels blade, symfony:s Twig, express js pug, rails haml osv. Listan blir så lång att jag tror jag måste missuppfattat hur logik kontra vyer definieras i denna kursen...

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

För att förstå mer av ramverkets legobitar satt jag en eftermiddag och pillade runt med Url-klassen. Jag gjorde inga (medvetna) ändringar av logiken men lade till en klass för att underlätta läsandet av beslutsträd och konstruerandet av url-er. Alla tester går igenom så Url-klassens api bör fortfarande stämma. Refaktorering när det finns fullständiga test-case är så trevligt! Go TDD!

Hur som helst vill jag ju inte bara testa min modifierade Url-legobit mot test-cases utan även i mitt ramverk. Därför har jag bytt ut anax-light Url-modul mot min modifierade Url-modul.

Efter att ha börjat pula runt med bitarna i ramverket börjar så smått anax-light och jag kunna umgås lite mer avslappnat. För att visa lite god vilja fick projektet en stjärna på github. Jag vet dock inte om jag tycker om view-klassen, känns lite som om den rör sig för mycket över gränsen mellan logik och presentation.

