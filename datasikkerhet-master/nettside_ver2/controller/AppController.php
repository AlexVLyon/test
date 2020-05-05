<?php

class AppController extends Controller {

    public function download(App $model): App {
        return $model->download();
    }

}

?>