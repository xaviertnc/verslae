<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Error 404 - Not Found</title>
    <meta name="viewport" content="width=device-width">
    <style type="text/css">
        body
        {
            font-family:'Droid Sans', sans-serif;
            font-size:10pt;
            color:#555;
            line-height: 25px;
        }

        .wrapper
        {
            width:760px;
            margin:0 auto 5em auto;
        }

        .main
        {
            overflow:hidden;
        }

        .error-spacer
        {
            height:4em;
        }

        a, a:visited
        {
            color:#2972A3;
        }

        a:hover
        {
            color:#72ADD4;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="error-spacer"></div>
        <div role="main" class="main">
            <?php $messages = array('We need a map.', 'I think we\'re lost.', 'We took a wrong turn.'); ?>

            <h1><?php echo $messages[mt_rand(0, 2)]; ?></h1>

            <h2>Server Error: 404 (Not Found)</h2>

            <hr>

            <h3>What does this mean?</h3>

            <p>
                We couldn't find the page you requested on our servers. We're really sorry
                about that. It's our fault, not yours. We'll work hard to get this page
                back online as soon as possible.
            </p>

            <p>
                Perhaps you would like to go to our <a href="<?=__HOME_URL__?>">home page</a>?
            </p>
        </div>
    </div>
</body>
</html>
