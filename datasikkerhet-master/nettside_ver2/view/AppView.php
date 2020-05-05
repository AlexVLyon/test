<?php

class AppView extends View {

    public function output(Model $model): void {
        include_once("./pages/appPage.php");
    }

}

?>