<!DOCTYPE html>
<html lang="<?=app()->getLocale()?>">
<head>
    <?php $title = isset($title) && !empty($title) ? strip_tags($title) : 'Oops! An error occurred'; ?>
    <title><?=$title?></title>
    <style>
        body, div, p { margin:0; padding:0; }
        body {background-color:#eee; color:#4f5155; font-size: 1rem; font-weight: normal;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica,
            Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; }
        a {color:#888; background-color:transparent; font-weight:normal;}
        #container { height: 100vh; display: flex; justify-content: center; align-items: center; }
        div.message { max-width: 80vw; min-width: 50vw; padding:20px; margin-right: auto; margin-left: auto;
            border:1px solid #ddd; -webkit-box-shadow:0 0 8px #D0D0D0; background:#fff;
            font-size: 1.1em;
        }
        h1 {margin:0 0 25px 0; font-size:27px;}
    </style>
</head>
<body>
<div id="container">
    <div class="message">
        <h1><?=$title?></h1>
        <?php if (isset($message) && !empty($message)): ?>
        <p><?=$message?></p><br /><br />
        <?php endif; ?>
        <p>go to <a href="javascript:window.history.back()">back</a> or <a href="<?=uri()->base()?>">home</a>.</p>
    </div>
</div>
</body>
</html>
