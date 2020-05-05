<?php

/**
 * En controller for innstillinger.
 */
class SettingsController extends Controller {

    /**
     * Endrer passordet til en bruker.
     */
    public function changePassword(Settings $model): Settings {
        $password = $_POST;
        $password['user'] = $_SESSION['user'];
        
        // Endrer passordet til en student.
        if (isset($_SESSION['student'])) {
            $model = $model->changeStudentPassword($password);
        }
        // Endrer passordet til en foreleser.
        else if (isset($_SESSION['lecturer'])) {
            $model = $model->changeLecturerPassword($password);
        }
        return $model;
    }

    /**
     * Endrer passordet til en student.
     * Denne metoden brukes av mobilappen, som kun er ment for studenter.
     */
    public function changeStudentPassword(Settings $model): Settings {
        return $model->changeStudentPassword($_POST);
    }
    
}

?>