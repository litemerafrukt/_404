<!-- Each menu view file has access to $routes array and $currentRoute -->
<ul class="list-inline">
    <?php foreach ($routes as $route) : ?>
        <li class="">
            <a href="<?= $route['route'] ?>"><?= $route['text'] ?></a>
        </li>
    <?php endforeach; ?>
</ul>
