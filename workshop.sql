--nombre total de séances créées 
SELECT u.id, COUNT(s.id)
from users u 
JOIN coachs c ON c.user_id = u.id 
JOIN seances s ON s.coach_id = c.user_id 
GROUP BY u.id;

--nombre de séances réservées
SELECT u.id, COUNT(s.id) 
from users u 
JOIN coachs c ON c.user_id = u.id 
JOIN seances s ON s.coach_id = c.user_id 
JOIN reservations r ON r.seance_id = s.id 
GROUP BY u.id;

--seulement les coachs ayant ≥3 séances
SELECT coach_id
from seances
GROUP BY coach_id
HAVING count(*) >= 3;

