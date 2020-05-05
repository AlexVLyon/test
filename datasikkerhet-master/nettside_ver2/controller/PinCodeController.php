<?php

class PinCodeController {

    public function submitPIN(Course $model): Course {
        $submission['PIN'] = $_POST['PIN'];
        $submission['user'] = $_SESSION['user'];
        return $model->submitPIN($submission);
    }

}

?>