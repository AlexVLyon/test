<?php

/**
 * En modell for å lage emner.
 */
class CourseCreator extends Model {

    public function __construct(Monolog\Logger $logger, string $lecturer, array $response = []) {
        parent::__construct($logger, $response);
        $this->response['courses'] = $this->loadCourses($lecturer);
    }

    /**
     * Laster inn emnene til foreleseren som er logget inn.
     */
    private function loadCourses(string $lecturer): array {

        $courses = [];

        if (filter_var($lecturer, FILTER_VALIDATE_EMAIL)) {

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT *, navn FROM emner, foreleser WHERE foreleser = ? AND foreleser = epost");
            $stmt->bind_param("s", $lecturer);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();

            while ($course = $result->fetch_assoc()) {
                $courses[] = $course;
            }

        }
        return $courses;
    }

    /**
     * Lagrer et nytt emne i databasen.
     */
    public function createCourse($course): CourseCreator {

        $email = trim($course['lecturer']);

        if (!empty($course['coursename']) && !empty($course['coursecode']) && !empty($course['PIN']) && !empty($email)) {

            $courseName = trim($course['coursename']);
            $courseCode = trim($course['coursecode']);
            $pin = trim($course['PIN']);

            //Sjekker at emnenavn og emnekode bare inneholder bokstaver og tall, og at pinkoden bare inneholder tall. 
            if (preg_match("/^[a-zA-Z0-9 ]*$/" , $courseName) && preg_match("/^[A-Z]+(|-[A-Z]+)[0-9]+$/", $courseCode) && preg_match("/^[0-9]*$/" , $pin) && strlen($pin) == 4 && filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $pin = password_hash($pin, PASSWORD_DEFAULT);

                if ($this->isAuthorized($email)) {

                    $mysqliInsert = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

                    $stmt = $mysqliInsert->prepare("INSERT INTO emner (emnekode, emnenavn, foreleser, PIN) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $courseCode, $courseName, $email, $pin);
                    $stmt->execute();

                    $mysqliInsert->close();

                    $response['error'] = false;
                    $response['message'] = "Nytt emne opprettet";
                    $this->logger->info('Foreleser opprettet et nytt emne.', ['emnekode' => $courseCode, 'brukernavn' => $email]);
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Ikke godkjent";
                    $this->logger->info('Foreleser som ikke er godkjent prøvde å opprette et nytt emne. Opprettelse mislykket.', ['brukernavn' => $email]);
                }
            }
            else{
                $response['error'] = true;
                $response['message'] = "Ugyldig informasjon";
                $this->logger->info('Foreleser prøvde å opprette et nytt emne. Opprettelse mislykket.', ['brukernavn' => $email]);
            }
        }
        else {
            $response['error'] = true;
            $response['message'] = "Alle feltene må fylles ut";
        }

        return new CourseCreator($this->logger, $email, $response);
    }

    /**
     * Sjekker om foreleseren som er logget inn er autorisert.
     */
    public function isAuthorized($lecturer): bool {

        $lecturer = stripslashes(trim(htmlspecialchars($lecturer)));

        if (filter_var($lecturer, FILTER_VALIDATE_EMAIL)) {

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT * FROM foreleser WHERE godkjent = 1 AND epost = ?");
            $stmt->bind_param("s", $lecturer);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();

            if ($result->num_rows > 0) {
                return true;
            }
        }
        return false;
    }

}

?>