<?php

/**
 * Et view for admin.
 */
class AdminView extends View {

    /**
     * Viser adminsiden.
     */
    public function output(Model $model): void {
        
        // Henter forelesere som ikke er godkjente.
        $response = $model->getResponse();
        $lecturers = $response['lecturers'];
        include_once('./pages/adminPage.php');
    }

}