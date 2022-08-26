<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>index</title>
</head>
<body>
    <form enctype="multipart/form-data" id="emailForm" method="post" name="emailForm">
        <p>Enter username:</p>

        <input type="text" name="username" id="username" required> <br>

        <p>Enter email:</p>

        <input type="text" name="email" id="email" required pattern="^\S+@\S+\.\S+$"
               title="Valid email"> <br>

        <p>Enter message:</p>

        <input type="text" name="message" id="message" required pattern="^.{10,5000}$"
               title="More than 10 and less than 5000 symbols"> <br>

        <p>Attach files:</p>

        <input type="file" name="files" id="files" multiple
               accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document,msword,pdf">

        <input type="submit">
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="scripts/ajax.js"></script>

</html>