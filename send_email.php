<?php
if (isset($_POST['send_email'])) {
    $recipient_email = $_POST['recipient_email'];
    $message = $_POST['message'];
    $subject = "Poruka od korisnika";
    $headers = "From: " . $_POST['sender_email'];

    if (mail($recipient_email, $subject, $message, $headers)) {
        echo "Poruka je uspešno poslata.";
        header("Location: pocetna.php");
    } else {
        echo "Greška prilikom slanja poruke.";
        header("Location: pocetna.php");
    }
}
?>