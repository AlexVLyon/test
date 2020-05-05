<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Henvendelser</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>

    <header>
        <?php include_once("components/navbar_fancy.php") ?>
    </header>

    <main>

        <div class="container">

            <div class="card">
            
                <div style="padding: 10px; margin: 10px">

                    <h1 class="subtitle is-5"><?php echo $course['emnekode']." ".$course['emnenavn'] ?></h1>
                    <p class="subtitle is-6"><?php echo $course['navn'] ?></p>
                    <p class="subtitle is-6"><?php echo $course['epost'] ?></p>
                    <img style="width:150px" src="./bilder/<?php echo $course['bilde'] ?>" />

                
                    <!-- Forelesere kan ikke sende henvendelser. -->
                    <?php if (!isset($_SESSION['lecturer'])): ?>

                        <form method="post">

                            <div class="field is-grouped">

                                <div class="control">

                                    <textarea class="textarea" placeholder="Send en henvendelse" rows="5" cols="50" name="inquiry"></textarea>

                                </div>

                                <div class="control">

                                    <button class="button is-primary" name="sendInquiry">Send</button>

                                </div>

                            </div>   

                        </form>

                    <?php endif; ?>

                </div>

            </div>

            <!-- Henvendelser -->
            <?php foreach ($inquiries as $inquiry): ?>

                <div class="card">

                    <div style="padding: 10px; margin: 10px">

                    <div style="padding: 10px; margin: 10px">
                
                        <p class="subtitle is-6">Henvendelse:</p>

                        <header class="card-header">
                            
                            <p class="card-header-title"><?php echo $inquiry['henvendelse'] ?>

                        </header>

                    <!-- Man kan ikke rapportere sine egne henvendelser. -->
                    <?php if ($_SESSION['user'] != $inquiry['avsender_student'] && $_SESSION['user'] != $inquiry['avsender_gjest']): ?>

                        <form method="post">

                            <div class="field">

                                <div class="control">

                                    <button class="button is-primary" name="reportInquiry">Rapporter</button>

                                </div>

                                <div style="padding: 5px;">

                                    <div class="control">

                                        <input type="hidden" name="id" value=<?php echo $inquiry['id'] ?> />

                                    </div>

                                </div>

                            </div>

                        </form>
                        
                    <?php endif; ?>

                </div>

                <div style="padding: 10px;">

                    <form method="post">

                        <div class="field">

                            <div class="control">

                                <textarea class="textarea" placeholder = "Skriv en kommentar" name="comment" rows="5" cols="50"></textarea>

                            </div>

                            <div class="control">

                            <div style="padding: 10px; margin: 10px">

                                <button class="button is-primary" name="sendComment">Svar</button>
                                <input type="hidden" name="id" value=<?php echo $inquiry['id'] ?> />
                                </div>

                            </div>

                        </div>

                    </form>

                    <!-- Viser svar fra foreleser. -->
                    <?php if (isset($inquiry['svar'])): ?>

                    
                        <p>Svar fra <?php echo $course['navn'] ?>:</p>
                        <p><?php echo $inquiry['svar'] ?>
            

                    <?php endif; ?>

                </div>

                <p>Kommentarer (<?php echo sizeof($inquiry['comments']) ?>):</p>

                <!-- Kommentarer til en henveldelse. -->
                <?php foreach ($inquiry['comments'] as $comment): ?>

                    <div style="padding: 10px;">

                        <p><?php echo $comment['kommentar'] ?></p>

                    </div>

                    <!-- Man kan ikke rapportere sine egne kommentarer. -->
                    <?php if ($_SESSION['user'] != $inquiry['avsender_student'] && $_SESSION['user'] != $inquiry['avsender_gjest']): ?>

                        <form method="post">

                            <button class="button is-primary" name="reportComment">Rapporter</button>
                            <input type="hidden" name="id" value=<?php echo $comment['id'] ?> />

                        </form>

                    <?php endif; ?>

                <?php endforeach; ?>
                </div>
                </div>  
            <?php endforeach; ?>
                </div>
            
        </div>
    </main>
</body>
</html>