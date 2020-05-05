<?php

/**
 * En Controller for å lage et emne.
 */
class CourseCreatorController extends Controller {

    /**
     * Oppretter et nytt emne.
     */
    public function createCourse(CourseCreator $model): CourseCreator {
        $course = $_POST;
        $course['lecturer'] = $_SESSION['user'];
        return $model->createCourse($course);
    }

}

?>