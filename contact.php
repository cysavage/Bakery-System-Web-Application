<?php
include_once('header.php');
?>
<div class="body-image3">
    <!doctype html>
    <html lang="en">

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <title>Contact Form</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

       
    </head>

    <body>

    <div class="contactPage">
        <form id="contactForm" method="POST" enctype="multipart/form-data">
            <br>
            <p>We're open for any suggestions or just to have a chat </p>
            <br>
            <input type="text" placeholder="Name" size="10" name="name"><br><br>
            <input type="email" placeholder="Email" name="email"><br><br>
            <textarea name="message" rows="6″ cols=" 20″> 
    </textarea><br><br>
            <input type="hidden" name="form-submitted" value="1">

            <button type="submit" value="Submit">Send</button>
            <h3>Follow us here</h3>
            <p>
                <a href="#"><img src="images/facebook2.png"></a>
                <a href="#"><img src="images/instagram.png"></a>
                <a href="#"><img src="images/twitter.png"></a>
            </p>
        </form>
    </div>
    </body>
</div>
    </html>

    <script>


        $(document).ready(function () {
            $('#contactForm').submit(function (event) {
                // Prevent form from submitting
                event.preventDefault();
                // Submit form data to PHP script
                $.ajax({
                    url: 'send-email.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        // Show success or error message
                        if (response == 'success') {
                            $('#form-message-success').html('Your message was sent, thank you!').show();
                            $('#form-message-warning').hide();
                            $('#contactForm')[0].reset();
                        } else {
                            $('#form-message-warning').html('Sorry, there was an error sending your message. Please try again later.').show();
                            $('#form-message-success').hide();
                        }
                    }
                });
            });
        });



    </script>