kmom03

cimage funkar nog inte riktigt med php 7. Mer än hälften av bilderna i kalendern fungerar inte på min lokala maskin som kör php 7.1 men dom fungerar på studentserver. Jag får upp ett fel från cimage som säger att bildfilen är trasig när jag provar med php 7.1. Har inte haft tid att titta närmare på detta eller skapat något issue.

Jag tror att det varit snyggare med ett user-objekt i session istället för bara användarens namn om hen är inloggad. Jag han dock inte utforska detta.

Hack och billiga lösningar.

Min erfarenhet är att press på studenterna kan vara en bra sak. Risken är bara att man trycker för hårt. Då hamnar man på andra sidan. Istället för att hinna reflektera på vad man gör och hur detta kunnat göras annorlunda måste man falla tillbaka på det man redan kan bara för att producera.

Där finns en hel del technical debt, tex att user borde vara en klass och några tajta kopplingar mellan komponenter, eftersom somligt bara måste gå fort. Till och med DRY blir lidande, ser att jag skriver samma sak för tredje gången men hinner inte refaktorera just nu. Såhär långt i kursen har jag tänkt att det ordnar jag till när jag vet mer om vägen framåt, vad som kommer i nästa kursmoment. Med tanke på trycket i kursen är jag rädd att jag inte kommer att hinna snygga upp saker.

