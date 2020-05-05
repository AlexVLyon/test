<!DOCTYPE html>
<html lang="no">
<head>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
  
<body>

    <header>

        <?php include_once("components/navbar_fancy.php") ?>

    </header>

    <div class="columns">
        
        <div class="column is-half " class="column"></div>
        
        <!--Login Card-->
        <div class="column is-half" class="has-background-grey-lighter" class="column">
                
            <form method="post">
            
                <div id="login_card" class="card">
            
                    <div class="card-content">
            
                        <p class="card-header-title is-centered">Logg inn:</p>
        
                        <div class="field">
            
                            <p class="control has-icons-left has-icons-right">
                                <input class="input" type="email" placeholder="E-post" name="email">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </p>

                        </div>
            
                        <div class="field">

                            <p class="control has-icons-left">

                                <input class="input" type="password" placeholder="Passord" name="password">

                                <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>

                            </p>

                        </div>
            
                        <div class="field">

                            <nav class="level">

                                <!-- Left side -->
                                <div class="level-left">
                                
                                    <p class="control">
                                        <button class="button is-primary" name="login" id= "loginBtn">Logg inn</button>
                                    </p>

                                </div>

                                <div class="level-right">
                
                                    <p class="control">
                                        <p class="is-vcentered">Ikke registrert? <a href="index.php?page=register">Trykk her!</a></p> 
                                    </p>

                                </div>

                                <br> 

                            <nav>
                
                        </div>

                        <?php if (isset($response['error']) && $response['error'] == true): ?>
                            <h3 name="errorMessage"><?php echo $response['message'] ?></h3>
                        <?php endif; ?>
            
                    </div>
        
                </div>

            </form>

        </div>

    </div>	
    
    <hr>
    
</body>
  
</html>

