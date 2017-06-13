kmom05



### SQL-varukorg

Produkt, produkter.

Inventory, faktiskt inventory dvs hur många oköpta varor finns på lagret.

Inbaskets, varor som ligger i varukorgar.

vinventory, visar inventory, product id samt hur många som finns av den produktet minus allt som finns i varukorgarna.

vinventorydescription, samma som vinventory men hämtar även description från produkttabellen.

VBasketDetails, visa status för varukorg(ar).

Trigger när en order läggs. Kolla om varorna är under fem, lägg isåfall i beställningstabell.

Transaktioner för att lägga lägga order.

En användare får bara en varukorg.

createOrder, skapa en order baserat på vad en användare har i sin varukorg. Tömmer varukorgen.

annulateOrder, ta bort en order.

showOrder, visar order.

showOrders, visar alla order.

ShipOrder??? Nope, skippar nog denna.