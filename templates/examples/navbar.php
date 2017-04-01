<?php
$urlHome  = $app->url->create("");
$urlAbout = $app->url->create("about");
?>
<nav>
    <a href="<?= $urlHome ?>">Home</a> |
    <a href="<?= $urlAbout ?>">About</a>
</nav>
