<?php
function is_logged_in() {
    session_start();
    return isset($_SESSION['user_id']);
}

function is_store_owner() {
    return is_logged_in() && $_SESSION['role'] === 'store_owner';
}
?>
