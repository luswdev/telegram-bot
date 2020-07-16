<!DOCTYPE html>
<html>
<head>
	<title>C-3PO Log</title>
    <link rel="icon" type="image/png" href="/data/img/work.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <!-- CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.0/css/all.min.css">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&family=Open+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.0/css/all.min.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/styles/androidstudio.min.css">
    
    <!-- JavaScript -->
    <script src="//cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>

    <!-- User Sources -->
    <link rel="stylesheet" type="text/css" href="styles/main.css"> 
    <link rel="stylesheet" type="text/css" href="styles/variable.css"> 
    <link rel="stylesheet" type="text/css" href="styles/helper.css"> 

</head>
<body>
    <header id="top">
        <div class="header-inner">
            <nav>
                <div class="nav-wrapper container">
                    <a class="brand-logo">
                        C-3PO Log
                    </a>
                </div>
            </nav>
        </div>
    </header>
	<main>
		<div class="main-inner">
            <div class="cards container row">
                <?php
                    $dirs = array_filter(glob('*'), 'is_dir');
                    foreach ($dirs as $dir) {
                        if (!is_numeric($dir[0])) {
                            continue;
                        }
                        
                        echo '<div class="col s12 m6 l4">'
                        . '<div class="card">'
                        . '<div class="card-content">'
                        . '<span class="card-title">'.$dir.'</span>'
                        . '<a href="#log-modal" class="btn modal-trigger waves-effect waves-teal" date="'.$dir.'" value="updated.txt">updated</a>'
                        . '<a href="#log-modal" class="btn modal-trigger waves-effect waves-teal" date="'.$dir.'" value="exec.txt">exec</a>'
                        . '</div>'
                        . '</div>'
                        . '</div>';
                    }
                ?>
            </div>
        </div>
        <div class="test">

        </div>
    </main>
    <footer>
        <div class="footer-inner"> 
        </div>
    </footer>
    <div id="log-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4></h4>
            <pre>
                <code class="json z-depth-4"></code>
            </pre>
        </div>
        <div class="modal-footer">
            <a class="raw-log waves-effect waves-light btn-flat">Raw</a>
            <a class="download-log waves-effect waves-light btn-flat">Download</a>
            <a class="modal-close waves-effect waves-light btn-flat">OK</a>
        </div>
    </div>
    <script src="scripts/main.js"></script>
</body>
</html>
