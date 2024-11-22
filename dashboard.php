<?php
session_start();

// Kontrollo nëse menaxheri është i identifikuar
if (!isset($_SESSION['manager_id'])) {
    // Nëse jo, ridrejto në faqen e logimit
    header("Location: login.php");
    exit();
}

// Merr vlerën e manager_id nga sesioni
$manager_id = $_SESSION['manager_id'];

// Pjesa tjetër e kodit të dashboard-it tuaj
?>
