<?php

/**
 * Et view for en emneliste.
 */
class CourseListView extends View {

    /**
     * Viser emnelistesiden.
     */
    public function output(Model $model): void {
        
        // Henter alle emner.
        $courses = $model->getResponse();
        include_once("./pages/courseListPage.php");
    }

}

?>