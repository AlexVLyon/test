<?php

/**
 * En Controller for login.
 */
class LoginController extends Controller {

    /**
     * Logger inn en bruker.
     */
    public function signIn(Login $model): Login {
        return $model->signIn($_POST);
    }

    /**
     * Logger inn en student.
     * Denne metoden brukes av appen som kun er ment for studenter.
     */
    public function signInStudent(Login $model): Login {
        return $model->signInStudent($_POST);
    }
    
}

?>