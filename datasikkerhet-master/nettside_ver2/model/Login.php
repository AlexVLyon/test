<?php

/**
 * En modell for login.
 */
class Login extends Model {

    /**
     * Logger brukeren inn.
     */
    public function signIn(array $user): Login {

        $response = array();
        if (!empty($user['email']) && !empty($user['password'])) {

            if (preg_match('/^[A-Za-z0-9_~\-!@#\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\)]+$/', $user['password']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {

                // Prøver å logge inn som student.
                $student = $this->loginStudent($user);

                if (!empty($student)) {
                    $response['error'] = false;
                    $response['message'] = "Logget inn";
                    $response['student'] = true;
                    $response['name'] = $student[0];
                    $response['email'] = $user['email'];
                    $response['fieldOfStudy'] = $student[2];
                    $response['year'] = $student[3];
                    return new Login($this->logger, $response);
                }

                // Prøver å logge inn som foreleser.
                $lecturer = $this->loginLecturer($user);

                if (!empty($lecturer)) {
                    $response['error'] = false;
                    $response['message'] = "Logget inn";
                    $response['lecturer'] = true;
                    $response['name'] = $lecturer[0];
                    $response['email'] = $user['email'];
                    return new Login($this->logger, $response);
                }

                // Prøver å logge inn som administrator.
                $admin = $this->loginAdmin($user);

                if (!empty($admin)) {
                    $response['error'] = false;
                    $response['message'] = "Logget inn";
                    $response['admin'] = true;
                    $response['email'] = $user['email'];
                    return new Login($this->logger, $response);
                }
                $response['error'] = true;
                $response['message'] = "Feil epost eller passord";
            }
            else{
                $response['error'] = true;
                $response['message'] = "Ugyldig tegn";
                $this->logger->warning("Bruker skrev inn ugyldig tegn i innloggingsfeltet.", ['brukernavn' => $user['email']]);
            }
        }
        else {
            $response['error'] = true;
            $response['message'] = "Alle feltene må fylles ut";
            $this->logger->info("Bruker ble ikke logget inn. Innlogging mislykket.", ['brukernavn' => $user['email']]);

        }

        return new Login($this->logger, $response);
    }

    /**
     * Logger inn en student.
     * Brukes av appen, som kun er ment for studenter.
     */
    public function signInStudent(array $user): Login {

        $response = array();
        if (!empty($user['email']) && !empty($user['password'])) {

            if (preg_match('/^[A-Za-z0-9_~\-!@#\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\)]+$/', $user['password']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {

                $student = $this->loginStudent($user);

                // Prøver å logge inn som student.
                if (!empty($student)) {
                    $response['error'] = false;
                    $response['message'] = "Logget inn";
                    $response['student'] = true;
                    $response['name'] = $student[0];
                    $response['email'] = $user['email'];
                    $response['fieldOfStudy'] = $student[2];
                    $response['year'] = $student[3];
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Feil epost eller passord";
                }

            }
            else {
                $response['error'] = true;
                $response['message'] = "Ugydlig tegn";
            }

        }
        else {
            $response['error'] = true;
            $response['message'] = "Alle feltene må fylles ut";
        }

        return new Login($this->logger, $response);

    }

    /**
     * Logger inn en student hvis brukeren finnes i tabellen for studenter.
     */
    private function loginStudent(array $user): array {

        $email = trim($user['email']);
        $password = trim($user['password']);

        $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

        $stmt = $mysqliSelect->prepare("SELECT navn, passord, studieretning, kull FROM student WHERE epost = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $mysqliSelect->close();

        $student = [];

        // Sjekker om det ble returnert en student.
        if ($result->num_rows > 0) {
            $row = $result->fetch_array();

            $pwHash = $row[1];

            if (password_verify($password, $pwHash)) {
                $student = $row;
                $this->logger->info("Student ble logget inn.", ['brukernavn' => $email]);
            }
        }

        return $student;
    }

    /**
     * Logger inn en foreleser hvis brukeren finnes i tabellen for forelesere.
     */
    private function loginLecturer(array $user): array {
       
        $email = trim($user['email']);
        $password = trim($user['password']);

        $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

        $stmt = $mysqliSelect->prepare("SELECT navn, passord FROM foreleser WHERE epost = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $mysqliSelect->close();

        $lecturer = [];

        // Sjekker om det ble returnert en foreleser.
        if ($result->num_rows > 0) {
            $row = $result->fetch_array();

            $pwHash = $row[1];

            // Sjekker om passordet er riktig.
            if (password_verify($password, $pwHash)) {
                $lecturer = $row;
                $this->logger->info("Foreleser ble logget inn.", ['brukernavn' => $email]);
            }
        }

        return $lecturer;
    }

    /**
     * Logger inn en foreleser hvis brukeren finnes i tabellen for administratorer.
     */
    private function loginAdmin(array $user): array {
        
        $email = trim($user['email']);
        $password = trim($user['password']);

        $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

        $stmt = $mysqliSelect->prepare("SELECT passord FROM admin WHERE brukernavn = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $mysqliSelect->close();

        $admin = [];

        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $pwHash = $row[0];

            if (password_verify($password, $pwHash)) {
                $admin = $row;
                $this->logger->info("Admin ble logget inn.", ['brukernavn' => $email]);
            }
        }
        return $admin;
    }

}

?>