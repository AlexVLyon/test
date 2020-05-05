<?php

/**
 * Et view for login.
 */
class LoginView extends View {

    /**
     * Viser login-siden.
     */
    public function output(Model $model): void {
        // Hvis man allerede er logget inn, har man ikke tilgang til denne siden.
        if (isset($_SESSION['loggedIn'])) {
            header("location: index.php");
            die;
        }

        $response = $model->getResponse();

        include_once("./pages/loginPage.php");
    }

}