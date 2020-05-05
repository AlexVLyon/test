<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
    <header>
        <?php include_once("components/navbar_fancy.php") ?>
    </header>

    <div class="columns">
            
        <div  class="column" class="column">
            
            <!--Header-->
            <div id="adminHeader" >

                <h1 class="subtitle is-2 is-centered" >Velkommen</h1>
                <h2 class="subtitle is-5 is-centered" >Dette er din startside</h2>

            </div>

        </div>
                
    

        <div class="column is-two-fifths">

            <main>

                <!-- Hviser hvor mange forelesere som ikke er godkjente. -->
                <?php if (($numLecturers = sizeof($lecturers)) > 0): ?>

                    <p class="subtitle is-5 is-centered"><?php echo $numLecturers." ".($numLecturers > 1 ? "forelesere" : "foreleser") ?> venter på å bli godkjent:</p>

                <?php else: ?>

                    <p class="subtitle is-6 is-centered">Ingen nye forelesere.</p>

                <?php endif; ?>

                <!-- Skriver ut navn og e-post til foreleserene som ikke er godkjente. -->
                <?php foreach ($lecturers as $lecturer): ?>
    
                    <div class="container is-centered">

                        <div class="card">

                            <div class="card-content">

                            <p class="subtitle is-6 is-centered">Navn:  <?php echo $lecturer['navn'] ?></p>

                            <p class="subtitle is-6 is-centered">E-post:  <?php echo $lecturer['epost'] ?></p>

                            <form method="post">

                                <button class="button is-primary" name="authorize">Godkjenn</button>
                                <input type="hidden" name="email" value=<?php echo $lecturer['epost'] ?> />
                            </form>

                            </div>
                            

                        </div>

                    </div>

                <?php endforeach; ?>

            </main>

        </div>
    </div>
</body>
</html>