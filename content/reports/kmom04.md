kmom04

Vad gör man om man har ett berg av uppgifter framför sig. Pillar med det gamla naturligtvis!

Jag började med att revidera lite av förra veckans kod. Jag lade till en User-klass och snyggade upp i routerna som jag skrivit.

Istället för null som användare använder jag ett Gäst-konto om sessionen inte redan har en användare. 

Det finns tre nivåer på användare, admin, user och guest. Gäst-användare har inte rätt att göra någonting och likställs med ingen användare.