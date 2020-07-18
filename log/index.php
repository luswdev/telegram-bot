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
    <header>
        <div class="header-inner">
            <nav>
                <div class="nav-wrapper container">
                    <a class="brand-logo">
                        C-3PO Log
                    </a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="/test/"><i class="fas fa-bolt"></i></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
	<main>
		<div class="main-inner container">
            <?php
                $dirs = array_filter(glob('*'), 'is_dir');
                $dirs = array_reverse($dirs);
                $lastMonth = substr($dirs[2],0,-3);

                echo '<ul class="collapsible" id="collapsible">'
                . '<li>'
                . '<div class="collapsible-header"><i class="material-icons">event</i>'.$lastMonth.'<span class="badge">1</span></div>'
                . '<div class="collapsible-body">';

                foreach ($dirs as $dir) {
                    if (!is_numeric($dir[0])) {
                        continue;
                    }

                    $dirMonth = substr($dir,0,-3);
                    if ($lastMonth != $dirMonth) {
                        $lastMonth = $dirMonth;
                        echo '</div>'
                        . '</li>'
                        . '<li>'
                        . '<div class="collapsible-header"><i class="material-icons">event</i>'.$lastMonth.'<span class="badge">1</span></div>'
                        . '<div class="collapsible-body">';
                    }
                        
                    echo '<div class="collapsible-row">'
                    . '<span class="date-title">'.$dir.'</span>'
                    . '<span>'
                    . '<a href="#log-modal" class="btn-flat modal-trigger" date="'.$dir.'" value="updated.txt">updated</a>'
                    . '<a href="#log-modal" class="btn-flat modal-trigger" date="'.$dir.'" value="exec.txt">exec</a>'
                    . '</span>'
                    . '</div>';
                }

                echo '</li>'
                . '</ul>';
            ?>
        </div>
    </main>
    <footer>
        <div class="footer-inner container">
            &copy <?php echo date('Y'); ?> LuSkywalker 
        </div>
    </footer>
    <div id="log-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4 id="log-title"></h4>
            <pre>
                <code class="json z-depth-4" id="log-body"></code>
            </pre>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-light btn-flat" id="raw-log" target="_blank">Raw</a>
            <a class="modal-close waves-effect waves-light btn-flat" id="download-log">Download</a>
            <a class="modal-close waves-effect waves-light btn-flat">OK</a>
        </div>
    </div>
    <script src="scripts/main.js"></script>
</body>
</html>
