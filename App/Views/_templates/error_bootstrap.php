<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>

    <link rel='icon' type='image/png' href='/public/images/evo_icon_light.png' />

    <title><?php echo $content['title']; ?></title>

    <link id="themeStylesheet" rel="stylesheet" href="/public/css/bootstrap.css" />
    <link rel="stylesheet" href="/public/css/custom.css" />
</head>

<body class="bg-slate">

<div class="container-fluid">
    <div class="row g-0">
        <div class="col-12 bg-blue h-25">
            <div class="">
                <i class="fas fa-exclamation-circle"></i>
                <div class=""><?php echo $content['header']; ?></div>
            </div>
        </div>
        <div class="col-12 bg-red text-white h-25">
            <div class="text-center">
                <span><i class="fa-solid fa-triangle-exclamation"></i></span>
                <div class=""></div>
            </div>
            <div class="text-center">
                <?php echo $content['message']; ?>
            </div>
        </div>
        <div class="col-12 bg-yellow h-25">
            <div class="text-center"><?php echo $content['info'] ?></div>
        </div>
        <div class="col-12 bg-green h-25">
            <div class="col-12 text-center">
                <a href='/home/index' class=''>
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>