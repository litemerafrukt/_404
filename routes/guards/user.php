<?php

$app->router->add('user/**', function () use ($app) {
    if (! $app->user->isUser()) {
        return $app->stdErr("Du är inte inloggad.")->send(403);
        die();
//        throw new Anax\Route\ForbiddenException("Du är inte inloggad.");
    }

    // Quickfix for router matching this guard and sets match but might not find subpage
    return $app->response;
});
