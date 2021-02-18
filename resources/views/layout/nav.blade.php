<div class="pos-f-t">
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark navbar-dark navbar-expand p-4">
            <ul class="navbar-nav bd-navbar-nav flex-row">
                <nav-bar-item current="{{$page}}" name="Home" url="/tg/"></nav-bar-item>
                <nav-bar-item current="{{$page}}" name="Log" url="/tg/log/"></nav-bar-item>
                <nav-bar-item current="{{$page}}" name="Test" url="/tg/test/"></nav-bar-item>
            </ul>
        </div>
    </div>
    <nav class="navbar navbar-expand navbar-dark py-3">
        <div class="container">
            <span class="navbar-brand mb-0 h1 mr-0 mr-md-2"><i class="fas fa-database"></i> BOTs</span>
            <ul class="navbar-nav bd-navbar-nav flex-row d-none d-md-flex">
                <nav-bar-item current="{{$page}}" name="Home" url="/tg/"></nav-bar-item>
                <nav-bar-item current="{{$page}}" name="Log" url="/tg/log/"></nav-bar-item>
                <nav-bar-item current="{{$page}}" name="Test" url="/tg/test/"></nav-bar-item>
            </ul>
            <ul class="navbar-nav ml-md-auto">
                <nav-bar-link icon="fas fa-envelope" url="mailto:info@lusw.dev"></nav-bar-link>
                <nav-bar-link icon="fab fa-github" url="//github.com/luswdev"></nav-bar-link>
                <nav-bar-link icon="fab fa-linkedin-in" url="//www.linkedin.com/in/callum-lu"></nav-bar-link>
            </ul>
        <button class="navbar-toggler btn btn-styled d-inline-block d-md-none" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>   
        </div>
    </nav>
</div>