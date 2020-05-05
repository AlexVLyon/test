<nav>
    <a href="index.php">Hjem</a>
    <a href="index.php?page=courses">Emner</a>
    <a href="index.php?page=app">App</a>

    <!-- Viser innstillinger hvis man er logget inn som student eller foreleser. -->
    <?php if (isset($_SESSION['student']) || isset($_SESSION['lecturer'])): ?>
        <a href="index.php?page=settings">Innstillinger</a>
    <?php endif; ?>

    <!-- Viser "Logg ut"-knappen hvis man er logget inn. -->
    <?php if (isset($_SESSION['loggedIn'])): ?>
        <a href="index.php?page=logout">Logg ut</a>
    <?php endif; ?>
</nav>