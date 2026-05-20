

-- 1. Find the addresses of all houses currently listed.
SELECT L.address 
FROM Listings AS L
    JOIN House AS H ON L.address = H.address;

-- 2. Find the addresses and MLS numbers of all hosues currently listed. 
SELECT L.address, L.mlsNumber
FROM Listings AS L
    JOIN House AS H ON L.address = H.address;

-- 3. Find the addresses of all 3-bedroom, 2-bathroom houses currently listed.
SELECT L.address
FROM Listings AS L
    JOIN House AS H ON L.address = H.address
WHERE H.bedrooms = 3 AND H.bathrooms = 2;

-- 4. Find the addresses and prices of all 3-bedroom, 2-bathroom houses with prices in the range
-- $100,000 to $250,000, with the results shown in descending order of price.
SELECT H.address, P.price
FROM House AS H
    JOIN Property AS P ON H.address = P.address
WHERE H.bedrooms = 3
    AND H.bathrooms = 2 
    AND P.price BETWEEN 100000 AND 250000
ORDER BY P.price DESC;

-- 5. Find the addresses and prices of all business properties that are advertised as office space in descending order of price.
SELECT B.address, P.price
FROM BusinessProperty AS B
    JOIN Property AS P ON P.address = B.address
WHERE LOWER(B.type) = 'office'
ORDER BY P.price DESC;

-- 6. Find all the ids, names and phones of all agents, together with the names of their firms and the dates when they started. 
SELECT A.agentId, A.name, A.phone, F.name, A.dateStarted
FROM Agent AS A
    JOIN Firm AS F ON A.firmId = F.id;

-- 7. Find all the properties currently listed by agent with id “001” (or some other suitable id).
SELECT L.address
FROM Listings AS L
WHERE L.agentId = 1;

-- 8. Find all Agent.name-Buyer.name pairs where the buyer works with the agent, sorted alphabetically by Agent.name.
SELECT A.name AS agentName, B.name AS buyerName
FROM Agent AS A, Buyer AS B, Works_With AS W
WHERE A.agentId = W.agentId AND B.id = W.buyerId
ORDER BY A.name;

-- 9. For each agent, find the total number of buyers currently working with that agent, i.e., the output should be Agent.id-count pairs.
SELECT W.agentId, COUNT(*) AS totalBuyers
FROM Works_With AS W
GROUP BY W.agentId;

-- 10. For some buyer that is interested in a house, where the buyer is identified by an id (e.g., “001”), find all houses that meet the buyer’s preferences, with the results shown in descending order of price
SELECT L.address, P.price
FROM Listings AS L
    JOIN House AS H ON L.address = H.address
    JOIN Property AS P ON L.address = P.address
    JOIN Buyer AS B ON B.id = 1
WHERE P.price BETWEEN B.minimumPreferredPrice AND B.maximumPreferredPrice
    AND H.bedrooms = B.bedrooms
    AND H.bathrooms = B.bathrooms
ORDER BY P.price DESC;