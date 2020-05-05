<!DOCTYPE html>
<html lang="no">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Emner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    
</head>
  
<body>
  	
  	<header>
  		
        <?php include_once("components/navbar_fancy.php") ?>
		
	</header>

    <nav class="level">
    
        <!-- Left side -->
        <div  class="level-left">

            <form method="get">

                <div class="field has-addons">

                    <div class="control">

                        <input type="hidden" name="page" value="courses" />
                        <input class="input" type="text" placeholder="Søk" name="search" />

                    </div>
                           
                    <div class="control">

                        <button class="button is-primary">Søk</button>

                    </div>

                      
                    
                </div>

            </form>

        </div>
        
    </nav>
    
    <hr>
	
	<div class="columns">
    	
		<div class="container">

			<div class="column">
						
				<table class="table">

					<thead>

						<tr>

							<th>Emnekode</th>
							<th>Navn</th>
							<th>Foreleser</th>
							<th>Epost</th>
                            <th>Henvendelser</th>
						
                        </tr>
					
                    </thead>
				
                    <tbody>

                        <tbody>

                            <!-- Skriver ut alle emnene og hvem som er foreleser i hvert emne. -->
                            <?php foreach ($courses as $course): ?>
                                
                                <tr>
                                    <td><?php echo $course['emnekode'] ?></td>
                                    <td><?php echo $course['emnenavn'] ?></td>
                                    <td><?php echo $course['navn'] ?></td>
                                    <td><?php echo $course['foreleser'] ?></td>
                                    <td><a href="index.php?page=course&code=<?php echo $course['emnekode'] ?>">Se henvendelser</a></td>
                                </tr>

                            <?php endforeach; ?>
                            
                        </tbody>
					
				    </tbody>
				
			    </table>
			
			</div>
  
		</div>
	
	    <hr>


</body>
  
</html>