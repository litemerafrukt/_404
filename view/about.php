<div class="about-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-message">
                    <h1>Om oophp</h1>
                    <hr class="intro-divider">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-section-a">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-sm-6 lead">
                <h2>Om webbplatsen</h2>
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>

                <p>Denna sida är skapd som en del av kursen <a href="http://dbwebb.se/oophp">oophp</a> på <a href="http://bth.se">Blekinge Tekniska Högskola</a>.</p>
                <p>Jag som skapat sidan heter Anders Nygren och nås lättast på mail: <a href="mailto:litemerafrukt@gmail.com">litemerafrukt@gmail.com</a>.</p>
                <p><a href="https://github.com/litemerafrukt/_404">Git-repo</a></p>
                <p><a href="<?= $app->url->create('api/sysinfo') ?>">JSON-sysinfo</a></p>
            </div>
            <!--<div class="col-lg-5 col-lg-offset-2 col-sm-6">
                <img class="img-responsive" src="{{ url_for('static', filename='img/python.jpg') }}" alt="">
            </div>-->
        </div>

    </div>
</div>

<div class="content-section-b">

    <div class="container">

        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6 lead">
                <h2>Om kursen</h2>
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>

                <p>Kursen är en fortsättningskurs i php. Vi ska skapa ett eget ramverk utifrån ramverket anax-light och fortsätta titta på unit-testning, tdd, databaser.</p>

            </div>
            <div class="col-lg-5 col-sm-pull-6 col-sm-6">
                <img class="img-responsive" src="<?= $app->url->asset('image/tamingphp.jpg') ?>" alt="taming php">
            </div>
        </div>

    </div>

</div>
