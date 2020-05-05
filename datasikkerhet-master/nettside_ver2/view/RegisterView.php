<?php

/**
 * Et view for registrering.
 */
class RegisterView extends View {

    /**
     * Viser siden for registrering.
     */
    public function output(Model $model): void {
        // Hvis man allerede er logget inn, har man ikke tilgang til denne siden.
        if (isset($_SESSION['loggedIn'])) {
            header("location: index.php");
            die;
        }

        $response = $model->getResponse();

        include_once("./pages/registerPage.php");
    }

}

?>