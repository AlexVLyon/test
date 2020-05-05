<?php

/**
 * En modell for Admin.
 */
class Admin extends Model {

    public function __construct(Monolog\Logger $logger, array $response = []) {
        parent::__construct($logger, $response);
        $lecturers = $this->loadUnauthorizedLecturers();
        $this->response['lecturers'] = $this->loadUnauthorizedLecturers();
    }

    /**
     * Henter forelesere som ikke er godkjente fra databasen.
     */
    private function loadUnauthorizedLecturers(): array {
        $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

        $stmt = $mysqliSelect->prepare("SELECT * FROM foreleser WHERE godkjent = 0");
        $stmt->execute();
        $result = $stmt->get_result();

        $mysqliSelect->close();

        $lecturers = [];
        while($lecturer = $result->fetch_assoc()) {
            $lecturers[] = $lecturer;
        }
        return $lecturers;
    }

    /**
     * Godkjenner en foreleser.
     */
    public function authorizeLecturer(string $email): Admin {

        $email = trim($email);

        $response = array();
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $mysqliUpdate = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliUpdate->prepare("UPDATE foreleser SET godkjent = 1 WHERE epost = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            if($stmt->affected_rows > 0){
                $response['error'] = false;
                $response['message'] = "Foreleser godkjent";
                $this->logger->info('Admin godkjente en foreleser.', ['admin' => $_SESSION['user'], 'foreleser' => $email]);
            }
            else{
                $response['error'] = true;
                $response['message'] = "Noe gikk galt";
                $this->logger->warning('Admin prøvde å godkjenne en foreleser som ikke eksisterer eller allerede er godkjent.', ['brukernavn' => $email]);
            }

            $mysqliUpdate->close();

        }
        else {
            $response['error'] = false;
            $response['message'] = "Ugyldig bruker";
            $this->logger->warning('Admin prøvde å autorisere en ugyldig epost.', ['admin' => $_SESSION['user'], 'foreleser' => $email]);
        }

        return new Admin($this->logger, $response);
    }

}

?>