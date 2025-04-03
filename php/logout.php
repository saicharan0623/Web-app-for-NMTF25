<?php
session_start();

// Logout function
function logout() {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Redirect to login page
    header("Location: student_login.php");
    exit();
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    logout();
}
?>