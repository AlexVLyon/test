<?php

/**
 * Et view for å lage emner.
 */
class CourseCreatorView extends View {

    /**
     * Viser siden for å lage emner.
     */
    public function output(Model $model): void {

        // Henter emnene til foreleseren som er logget inn.
        $response = $model->getResponse();
        $courses = $response['courses'];
        include_once("./pages/courseCreatorPage.php");
    }

}

?>