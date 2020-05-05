<?php

/**
 * En Controller for emnelister.
 */
class CourseListController extends Controller {
    
    /**
     * Søker etter et bestemt søkeord i emnelisten.
     */
    public function search(CourseList $model): CourseList {
        if (!empty($_GET['search'])) {
            $model = $model->search($_GET['search']);
        }
        return $model;
    }

}

?>