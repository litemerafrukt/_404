# Webshop kmom05

## Generellt
Webshoppen har ett lager och ett virtuellt lager. Lagret visar de faktiska mängder av produkter som finns. Det virtuella lagret visar hur många produkter som finns tillgängliga om man räknar bort de som ligger i varukorgar. Det virtuella lagret har ingen egen tabell utan är en implementerat med vyer.

Var användare (shopper) får en varukorg.

Varukorgen är en enda tabell och för att se en enskild användares varukorg används en vy.

Om en användare vill lägga varor i sin varukorg görs en koll mot det virtuella lagret. När en order läggs görs en koll mot det faktiska lagret med en rollback om det faktiska lagret är för litet för ordern. När det läggs en order töms användarens varukorg.

När det läggs en order görs en kontroll via en trigger. Om lagersaldot för en vara går under fem läggs varan till en tabell, oophp_lowInventory, tillsammans med en tidsstämpel. För att få en rapport om varor som börjar ta slut används en vy.

Alla tabeller är namespacade med oophp. Jag har använt snakecase för tabellkolumnernas namn och camelCase för argument och variabler i funktioner och procedurer.

## Tabeller

### oophp_prodCategories
Produktkategorier.

### oophp_products
Produkter

### oophp_inventory
Lagret, faktiska lagervärden.

### oophp_lowInventory
Tabell över varor som gått under fem i lagersaldo.

### oophp_inBaskest
Tabell med samtliga varukorgar.

### oophp_orders
Indeviduella ordrar.

### oophp_orderRows
Tabell med samtliga befintliga orderrader.

### oophp_shoppers
Användare/varukorgar.

## Vyer

### oophp_vavailable
Visar tabell över tillgänglighet i lager, hur mycket som finns i varukorgar samt hur många varor som är tillgängliga. Denna vy är framförallt en hjälpvy för oophp_vvirtualinventory.

### oophp_vvirtualinventory
Visar produkt id samt hur många som finns tillgängliga borträknat varor i varukorgar. Om det inte finns några produkter i varukorgar hämtas värdet för tillgängliga från det faktiska lagersaldot.

### oophp_vbasketdetails
Visar en översikt över varukorgstabellen.

### oophp_vlowinventory
Rapport över varor som kan ha lågt lagersaldo. Vyn hämtar det faktiska lagersaldot för en vara när vyn visas.

### oophp_vorderdetails
Visar översikt över alla ordrar.

### oophp_vvirtualinventorydescription
Samma sak som oophp_vvirtualinventory men inkluderar produkt description.

### oophp_vadmininventory
Vy som används i me-sidans backend för att visa info från olika tabeller angående produkter och lager.

## Procedurer

### putInBasket(shopperId, prodId, amount)
Lägg varor i en varukorg (shopperId). Påverkar inte det faktiska lagret men påverkar det virtuella lagret. En koll görs mot det virtuella lagret att det finns tillräckligt med varor kvar för att lägga i varukorgen.

### getBasket(shopperId)
Få en tabell över innehållet i en varukorg.

### emptyBasket(shopperId)
Ta bort allt innehåll i en varukorg.

### placeOrder(shopperId)
Gör om en varukorg till en order. Tar bort varorna från lagret. Allt är omslutet av en transaktion, om någon produkt får ett negativt lagervärde görs en rollback.

### showOrder(orderNr)
Visa innehållet i en order.

### deleteOrder
Tar bort en order och lägger tillbaka varorna i lagret.

## Funktion

### sumBasketProd(shopperId, prodId)
Om en användare lagt till en produkt i sin varukorg flera gånger skapas det flera rader i varukorgstabellen. Denna funktion lägger ihop antalet av en produkt för en varukorg. Används för att få överskådligare utskrifter.

## Övrigt
Förutom egna procedurer och funktioner använder jag den inbyggda funktionen SUM().
