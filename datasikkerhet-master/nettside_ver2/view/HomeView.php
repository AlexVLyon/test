<?php

/**
 * Et view for hjemmesiden.
 */
class HomeView extends View {

    /**
     * Viser hjemmesiden.
     */
    public function output(Model $model): void {
        include_once("./pages/homePage.php");
    }

}

?>