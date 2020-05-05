<!DOCTYPE html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src = "https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
  <link href="css/styles.css" rel="stylesheet" type="text/css" />

  <title>Registrer</title>

  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  
</head>

<body>

    <header>

        <?php include_once("components/navbar_fancy.php") ?>

    </header>

	<div class="content">

        <?php if (isset($response['error']) && $response['error'] == true): ?>
            <p name="errorMessage"><?php echo $response['message'] ?></p>
        <?php endif; ?>

        <div class="columns">
    	
            <!-- Studentregistrering -->
            <div class="column is-half" id="studentReg">
    	
                <div id="studentCard" class="card">
		  		
		            <div  class="card-content">
  				
  			            <p class="subtitle is-2 is-centered">Student</p>
		  	
		                <div class="field">

                            <form method="post">
                        
                                <label class="label">Navn</label>
                                <input type="text" class="input is-primary" name="name" pattern="[A-Za-z  \æ\ø\å\Æ\Ø\Å ]" title="Navn kan bare inneholde bokstaver."/>

                                <label class="label">E-post</label>
                                <input type="email" class="input is-primary" name="email" title = "Skriv inn en gyldig e-postadresse."/>
                
                                <label class="label">Studieretning</label>
                                <input type="text" class="input is-primary" name="fieldOfStudy" />

                                <label class="label">Årskull</label>
                                <input type="number" class="input is-primary" pattern="[0-9]{4}" name="year" title="fire siffer."/>

                                <div class="field is-grouped is-grouped-multiline">

                                    <label class="label">Passord</label>
                                    <input type="password" class="input is-primary " name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*\W).{8,}"
                                    title = "Passord må bestå av minst åtte tegn, minst en stor og en liten bokstav, minst ett tall og et spesialtegn"/>

                                </div>

                                <button name="studentSubmit" class="button is-primary" class="button is-link"> Registrer</button>     
                                <p>Er du allerede registrert? <a href="index.php">Logg inn her!</a></p> 
                        
                            </form>

	     		        </div>

     	            </div>

                </div>

            </div>

            <!-- Foreleserregistrering -->
            <div class="column is-half" id="foreleserReg">
            
            <div id="foreleserCard" class="card">
                    
                <div class="card-content">
                    
                    <p class="subtitle is-2 is-centered">Foreleser</p>
                
                    <div class="field">

                        <form method="post" enctype="multipart/form-data">
                        
                            <label class="label">Navn</label>
                            <input type="text" class="input is-primary" name="name" />

                            <label class="label">E-post</label>
                            <input type="text" class="input is-primary" name="email" />

                            <label class="label">Bilde</label>
                            <input type="file" class="input is-primary" name="image" />
    
                            <br>

                            <div class="field">

                                <label class="label">Passord</label>  
                                <input type="password" class="input is-primary " name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*\W).{8,}"
                                title = "Passord må bestå av minst åtte tegn, minst en stor og en liten bokstav, minst ett tall og et spesialtegn" />

                            </div>

                            <button name="lecturerSubmit" class="button is-primary" class="button is-link"> Registrer</button>     
                            <p>Er du allerede registrert? <a href="index.php">Logg inn her!</a></p> 
                        
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>