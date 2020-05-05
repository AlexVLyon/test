<?php

/**
 * Et view for et emne.
 */
class CourseView extends View {

    /**
     * Viser et emne.
     */
    public function output(Model $model): void {

        // Sjekker at det valgte emnet er satt.
        if (!isset($_GET['code'])) {
            header("location: index.php");
            die;
        }

        // Henter info om emnet.
        $course = $model->getResponse();

        if (empty($course)) {
            http_response_code(404);
            die;
        }

        // Hvis man ikke er logget inn, og ikke har riktig PIN-kode, har man ikke tilgang til å vise siden.
        if (!isset($_SESSION["loggedIn"])) {
            if (isset($_SESSION['access'][$course['emnekode']])) {
                $pin = $_SESSION['access'][$course['emnekode']];

                if (!$model->isCorrectPIN($pin)) {
                    unset($_SESSION['access'][$course['emnekode']]);
                    header("location: index.php?page=course&code=".$course['emnekode']);
                    die;
                }
            }
            else  {
                header("location: index.php?page=course&code=".$course['emnekode']);
                die;
            }
        }

        // Hvis man er logget inn som en foreleser, har man ikke tilgang til andre forelesere sine emner.
        if ($model->isNotCourseLecturer($_SESSION)) {

            http_response_code(403);
            die;
        }

        // Henter henvendelsene til emnet.
        $inquiries = $course['inquiries'];
        include_once("./pages/coursePage.php");
    }

}

?>