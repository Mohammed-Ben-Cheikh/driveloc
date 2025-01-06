<?php
require_once __DIR__ . '/../database/Database.php';

class PasswordReset {
    public static function generateToken($email) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $database = new Database();
        $db = $database->connect();
        
        // Delete any existing tokens for this email
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        
        // Insert new token
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email, $token, $expires]);
        
        $database->disconnect();
        return $token;
    }

    public static function verifyToken($email, $token) {
        $database = new Database();
        $db = $database->connect();
        
        $sql = "SELECT * FROM password_resets 
                WHERE email = ? AND token = ? AND used = 0 
                AND expires_at > CURRENT_TIMESTAMP";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email, $token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $database->disconnect();
        return $result ? true : false;
    }

    public static function markTokenAsUsed($email, $token) {
        $database = new Database();
        $db = $database->connect();
        
        $sql = "UPDATE password_resets SET used = 1 WHERE email = ? AND token = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$email, $token]);
        
        $database->disconnect();
        return $result;
    }
}
