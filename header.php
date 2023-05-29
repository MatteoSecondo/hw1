<?php

    require_once 'auth.php';

    echo '<header class="header">

            <div>
                <h1>Restaurant Compare</h1>
            </div>

            <nav class="navbar">
            <a href="index.php">Home</a>  
            <a href="personal_area.php">Area Personale</a>
            <a href="contact_us.php">Contattaci</a>';
            
    if (checkAuth()) 
    {
        echo '<a href="logout.php">Logout</a>';
    }
    else
    {
        echo '<a href="login.php">Accedi/Registrati</a>';
    }
                               
    echo '</nav>

          <div class="hamburger">
              <div></div>
              <div></div>
              <div></div>
          </div>
          </header>';
    
?>