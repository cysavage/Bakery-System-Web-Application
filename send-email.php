<?php
if (isset($_POST['form-submitted'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Construct email message
    $to = "cyrilkthomasest.1991@gmail.com"; // Replace with your email address - **Note create a vibe guide admin email
    $subject = "New message from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    // Send email
    $headers = "From: $email";
    if (mail($to, $subject, $body, $headers)) {
        echo "success"; // Return success message to JavaScript
    } else {
        echo "error"; // Return error message to JavaScript
    }
}
?>