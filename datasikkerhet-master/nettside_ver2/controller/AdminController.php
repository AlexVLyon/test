<?php

/**
 * En Controller-klasse for Admin.
 */
class AdminController extends Controller {
    
    /**
     * Godkjenner en foreleser.
     */
    public function authorizeLecturer(Admin $model): Admin {
        return $model->authorizeLecturer($_POST['email']);
    }
    
}

?>