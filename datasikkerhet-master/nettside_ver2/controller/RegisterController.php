<?php

/**
 * En Controller for registrering.
 */
class RegisterController extends Controller {

    /**
     * Registrerer en ny student eller foreleser.
     */
    public function signUp(Register $model): Register {

        // Registrerer en ny student.
        if (isset($_POST['studentSubmit'])) {
            $model = $model->registerStudent($_POST);
        }
        // Registrerer en ny foreleser.
        else if (isset($_POST['lecturerSubmit']) && isset($_FILES['image'])) {
            $registration = $_POST;
            $registration['image'] = $_FILES['image'];
            $model = $model->registerLecturer($registration);
        }

        return $model;
    }

    /**
     * Registrerer en ny student.
     * Denne funksjonen brukes av mobilappen som kun er ment for studenter.
     */
    public function signUpStudent(Register $model): Register {
        return $model->registerStudent($_POST);
    }

}

?>