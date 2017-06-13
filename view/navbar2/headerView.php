<!-- Each menu view file has access to $routes array and $currentRoute -->
<ul class="nav navbar-nav navbar-right">
    <?php foreach ($routes as $route) : ?>
        <li class="<?= $currentRoute == $route['route'] ? 'active': '' ?>">
            <a href="<?= $route['route'] ?>"><?= $route['text'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
