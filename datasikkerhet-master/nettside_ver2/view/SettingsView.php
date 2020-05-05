<?php

/**
 * Et view for innstillinger.
 */
class SettingsView extends View {

    /**
     * Viser siden med innstillinger.
     */
    public function output(Model $model): void {
        // Hvis man ikke er logget inn som student eller foreleser, har man ikke tilgang til denne siden.
        if (!isset($_SESSION['student']) && !isset($_SESSION['lecturer'])) {
            header("location: index.php");
            die;
        }
        include_once("./pages/settingsPage.php");
    }

}

?>