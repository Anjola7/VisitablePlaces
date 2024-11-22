<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Marrja e të dhënave të vizitorëve
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bank_account_number = $_POST['bank_account_number'];
    $credit_card_number = $_POST['credit_card_number'];
    $payment_amount = $_POST['payment_amount'];

    // Përgatitja e vendndodhjeve të vizituara
    $visited_locations = '';
    if (isset($_POST['locations'])) {
        $selected_locations = $_POST['locations'];
        $visited_locations = implode(', ', $selected_locations);
    }

    // Ruajtja e të dhënave të vizitorëve në tabelën visitors
    $stmt = $mysqli->prepare("INSERT INTO visitors (first_name, last_name, visited_locations) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $first_name, $last_name, $visited_locations);
    $stmt->execute();
    $visitor_id = $stmt->insert_id; // ID e vizitorit të sapo shtuar
    $stmt->close();

    // Ruajtja e detajeve bankare në tabelën bankdetails
    $stmt = $mysqli->prepare("INSERT INTO bankdetails (visitor_id, bank_account_number, credit_card_number, payment_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $visitor_id, $bank_account_number, $credit_card_number, $payment_amount);
    $stmt->execute();
    $stmt->close();

    // Ridrejtoni përdoruesin te faqja e mesazhit të suksesit
    header('Location: payment_success.php');
    exit();
}
?>

