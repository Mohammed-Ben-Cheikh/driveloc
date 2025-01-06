CREATE VIEW ListeVehicules AS
SELECT 
    v.id AS vehicle_id,
    v.name AS vehicle_name,
    c.name AS category_name,
    AVG(r.rating) AS average_rating,
    CASE 
        WHEN r.id IS NULL THEN 'Available'
        ELSE 'Unavailable'
    END AS availability
FROM 
    vehicles v
LEFT JOIN 
    categories c ON v.category_id = c.id
LEFT JOIN 
    reservations res ON v.id = res.vehicle_id AND res.end_date >= CURDATE()
LEFT JOIN 
    ratings r ON v.id = r.vehicle_id
GROUP BY 
    v.id, v.name, c.name;
