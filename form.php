<?php
  // Check if the form has been submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $phone = test_input($_POST["phone"]);
    $message = test_input($_POST["message"]);

    // Validate the form data
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $error_message = "Only letters and white space allowed in the name field";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error_message = "Invalid email format";
    }
    else if (!preg_match("/^[0-9]{10}$/",$phone)) {
      $error_message = "Invalid phone number format";
    }
    else {
      // Extract email addresses from the message
      preg_match_all('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $message, $matches);
      $to = implode(",", $matches[0]);

      // Send the email
      $subject = "New Contact Form Submission";
      $body = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
      $headers = "From: your_email@example.com" . "\r\n" .
                 "Reply-To: $email" . "\r\n" .
                 "Cc: $email" . "\r\n" .
                 "X-Mailer: PHP/" . phpversion();

      if (mail($to, $subject, $body, $headers)) {
        $success_message = "Thank you for your message!";
      }
      else {
        $error_message = "Oops! Something went wrong, please try again later.";
      }
    }
  }

  // Function to validate input data
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>

<!-- Display the form with error/success messages -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" required>
  <br><br>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required>
  <br><br>

  <label for="phone">Phone:</label>
  <input type="tel" id="phone" name="phone" required>
  <br><br>

  <label for="message">Message:</label>
  <textarea id="message" name="message" required></textarea>
  <br><br>

  <input type="submit" value="Submit">
</form>

<?php
  // Display any error or success messages
  if (isset($error_message)) {
    echo "<p style='color:red'>$error_message</p>";
  }
  else if (isset($success_message)) {
    echo "<p style='color:green'>$success_message</p>";
  }
?>
