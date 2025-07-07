<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Eingaben bereinigen
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Pflichtfelder prüfen
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Bitte füllen Sie alle Pflichtfelder korrekt aus.";
        exit;
    }

    // Empfänger anpassen!
    $recipient = "lostrios@gmx.ch";

    // Betreff-Standard, falls leer
    if (empty($subject)) {
        $subject = "Neue Nachricht von $name";
    }

    // E-Mail-Inhalt zusammenbauen
    $email_content  = "Name: $name\n";
    $email_content .= "E-Mail: $email\n\n";
    $email_content .= "Betreff: $subject\n\n";
    $email_content .= "Nachricht:\n$message\n";

    // Header setzen
    $email_headers = "From: $name <$email>\r\n";
    $email_headers .= "Reply-To: $email\r\n";

    // E-Mail verschicken
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "Vielen Dank! Ihre Nachricht wurde erfolgreich gesendet.";
    } else {
        http_response_code(500);
        echo "Entschuldigung, beim Senden der Nachricht ist ein Fehler aufgetreten.";
    }
} else {
    // Kein POST-Request
    http_response_code(403);
    echo "Ups! Da ist etwas schiefgegangen.";
}
?>
