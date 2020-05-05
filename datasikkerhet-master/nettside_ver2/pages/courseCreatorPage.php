<!DOCTYPE html>
<html lang="no">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src = "https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js" ></script>
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <title>Legg til emne</title>

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
                <h1 class="subtitle is-2 is-centered">LEGG TIL EMNER:</h1>
            </div>

        </div>

    </div>

    <div class="columns">

        <div class="column is-half " class="column">
    	
    	    <div id="card" class="card">
		  		
  			    <div  class="card-content">

                    <?php if (isset($response['error']) && $response['error'] == true): ?>
                        <p><?php echo $response['message'] ?></p>
                    <?php endif; ?>
  				
                    <p class="card-header-title is-centered"></p>
                
                    <div class="field">
        
                        <form method="post">

                            <label>Emnekode:</label>
                            <input type="text" class="input is-primary"  name="coursecode" />

                            <br />
                
                            <label>Emnenavn:</label>
                            <input type="text" class="input is-primary"  name="coursename" />

                            <br />
                            
                            <label>Emnekode:</label>
                            <input type="number" class="input is-primary"  name="pin" />

                            <br />

                            <button name="submitCourse" class="button is-primary" class="button is-link">Legg til</button>      
                
                        </form>
            
                    </div>

                </div>

            </div>

        </div>

        <div class="column is-half" id="tabell" class="column">

            <div id="emnerCard" class="card">

                <table id="emner" class="table">

                    <thead>

                        <tr>
                            <th>Emnekode</th>
                            <th>Navn</th>
                            <th>Henvendelser</th>
                        </tr>

                    </thead>

                        <tbody>

                            <!-- Skriver ut emnene til foreleseren som er logget inn. -->
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course['emnekode'] ?></td>
                                    <td><?php echo $course['emnenavn'] ?></td>
                                    <td><a href="index.php?page=course&code=<?php echo $course['emnekode'] ?>">Se henvendelser</a></td>
                                </tr>
                            <?php endforeach; ?>
                            
                        </tbody>
                        
                </table>

            </div>

        </div>

    </div>

</body>
</html>