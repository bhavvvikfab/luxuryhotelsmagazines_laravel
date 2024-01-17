<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #333;
    }
    p {
      color: #555;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      text-decoration: none;
      color: #fff;
      background-color: #007bff;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container" style="border:1px solid grey; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

    <h2>Forgot Password</h2>
 

    <p>Hi {{ is_array($email) ? $email['name'] : $email }}, <!-- Replace with the actual user's name --> </p>
    <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p>
    <p><a href="{{ route('password.reset', ['token' => $email['key'], 'id' => $email['id']]) }}" class="btn">Reset Password</a></p> <!-- Replace with the actual reset link -->
    <p>If you didn't request a password reset, you can ignore this email.</p>
    <p>Thanks,<br>Your Application Team</p>
  </div>
</body>
</html>
