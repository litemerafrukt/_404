<?php

$app->router->add('admin/**', function () use ($app) {
    if (! $app->user->isAdmin()) {
        return $app->stdErr("Du har inte adminbehörighet.")->send(403);
        die();
//        throw new Anax\Route\ForbiddenException("Du har ingen adminbehörighet.");
    }
    // Quickfix for router matching this guard and sets match but might not find subpage
    return $app->response;
});
