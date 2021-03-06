<?php if (! $userLoggedIn) : ?>
    <div id="login-menu" class="<?= $showLogin ? '' : 'hidden' ?> form-group">
        <form class="navbar-text navbar-right" method="post" action="<?= $loginHandler ?>">
            <a href="<?= $newUser ?>" class="right-divider">Skapa ny användare</a>
            <span>Inloggning: </span>
            <input placeholder="Användarnamn" id="user" name="user" type="text">
            <input placeholder="Lösenord" id="password" name="password" type="password">
            <button name="login" value="attempt" class="btn btn-primary btn-sm">Logga in</button>
            <button name="login" value="cancel" class="btn btn-warning btn-sm">Avbryt</button>
        </form>
    </div>
<?php elseif ($userLoggedIn) : ?>
    <div id="login-menu" class="<?= $showLogin ? '' : 'hidden' ?>">
        <form class="navbar-text navbar-right" method="post" action="<?= $logoutHandler ?>">
            <span class="right-divider">Inloggad som: <?= $username ?></span>
            <?php if ($isAdmin) : ?>
                <a href="<?= $adminUsers ?>" class="right-divider">Användare</a>
                <a href="<?= $adminContent ?>" class="right-divider">Innehåll</a>
                <a href="<?= $adminWebshop ?>" class="right-divider">Lager</a>
            <?php endif ?>
            <a href="<?= $changePassword ?>" class="right-divider">Ändra lösenord</a>
            <a href="<?= $userProfile ?>" class="right-divider">Profil</a>
            <button name="logout" value="logout" class="btn btn-primary btn-sm">Logga ut</button>
            <button name="logout" value="cancel" class="btn btn-warning btn-sm">Avbryt</button>
        </form>
    </div>
<?php endif ?>

<script>
loginButton = document.getElementById("login-button");
loginMenu = document.getElementById("login-menu");

loginButton.addEventListener("click", function () {
    console.log("whatnot");
    loginMenu.classList.toggle("hidden");
});
</script>
