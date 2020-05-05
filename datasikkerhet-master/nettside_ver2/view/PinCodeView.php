<?php

/**
 * Et view for en PIN-kode.
 */
class PinCodeView extends View {

    /**
     * Viser siden hvor man skriver inn en PIN-kode til et emne.
     */
    public function output(Model $model): void {
        // Henter info om emnet.
        $course = $model->getResponse();

        if (!isset($course['emnekode'])) {
            http_response_code(404);
            die;
        }

        // Hvis PIN-koden er riktig, blir man omdirigert til siden for emnet.
        if (!isset($_SESSION['loggedIn']) && isset($_SESSION['access'][$course['emnekode']])) {
            header("location: index.php?page=course&code=".$course['emnekode']);
            die;
        }
        include_once("./pages/pinCodePage.php");
    }

}

?>