kmom04

Bara administratörer kan lägga till innehåll.

Det finns länkar i navbaren till "Blog" och "Sidor". Där visas de publicerade artiklarna av respektive typ. De som ligger under sidor är inte tänkt att visas i en sådan lista utan visas på helsida.

Blog och page behandlas olika. Page är "anything goes" och där körs ingen escape. Går man vidare till en separat page så får man upp hur den är meningen att renderas och det görs med ramverkets view-modul.

Block-delen av uppgiften förstod jag inte syftet med då inget på min sida är uppbyggt på det sättet. Kravet innehöll inte heller någon riktigt förklaring till hur det var tänkt att användas. Var någonstans men inte hur och inte hur det kan tänkas underlätta. Men jag lade in att man kan söka efter block. Titeln för blocket används för att söka rätt på blocket. Först publicerade block som inte är raderat returneras. Detta gör att man tex kan ha ett nyhetsnotisblock. Döp alla blocken till tex "news" och lägg in att de ska raderas en dag i taget så har du automatiska nyhetsuppdateringar.

Mina kontaktuppgifter i footern är ett block.

Blocks är en ny klass för att hantera block. Denna använder mitt nya $tlz-objekt och för att få någon överensstämmelse med tidigare kod valde jag att göra interface och trait motsvarande som för $app till $tlz.

Jag tolkade krav 5, angående felhantering av sluggen, att om sluggen är tom och innehållstypen är blog så försöker jag skapa en slug. Om detta misslyckas visas ett felmedelande.

Vad gäller felhantering av path tolkade jag det som att en tom path helt enkelt ska sättas till `NULL` i databasen.

Jag lade till ett nytt globalt objekt `$tlz` av klassen `Toolz` som innehåller lite smått och gott som inte passar någon annanstans. Till exempel en funktion 
för att kombinera ihop en array av `Either` till en `Either` som innehåller en array. Den är bra att ha till exempel när man som i detta kursmomentet har många värden i från ett formulär som man vill behandla separata Either. Då kan man sedan kombinera ihop dessa och antingen få en `Right` innehållande en array med alla värden eller en `Left` som innehåller det första felet, Left, som stöttes på i arrayen. 

Vad gör man om man har ett berg av uppgifter framför sig. Pillar med det gamla naturligtvis!

Jag började med att revidera lite av förra veckans kod. Jag lade till en User-klass och snyggade upp i routerna som jag skrivit.

Istället för null som användare använder jag ett Gäst-konto om sessionen inte redan har en användare.

Det finns tre nivåer på användare, admin, user och guest. Gäst-användare har inte rätt att göra någonting och likställs med ingen användare.

Jag kunde inte heller sluta pilla i anax-light modulerna. Medan jag reviderade kmom03 fick jag en underlig bugg som berodde på att jag inte reflekterat över flödet av en request genom ramverket. Jag hade reflekterat över att alla handlers var procedurer snarare än funktioner men tänkt att det går att betrakta hela requesten, från index.php till svar, som ett slags funktionsanrop.

Buggen kom av att jag inte såg till att sända något från en handler. Så jag gick in och tittade i `Response` klassen och såg att den var nästan helt förberedd för ett förfarande där man ser till att returnera ett response från sina handlers.

Så jag bestämde mig för att skriva lite nytt i respons-klassen och göra om mina route-handlers till att, nästan, alltid returnera ett response istället för att själv sända svaret. Jag använder min nya respons-klass istället för den composer-installerade.

Min nya respons-klass ska vara helt bakåtkompatibel med canax respons-klass men den är inte helt färdig och inte enhetstestad så det får vänta lite med eventuell pull request.

Om man använder en ** route som guard för nått och sidan inte finns får man ingenting.

Block delen av uppgiften var i stort sett odefinierad och jag förstod inte uppgiftsskaparens tanke riktigt. Min definition blev:

En admin ska kunna gå in och CRUD:a block. I en template kan man skriva $app->blocks->exists("blocknamn") och $app->block->html("blocknamn") för att hämta blocket till den platsen.


-- Stämmer inte
Äntligen insåg jag att jag kan låsa alla admin router genom att göra en handler i toppen på route-filen som inte returnerar utan som kör en redirect till error-sida om användaren inte har behörighet. Denna handler körs först som en guard på alla routes som kan leda till en admin-sida. En annan variant skulle vara att helt enkelt inte ladda routsen genom att lägga dem inom en if-sats. Om man inte har behörighet laddas inte routsen. Smaksak vad som är bäst kanske?

--- Stämmer inte
Jag kom på att jag kan låsa alla admin-sidor väldigt enkelt genom en simpel if-sats istället för att göra en koll på varenda route som bara admin får ha tillgång till. Om användaren inte är admin laddas inte admin routarna. Varför tänkte jag inte på det innan?

--- Stämmer inte längre
Jag kom på att man kan "låsa" alla admin-routes genom att fånga alla requests till en viss route. Så istället för att kontrollera om användaren är admin i var route som kräver admin-behörighet har jag en funktion som gör en koll att användaren är admin. Sedan fångar jag alla anrop till `admin/{}` i sammanlagt tre undernivåer. Om man inte är inloggad som admin studsar man tillbaka till start-sidan. Eftersom routern kör alla routes som matchar använde jag internal routes och jag lade till i routern att returnera resultatet av ´handleInternal()´ eftersom det av någon anledning inte returnerades i orginal.