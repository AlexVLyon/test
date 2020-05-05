<?php

/**
 * En modell for en emneliste.
 */
class CourseList extends Model {

    //private $courses = [];

    public function __construct(Monolog\Logger $logger, string $search = "") {
        parent::__construct($logger);
        $this->response = $this->loadCourses($search);
    }

    public function loadCourses(string $search): array {
        if (empty($search)) {
            return $this->loadAllCourses();
        }
        return $this->search($search);
    }

    /**
     * Laster inn alle emner fra databasen.
     */
    public function loadAllCourses(): array {
        $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

        $stmt = $mysqliSelect->prepare("SELECT emner.*, navn, bilde FROM emner, foreleser WHERE foreleser = epost");
        $stmt->execute();
        $result = $stmt->get_result();

        $mysqliSelect->close();

        $courses = [];
        while($course = $result->fetch_assoc()) {
            $courses[] = $course;
        }
        return $courses;
    }

    /**
     * Henter alle emnene fra databasen som inneholder et søkeord.
     */
    public function search(string $searchTerm): array {

        $courses = [];

        if (preg_match("/^[a-zA-Z0-9 \æ\ø\å\Æ\Ø\Å ]*$/" , $searchTerm)) {

            $search = '%'.$searchTerm.'%';

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT emner.*, navn FROM emner JOIN foreleser ON foreleser = epost 
                                            WHERE emnekode LIKE ? OR emnenavn LIKE ? OR foreleser LIKE ? OR navn LIKE ?");
            $stmt->bind_param("ssss", $search, $search, $search, $search);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();

            while($course = $result->fetch_assoc()) {
                $courses[] = $course;
            }

            $this->logger->info('Bruker søkte etter søkeord.', ['sokeord' => $searchTerm]);
        }

        else{
            $this->logger->warning('Bruker skrev inn ugyldig tegn i søkefeltet.', ['sokeord' => $searchTerm]);
        }

        return $courses;
    }

}

?>