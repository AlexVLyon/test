<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src = "https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
  <link href="css/styles.css" rel="stylesheet" type="text/css" />

  <title>Instillinger </title>

  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  
</head>

<body>

    <header>

        <?php include_once("components/navbar_fancy.php") ?>

    </header>
      
    <div class="columns">
        
        <div class="column">

            <!--Header-->
            <div class="container is-centered">

                <h1 class="subtitle is-2 is-centered">INNSTILLINGER</h1>

            </div>

        </div>

    </div>

    <div class="content">

        <div class="columns">
        
            <div class="column is-one-third" >
            
                <div id="byttpassordCard" class="card">
                        
                    <div class="card-content">
                        
                        <p class="card-header-title is-centered">Bytt passord:</p>
                    
                        <form method="post">

                            <div class="field">
                
                                <label class="label">Skriv inn gammelt passord</label>
                                <input type="password" class="input is-primary" name="oldPassword" pattern="[^'\x22]+" />
                
                                <br /><br />

                                <label class="label">Skriv inn nytt passord</label>
                                <input type="password" class="input is-primary" name="newPasswordFirst" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                                title = "Passord må bestå av minst åtte tegn, minst en stor og en liten bokstav, minst ett tall og en spesialtegn" />
                
                                <br><br>

                                <label class="label is-centered">Gjenta det nye passordet</label>
                                <input type="password" class="input is-primary" name="newPasswordSecond" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}"
                                title = "Passord må bestå av minst åtte tegn, minst en stor og en liten bokstav, minst ett tall og en spesialtegn" />
            
                            </div>

                            <br /><br />

                            <div class="field is-grouped is-grouped-centered">
                                <button name="changePassword" class="button is-primary is-centered" >Bytt</button>     
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>