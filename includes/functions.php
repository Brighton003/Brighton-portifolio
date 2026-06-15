<?php
// includes/functions.php

session_start();

/**
 * Sanitize input data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Check if admin is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Fetch all records from a table
 */
function getAll($pdo, $table, $orderBy = 'id DESC') {
    $stmt = $pdo->prepare("SELECT * FROM $table ORDER BY $orderBy");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Fetch a single record by ID
 */
function getById($pdo, $table, $id) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Format date/duration for display
 */
function formatDuration($start, $end = null) {
    if (!$end) return $start . " - Present";
    return $start . " - " . $end;
}
?>
