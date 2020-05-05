<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
    <header>

        <?php include_once("components/navbar_fancy.php") ?>

        <h1 class="subtitle is-4 is-centered"><?php echo $course['emnekode']." ".$course['emnenavn'] ?></h1>

    </header>

    <main>

        <nav class="level">
    
            <!-- Left side -->
            <div  class="level-left">

                <form method="post">

                    <div class="field has-addons">

                        <div class="control">

                            <input placeholder="PIN" class="input" type="number" name="PIN" />

                        </div>
                                
                        <div class="control">

                            <button class="button is-primary" name="submitPin">Send</button>

                        </div>

                    </div>

                <form>
            </div>
        </nav>

        <!-- Skriver ut en tilbakemelding hvis PIN-koden er feil. -->
        <?php if (isset($course['error']) && $course['error'] == true): ?>
            <p><?php echo $course['message'] ?></p>
        <?php endif; ?>
    </main>
</body>
</html>