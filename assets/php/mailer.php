<?php
    
    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
        $number = strip_tags(trim($_POST["contact_number"]));
        $name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);
        $honeypot = trim($_POST["contact_me_by_fax_only"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !empty($honeypot) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Something went wrong, please try again";
            exit;
        }

        // Set the recipient email address.
        // FIXME: Update this to your desired email address.
        $recipient = "info@beartoothgroup.com";

        // Set the email subject.
        $subject = "Yellowstone Basin Contact Form Inquiry";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Number: $number\n\n";
        $email_content .= "Message:\n$message\n";

        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        if (mail($recipient, $subject, $email_content, $email_headers)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thanks! We'll get back to you shortly.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Something went wrong, please try again.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "Something went wrong, please try again.";
    }
?>