<?php


// Include the Singleton class definition
require 'Database.php';

// Simulate user input (which should always be treated as untrusted)
$user_id = 5;

// --- 1. Get the Database Instance (The Manager) ---
// This ensures the connection is established ONCE, or retrieves the existing manager.
$db_manager = Database::getInstance();

// --- 2. Get the PDO Connection (The Execution Tool) ---
// We ask the manager for the actual tool we need to talk to MySQL.
$pdo = $db_manager->getConnection();

echo "--- Starting Query Execution ---<br>";

// --- 3. Execute a Secure Query using Prepared Statements ---
try {
    // Define the SQL query with a named placeholder (:id)
    $sql = "SELECT username FROM users WHERE id = :id";

    // Prepare the statement object
    $stmt = $pdo->prepare($sql);

    // Execute the statement, securely binding the user input to the placeholder.
    // This is the step that prevents SQL Injection (SQLi).
    $stmt->execute([':id' => $user_id]);

    // Fetch the result
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<hr>Query successful! <br>";
    if ($user) {
        echo "Successfully retrieved user data for ID: {$user_id}.<br>";
        echo "Username: <strong>" . htmlspecialchars($user['username']) . "</strong>";
    } else {
        echo "No user found with ID: {$user_id}.";
    }

} catch (PDOException $e) {
    echo "<hr><strong>ERROR executing query:</strong> " . $e->getMessage();
}

// --- Demonstration of Singleton in action ---
echo "<hr>--- Singleton Test ---<br>";

// Calling getInstance again returns the SAME object instance, 
// and the private __construct() is NOT executed again.
$db_manager_2 = Database::getInstance();

if ($db_manager === $db_manager_2) {
    echo "Success: The second call returned the exact same object instance. Only one connection was ever made.";
} else {
    echo "Failure: Multiple instances were created.";
}
