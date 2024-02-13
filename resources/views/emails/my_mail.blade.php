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
    .reset-btn {
    background-color: #3498db; /* Blue background color */
    color: white; /* White text color */
    padding: 10px 20px; /* Padding for better appearance */
    border: 1px solid #3498db; /* Set border with the same color as the background */
    border-radius: 5px; /* Add some border-radius for rounded corners */
    cursor: pointer; /* Add pointer cursor on hover */
}
  </style>
</head>
<body>
  <div class="container" style="border:1px solid grey; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">

  @if($data['key']==1)
    <h2>Login</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
<!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
<p>Your OTP is: <b>{{ isset($data['token']) ? $data['token'] : 'N/A' }}</b></p>

<!-- <p>If you didn't request a password reset, you can ignore this email.</p> -->
<p>Thanks,<br>Your Application Team</p>
@elseif($data['key']==2)

<h2>Forgot Password</h2>



<p>Hi {{ isset($data['name']) ? $data['name'] : 'Employer' }},</p>
<p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p>
<p><a href="{{ url($data['url'] . '/forgot-password/' . $data['remember_token']) }}" class="btn">Reset Password</a></p>
 
<!-- <button type="submit" class="btn btn-primary reset-btn">Reset Password </button> -->
<p>If you didn't request a password reset, you can ignore this email.</p>
<p>Thanks,<br>Your Application Team</p>
@elseif($data['key']==3)
    <h2>Employer Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
<!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
<p>Name: <b>{{ isset($data['user_name']) ? $data['user_name'] : 'N/A' }}</b></p>
<p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>
<p>Password: <b>{{ isset($data['password']) ? $data['password'] : 'N/A' }}</b></p>

<!-- <p>If you didn't request a password reset, you can ignore this email.</p> -->
<p>Thanks,<br>Your Application Team</p>
@elseif($data['key']==4)
    <h2>User Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
<!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
<p>Name: <b>{{ isset($data['name']) ? $data['name'] : 'N/A' }}</b></p>
<p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>
<p>Password: <b>{{ isset($data['password']) ? $data['password'] : 'N/A' }}</b></p>

<!-- <p>If you didn't request a password reset, you can ignore this email.</p> -->
<p>Thanks,<br>Your Application Team</p>

@elseif($data['key']==6)
    <h2>Query Details</h2>


    <p>Hi <b>{{ isset($data['name']) ? $data['name'] : 'User' }}</b>,</p>
<!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
<p>Your Query Solved Successfully!</p>
<!-- <p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>
<p>Password: <b>{{ isset($data['password']) ? $data['password'] : 'N/A' }}</b></p> -->

<!-- <p>If you didn't request a password reset, you can ignore this email.</p> -->
<p>Thanks,<br>Your Application Team</p>
  
  

@elseif($data['key']==5)

    <h2>Subscriber Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
    <!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
    <p>Name: <b>{{ isset($data['name']) ? $data['name'] : 'N/A' }}</b></p>
    <p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>

    <p>Thanks,<br> For subscribed to the newsletter</p>



@elseif($data['key']==7)

    <h2>Hotel Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
    <!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
    <p>Name: <b>{{ isset($data['name']) ? $data['name'] : 'N/A' }}</b></p>
    <p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>

    <p>Thanks,<br> For voted this hotel</p>

    @elseif($data['key']==8)

    <h2>News Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
    <!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
    <p>Name: <b>{{ isset($data['name']) ? $data['name'] : 'N/A' }}</b></p>
    <p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>

    <p>Thanks,<br> For voted this news</p>

    @elseif($data['key']==9)

    <h2>User Details</h2>


    <p>Hi {{ isset($data['name']) ? $data['name'] : 'User' }},</p>
    <!-- <p>It seems you've forgotten your password. No worries! Click the button below to reset it:</p> -->
    <p>Name: <b>{{ isset($data['name']) ? $data['name'] : 'N/A' }}</b></p>
    <p>Email: <b>{{ isset($data['email']) ? $data['email'] : 'N/A' }}</b></p>

    <p>Thanks,<br> For contact to us</p>
    <p>We will contact you very soon</p>


@endif


  </div>
</body>
</html>
