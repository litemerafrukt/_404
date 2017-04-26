kmom04

Vad gör man om man har ett berg av uppgifter framför sig. Pillar med det gamla naturligtvis!

Jag började med att revidera lite av förra veckans kod. Jag lade till en User-klass och snyggade upp i routerna som jag skrivit.

Istället för null som användare använder jag ett Gäst-konto om sessionen inte redan har en användare.

Det finns tre nivåer på användare, admin, user och guest. Gäst-användare har inte rätt att göra någonting och likställs med ingen användare.

Jag kunde inte heller sluta pilla i anax-light modulerna. Medan jag reviderade kmom03 fick jag en underlig bugg som berodde på att jag inte reflekterat över flödet av en request genom ramverket. Jag hade reflekterat över att alla handlers var procedurer snarare än funktioner men tänkt att det går att betrakta hela requesten, från index.php till svar, som ett slags funktionsanrop.

Buggen kom av att jag inte såg till att sända något från en handler. Så jag gick in och tittade i `Response` klassen och såg att den var nästan helt förberedd för ett förfarande där man ser till att returnera ett response från sina handlers.

Så jag bestämde mig för att skriva lite nytt i respons-klassen och göra om mina route-handlers till att, nästan, alltid returnera ett response istället för att själv sända svaret. Jag använder min nya respons-klass istället för den composer-installerade.

Min nya respons-klass ska vara helt bakåtkompatibel med canax respons-klass men den är inte helt färdig och inte enhetstestad så det får vänta lite med eventuell pull request.

Block delen av uppgiften var i stort sett odefinierad och jag förstod inte uppgiftsskaparens tanke riktigt. Min definition blev:

En admin ska kunna gå in och CRUD:a block. I en template kan man skriva $app->blocks->exists("blocknamn") och $app->block->html("blocknamn") för att hämta blocket till den platsen.

Jag valde att skapa tre tabeller i databasen då jag tyckte att det var tre distinkta typer av content med olika syften.
