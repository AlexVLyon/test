<?php

/**
 * En model for innstillinger.
 */
class Settings extends Model {

    public function __construct(Monolog\Logger $logger, bool $changed = false, array $response = []) {
        parent::__construct($logger, $response);
    }

    /**
     * Endrer passordet til en student.
     */
    public function changeStudentPassword($passwords): Settings {

        $email = stripslashes(trim(htmlspecialchars($passwords['user'])));

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT passord FROM student WHERE epost = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $mysqliSelect->close();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $student = $result->fetch_assoc();

                $oldPassword = $passwords['oldPassword'];
                $pwHash = $student['passord'];

                // Sjekker at passordet er riktig.
                if (password_verify($oldPassword, $pwHash)) {

                    $newPassword1 = $passwords['newPasswordFirst'];
                    $newPassword2 = $passwords['newPasswordSecond'];

                    // Sjekker at det nye passordet er fyllt ut riktig i begge feltene.
                    if ($newPassword1 == $newPassword2 && !empty($newPassword1)) {

                        //Sjekker at det nye passordet som er skrevet inn kun inneholder tillate tegn.
                        if (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}/", $newPassword1)) {


                            $newPassword = password_hash($newPassword1, PASSWORD_DEFAULT);

                            $mysqliUpdate = new MySQLi($this->servername, $this->usernameUpdate, $this->passwordUpdate, $this->dbname);

                            $stmt = $mysqliUpdate->prepare("UPDATE student SET passord = ? WHERE epost = ?");
                            $stmt->bind_param("ss", $newPassword, $email);
                            $stmt->execute();

                            $mysqliUpdate->close();

                            if ($stmt->affected_rows > 0) {

                                $response['error'] = false;
                                $response['message'] = "Passord endret";
                                $this->logger->info('Student oppdaterte passordet sitt.', ['brukernavn' => $email]);

                                return new Settings($this->mysqli, $this->logger, true, $response);
                            }
                            else {
                                $response['error'] = true;
                                $response['message'] = "Det oppstod en feil";
                                $this->logger->info('Student prøvde å oppdatere passordet sitt. Oppdatering mislykket.', ['brukernavn' => $email]);
                            }
                        }
                        else{
                            $response['error'] = true;
                            $response['message'] = "Ugyldig tegn";
                            $this->logger->warning('Student skrev inn ugyldig tegn i nyttpassord-feltet', ['brukernavn' => $email]);
                        }
                    }
                    else {
                        $response['error'] = true;
                        $response['message'] = "Det nye passordet er feil";
                    }
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Feil passord";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Det oppstod en feil";
                $this->logger->warning('Student prøvde å oppdatere passordet til en epost som ikke finnes i systemet. Oppdatering mislykket.', ['brukernavn' => $email]);
            }
        }
        else {
            $response['error'] = true;
            $response['message'] = "Det oppstod en feil";
            $this->logger->warning('Student prøvde å oppdatere passordet til en ugyldig epostadresse. Oppdatering mislykket.', ['brukernavn' => $email]);
        }

        return new Settings($this->logger, false, $response);
    }

    /**
     * Endrer passordet til en foreleser.
     */
    public function changeLecturerPassword($passwords): Settings {

        $email = stripslashes(trim(htmlspecialchars($passwords['user'])));

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT passord FROM foreleser WHERE epost = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $mysqliSelect->close();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $lecturer = $result->fetch_assoc();

                $oldPassword = $passwords['oldPassword'];
                $pwHash = $lecturer['passord'];

                // Sjekker om brukeren har skrevet inn riktig passord.
                if (password_verify($oldPassword, $pwHash)) {

                    $newPassword1 = $passwords['newPasswordFirst'];
                    $newPassword2 = $passwords['newPasswordSecond'];

                    // Sjekker at det nye passordet er fyllt ut riktig i begge feltene.
                    if ($newPassword1 == $newPassword2 && !empty($newPassword1)) {

                        //Sjekker at det nye passordet som er skrevet inn kun inneholder tillatte tegn.
                        if (preg_match("/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}/", $newPassword1)) {

                            $newPassword = password_hash($newPassword1, PASSWORD_DEFAULT);

                            $mysqliUpdate = new MySQLi($this->servername, $this->usernameUpdate, $this->passwordUpdate, $this->dbname);

                            $stmt = $mysqliUpdate->prepare("UPDATE foreleser SET passord = ? WHERE epost = ?");
                            $stmt->bind_param("ss", $newPassword, $email);
                            $stmt->execute();

                            $mysqliUpdate->close();

                            if ($stmt->affected_rows > 0) {

                                $response['error'] = false;
                                $response['message'] = "Passord endret";

                                $this->logger->info('Foreleser oppdaterte passordet sitt.', ['brukernavn' => $email]);

                                return new Settings($this->mysqli, $this->logger, true);
                            }
                            else {
                                $response['error'] = true;
                                $response['message'] = "Det oppstod en feil";
                                $this->logger->info('Foreleser prøvde å oppdatere passordet sitt. Oppdatering mislykket.', ['brukernavn' => $email]);
                            }
                        }
                        else{
                            $response['error'] = true;
                            $response['message'] = "Ugyldig tegn";
                            $this->logger->warning('Foreleser skrev inn ugyldig tegn i nyttpassord-feltet', ['brukernavn' => $email]);
                        }
                    }
                    else {
                        $response['error'] = true;
                        $response['message'] = "Det nye passordet er feil";
                    }
                }
                else {
                    $response['error'] = true;
                    $response['message'] = "Feil passord";
                }
            }
            else {
                $response['error'] = true;
                $response['message'] = "Det oppstod en feil";
                $this->logger->warning('Foreleser prøvde å oppdatere passordet til en epost som ikke finnes i systemet. Oppdatering mislykket.', ['brukernavn' => $email]);
            
            }
        }
        else {
            $response['error'] = true;
            $response['message'] = "Det oppstod en feil";
            $this->logger->warning('Foreleser prøvde å oppdatere passordet til en ugyldig epostadresse. Oppdatering mislykket.', ['brukernavn' => $email]);
        }

        return new Settings($this->logger, $response);
    }

}

?>