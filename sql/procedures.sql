DELIMITER //

CREATE PROCEDURE AjouterReservation(
    IN p_client_id INT,
    IN p_vehicle_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_total_price DECIMAL(10, 2)
)
BEGIN
    INSERT INTO reservations (client_id, vehicle_id, start_date, end_date, total_price)
    VALUES (p_client_id, p_vehicle_id, p_start_date, p_end_date, p_total_price);
END //

DELIMITER ;
