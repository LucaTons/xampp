--QUERY 1

/*1)la capienza media di tutti i magazzini*/
SELECT AVG(capienza) AS capienza_media_magazzini
FROM Magazzini

--QUERY 2

/*2)il numero di volte che la materia prima "Panna Fresca" viene utilizzata in delle ricette*/
SELECT COUNT(*) AS numero_panne
FROM MateriePrime NATURAL JOIN Ricette
WHERE Tipologia = "Panna Fresca";

--QUERY 3

/*3)il numero di ingredienti utilizzati per ciascun prodotto*/
SELECT Prodotti.Codice, COUNT(MateriePrime.Codice) AS Numero_Ingredienti
FROM Prodotti NATURAL JOIN MateriePrime
GROUP BY Prodotti.Codice;

/*3)il numero di ingredienti utilizzati per ciascun prodotto*/
SELECT Ricette.Id, COUNT(*) AS Numero_Ingredienti
FROM Ricette
GROUP BY Ricette.Id;

/*3)il numero di ingredienti utilizzati per ciascun prodotto*/
SELECT Ricette.Id, Prodotti.Nome, COUNT(*) AS Numero_Ingredienti
FROM Ricette NATURAL JOIN Prodotti
GROUP BY Ricette.Id;

SELECT Dipendenti.Matricola FROM Dipendenti LEFT JOIN Prodotti ON Dipendenti.Matricola = Prodotti.Matricola WHERE Prodotti.ID IS NULL;

--QUERY 4

/*4)la lista dei dipendenti che non sono responsabili di alcun prodotto*/
SELECT Prodotti.Matricola, COUNT(Dipendenti.Matricola) 
FROM Dipendenti NATURAL JOIN Prodotti
GROUP BY Dipendenti.Nome, Dipendenti.Cognome;

--QUERY 5

/*5)le migliori 10 materie prime che vengono utilizzata in maggior quantità (intesa come peso totale, non numero di utilizzi)*/
SELECT COUNT(MateriePrime.PesoUnitario) AS Migliori_10_materie_prime
FROM MateriePrime
GROUP BY MateriePrime.PesoUnitario;

SELECT Ricette.Tipologia AS Nome_Prodotto, SUM(Ricette.Qta*MateriePrime.PesoUnitario) AS Migliori_10
FROM MateriePrime NATURAL JOIN Ricette
GROUP BY Ricette.Tipologia
ORDER BY Migliori_10 DESC
LIMIT 10;

--QUERY 6

/*6)il numero di prodotti contenuti in ciascun magazzino, mantenendo soltanto quelli che ne hanno più di 50*/
SELECT Prodotti.Codice, COUNT(*) AS n_prodotti
FROM Prodotti NATURAL JOIN Magazzini
GROUP BY Prodotti.Codice
HAVING n_prodotti > 0;

--QUERY 7

/*7)la lista dei prodotti che utilizzano almeno una materia rpima che non è contenuta in alcun magazzino*/


--QUERY 8

/*8)la lista dei prodotti il cui costa totale delle materie prime supera la media dei costi totali di tutti i prodotti*/
