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
            
        <div  class="column is-full" class="column">
            
            <!--Header-->
            <div class="container is-centered">
                <h1 class="subtitle is-2 is-centered" >Velkommen, <?php echo $_SESSION['name'] ?></h1>
                <h2 class="subtitle is-5 is-centered" >Dette er din startside</h2>
            </div>

        </div>
                
    </div>	
        
    <hr>
        

    
    
</body>
  
</html>
