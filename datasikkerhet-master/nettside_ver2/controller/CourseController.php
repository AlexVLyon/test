<?php

/**
 * En Controller for emner.
 */
class CourseController extends Controller {

    /**
     * Sender inn en henvendelse fra brukeren.
     */
    public function submitInquiry(Course $model): Course {
        $inquiry = $_POST;
        $inquiry['user'] = $_SESSION['user'];

        if (isset($_SESSION['student'])) {
            $model = $model->saveStudentInquiry($inquiry);
        }
        else if (isset($_SESSION['guest'])) {
            $model = $model->saveGuestInquiry($inquiry);
        }
        return $model;
    }

    /**
     * Sender inn en kommentar fra brukeren.
     */
    public function submitComment(Course $model): Course {
        $comment = $_POST;
        $comment['user'] = $_SESSION['user'];

        $course = $model->getResponse();
        $lecturer = $course['epost'];
        if (isset($_SESSION['lecturer']) && $_SESSION['user'] == $lecturer) {
            $model = $model->saveReply($comment);
        }
        else if (isset($_SESSION['student'])) {
            $model = $model->saveStudentComment($comment);
        }
        else if (isset($_SESSION['guest'])) {
            $model = $model->saveGuestComment($comment);
        }
        return $model;
    }

    /**
     * Sender inn en henvendelse fra en student.
     * Denne funksjonen brukes av appen som kun er ment for studenter.
     */
    public function submitStudentInquiry(Course $model): Course {
        return $model->saveStudentInquiry($_POST);
    }

    /**
     * Sender inn en kommentar fra en student.
     * Denne funksjonen brukes av appen som kun er ment for studenter.
     */
    public function submitStudentComment(Course $model): Course {
        return $model->saveStudentComment($_POST);
    }

    /**
     * Rapporterer en henvendelse.
     */
    public function reportInquiry(Course $model): Course {
        if (isset($_POST['id'])) {
            $report['id'] = $_POST['id'];
            $report['user'] = $_SESSION['user'];
            $model = $model->reportInquiry($report);
        }
        return $model;
    }

    /**
     * Rapporterer en kommentar.
     */
    public function reportComment(Course $model): Course {
        if (isset($_POST['id'])) {
            $report['id'] = $_POST['id'];
            $report['user'] = $_SESSION['user'];
            $model = $model->reportComment($report);
        }
        return $model;
    }

    /**
     * Rapporterer en henvendelse.
     * Denne metoden brukes av appen.
     */
    public function studentReportInquiry(Course $model): Course {
        return $model->reportInquiry($_POST);
    }

    /**
     * Rapporterer en kommentar.
     * Denne metoden brukes av appen.
     */
    public function studentReportComment(Course $model): Course {
        return $model->reportComment($_POST);
    }
    
}

?>