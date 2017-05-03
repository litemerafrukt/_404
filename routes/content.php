<?php

use Anax\Route\NotFoundException;

$app->router->add('blog', function () use ($app, $tlz) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    $blogPostToHtml = function ($blogPost) use ($app, $tlz) {
        $blogTitle = $tlz->textbox($blogPost->title)->esc()->text();
        $blogBody = $tlz->textbox($blogPost->data)->esc()->text();
        $blogLink = $app->url->create("blog/{$blogPost->slug}");
        $data =[
            'title' => $blogTitle,
            'link'  => $blogLink,
            'body'  => $tlz->filterText($blogBody, $blogPost->filter),
        ];

        $renderedBlogPost = $tlz->render(_404_INSTALL_PATH . '/view/blog/blogpost.php', $data);

        return $renderedBlogPost;
    };

    $blogPosts = array_map($blogPostToHtml, $contentDb->allPublished('post'));

    $allBlog = implode("<br>", $blogPosts);

    $app->view->add("layout", ["title" => "Blog"], "layout");
    $app->view->add("blog/blog", compact('allBlog'), "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});


$app->router->add('blog/{post}', function ($post) use ($app, $tlz) {
    $contentAccessDb = new _404\Database\ContentAccess($app->dbconnection);

    $eitherBlogPost = $tlz->eitherEmpty($contentAccessDb->searchPost($post), "$post not found.");

    return $eitherBlogPost->resolve(
        function ($blogPost) use ($app, $tlz) {
            $blogTitle = $tlz->textbox($blogPost->title)->esc()->text();
            $blogBody = $tlz->textbox($blogPost->data)->esc()->text();
            $data =[
                'title' => $blogTitle,
                'link'  => '#',
                'body'  => $tlz->filterText($blogBody, $blogPost->filter),
            ];

            $app->view->add("layout", ["title" => $blogTitle], "layout");
            $app->view->add("blog/blogpost", $data, "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        },
        function ($errMsg) {
            throw new NotFoundException($errMsg);
        }
    );
});

$app->router->add('pages', function () use ($app, $tlz) {
    $contentDb = new _404\Database\Content($app->dbconnection);

    $dbPageToHtml = function ($page) use ($app, $tlz) {
        $data =[
            'title' => $page->title,
            'link'  => $app->url->create("pages/$page->path"),
            'body'  => $tlz->filterText($page->data, $page->filter),
        ];

        $renderedPage = $tlz->render(_404_INSTALL_PATH . '/view/blog/blogpost.php', $data);

        return $renderedPage;
    };

    $pages = array_map($dbPageToHtml, $contentDb->allPublished('page'));

    $allPages = implode("<br>", $pages);

    $app->view->add("layout", ["title" => "Sidor"], "layout");
    $app->view->add("pages/pages", compact('allPages'), "main");

    return $app->response->setBody($app->view->renderBuffered("layout"));
});


$app->router->add('pages/{page}', function ($page) use ($app, $tlz) {
    $contentAccessDb = new _404\Database\ContentAccess($app->dbconnection);

    $eitherPage = $tlz->eitherEmpty($contentAccessDb->searchPage($page), "$page not found.");

    return $eitherPage->resolve(
        function ($page) use ($app, $tlz) {
            $filteredPage = $tlz->filterText($page->data, $page->filter);

            $app->view->add("layout", ["title" => $page->title], "layout");
            // $app->view->add("pages/page", $data, "main");
            $app->view->addString($filteredPage, "main");

            return $app->response->setBody($app->view->renderBuffered("layout"));
        },
        function ($errMsg) {
            throw new NotFoundException($errMsg);
        }
    );
});
