<?php

return function ($route, $text) use ($app) {
    ?>
    <li class="<?= $app->request->getCurrentUrl() == $route ? 'active': '' ?>">
        <a href="<?= $route ?>"><?= $text ?></a>
    </li>
    <?php };
