<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>img</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="scripts/ajax.js"></script>

</head>
<body>
    <?php
        require 'vendor/autoload.php';

        use Intervention\Image\ImageManager;

        session_start();

        $captcha = [];
        $imgFileName = './img/test.png';
        $manager = new ImageManager(['driver' => 'imagick']);
        $img = $manager->canvas(600, 200, '#cba');

        $imageWidth = $img->width();
        $imageHeight = $img->height();

        for ($i = 0; $i < 5; $i++) {
            array_push($captcha, generateRandomString(1));
        }

        $capStr = '';
        foreach($captcha as $item){
            $capStr .= $item;
        }
        
        $_SESSION['captcha'] = $capStr;

        $num = 1;
        foreach ($captcha as $item) {
            $img->text($item, ($num * 100)+rand(-50,50), ($imageHeight / 2)+rand(-50,50), function ($font) {
                $font->file('times new roman.ttf');
                $font->size(30);
                $font->color('#' . generateRandomColor());
                $font->align('center');
                $font->angle(rand(-45, 45));
            });
            $num++;
        }
        $img->save($imgFileName);


        function generateRandomString($length = 1)
        {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        function generateRandomColor()
        {
            $length = 3;
            $characters = '0123456789abcdef';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

    ?>

    <img src="img/test.png">

    <form id="captchaForm" method="post" action="#">
        <input type="text" id="captcha" name="captcha">
        <input type="submit">
    </form>

    <p id="cap_res"></p>

    <form enctype="multipart/form-data" method="post" id="imgForm">
        <input id="img" type="file" accept="image/jpeg,png,jpg" name="filename">
        <input type="submit">
    </form>

    <img id="watermarked" src="img/watermarked.png">
</body>
</html>