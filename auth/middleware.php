<?php
/**
 * Authentication Middleware
 * Check if user is logged in
 */

require_once __DIR__ . '/../config/database.php';

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

// Function to get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    global $conn;
    $user_id = intval($_SESSION['user_id']);
    
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT id, username, email, created_at, profile_picture FROM users WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    return $result->fetch_assoc();
                }
            }
        } catch (Exception $e) {
            // DB lookup failed, fallback to session data
        }
    }
    
    return [
        'id' => $user_id,
        'username' => $_SESSION['username'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? '',
        'created_at' => date('Y-m-d H:i:s'),
        'profile_picture' => null
    ];
}

// Function to require login (redirect to login if not logged in)
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.html");
        exit();
    }
}

// Function to prevent access if already logged in
function preventLogin() {
    if (isLoggedIn()) {
        header("Location: home.php");
        exit();
    }
}

// Function to logout
function logout() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    header("Location: login.html");
    exit();
}

// Function to set user session
function setUserSession($user_id, $email, $username) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $email;
    $_SESSION['username'] = $username;
    $_SESSION['login_time'] = time();
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
}

// JSON file storage path for persistent fallback
function getUsersStorageFile() {
    return __DIR__ . '/../config/users.json';
}

function loadUsersFromFile() {
    $file = getUsersStorageFile();
    if (!file_exists($file)) {
        return [];
    }
    $content = @file_get_contents($file);
    if (!$content) return [];
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function saveUsersToFile($users) {
    $file = getUsersStorageFile();
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    @file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

// Find user by email from MySQL with persistent fallback
function findUserByEmail($email) {
    global $conn;
    $email = trim(strtolower($email));
    
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT id, username, email, password, created_at FROM users WHERE LOWER(email) = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    return $result->fetch_assoc();
                }
            }
        } catch (Exception $e) {
            // DB error fallback
        }
    }
    
    // File persistence fallback
    $fileUsers = loadUsersFromFile();
    foreach ($fileUsers as $u) {
        if (isset($u['email']) && strtolower($u['email']) === $email) {
            return $u;
        }
    }
    
    return null;
}

// Find user by username from MySQL with persistent fallback
function findUserByUsername($username) {
    global $conn;
    $username = trim(strtolower($username));
    
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT id, username, email, password, created_at FROM users WHERE LOWER(username) = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    return $result->fetch_assoc();
                }
            }
        } catch (Exception $e) {
            // DB error fallback
        }
    }
    
    // File persistence fallback
    $fileUsers = loadUsersFromFile();
    foreach ($fileUsers as $u) {
        if (isset($u['username']) && strtolower($u['username']) === $username) {
            return $u;
        }
    }
    
    return null;
}

// Save new user to MySQL and sync with persistent storage
function saveNewUser($username, $email, $hashedPassword) {
    global $conn;
    $userId = time();
    $createdAt = date('Y-m-d H:i:s');
    
    if ($conn) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                if ($stmt->execute()) {
                    $userId = $stmt->insert_id;
                }
            }
        } catch (Exception $e) {
            // Fallback continues
        }
    }
    
    // Always persist to JSON file as well for high reliability
    $fileUsers = loadUsersFromFile();
    $newUser = [
        'id' => $userId,
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword,
        'created_at' => $createdAt,
        'profile_picture' => null
    ];
    $fileUsers[] = $newUser;
    saveUsersToFile($fileUsers);
    
    return $newUser;
}


