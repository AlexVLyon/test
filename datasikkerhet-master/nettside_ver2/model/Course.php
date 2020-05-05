<?php

/**
 * En model for et emne.
 */
class Course extends Model {

    public function __construct(Monolog\Logger $logger, string $code, array $response = []) {
        parent::__construct($logger);
        $course = $this->loadCourse($code);
        $this->response = array_replace($response, $course);
        $this->response['inquiries'] = $this->loadInquiries($code);
    }

    /**
     * Lagrer en henvendelse fra en student i databasen.
     */
    public function saveStudentInquiry(array $inquiry): Course {

        if (!empty($inquiry['inquiry']) && !empty($inquiry['user']) && preg_match('/^[A-Za-z0-9_~\-!@#?.\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\) ]+$/', $inquiry['inquiry'])) {

            $inquiry1 = stripslashes(trim(htmlspecialchars($inquiry['inquiry'])));
            $user = stripslashes(trim(htmlspecialchars($inquiry['user'])));

            $mysqliInsert = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliInsert->prepare("INSERT INTO henvendelse (avsender_student, mottaker, emnekode, henvendelse) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $user, $this->response['epost'], $this->response['emnekode'], $inquiry1);
            $stmt->execute();

            $mysqliInsert->close();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Henvendelse sendt";
                $this->logger->info('En student sendte en henvendelse.', ['brukernavn' => $user, 'henvendelse' => $inquiry['inquiry']]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Henvendelsen ble ikke sendt";
                $this->logger->info('En student prøvde å sende en henvendelse. Forsøk mislykket.', ['brukernavn' => $user, 'henvendelse' => $inquiry['inquiry']]);
            }

        }
        else {
            $response['error'] = true;
            $response['message'] = "Tomt tekstfelt";
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Lagrer en henvendelse fra en gjest i databasen.
     */
    public function saveGuestInquiry(array $inquiry): Course {

        if (!empty($inquiry['inquiry']) && !empty($inquiry['user']) && preg_match('/^[A-Za-z0-9_~\-!@#?.\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\) ]+$/', $inquiry['inquiry'])) {

            $inquiry1 = stripslashes(trim(htmlspecialchars($inquiry['inquiry'])));
            $user = stripslashes(trim(htmlspecialchars($inquiry['user'])));

            $mysqliInsert = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliInsert->prepare("INSERT INTO henvendelse (avsender_gjest, mottaker, emnekode, henvendelse) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $inquiry['user'], $this->response['epost'], $this->response['emnekode'], $inquiry1);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Henvendelse sendt";
                $this->logger->info('En gjest sendte en henvendelse.', ['brukernavn' => $user, 'henvendelse' => $inquiry1]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Henvendelsen ble ikke sendt";
                $this->logger->info('En gjest prøvde å sende en henvendelse. Forsøk mislykket.', ['brukernavn' => $user, 'henvendelse' => $inquiry1]);
            }

            $mysqliInsert->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Tomt tekstfelt";
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Lagrer svaret fra en foreleser i databasen.
     */
    public function saveReply(array $reply): Course {

        if (!empty($reply['comment']) && !empty($reply['user']) && preg_match('/^[A-Za-z0-9_~\-!@#?.\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\) ]+$/', $reply['comment'])) {

            $replyId = stripslashes(trim(htmlspecialchars($reply['id'])));
            $replyC = stripslashes(trim(htmlspecialchars($reply['comment'])));
            $replyUser = stripslashes(trim(htmlspecialchars($reply['user'])));

            $mysqliUpdate = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliUpdate->prepare("UPDATE henvendelse SET svar = ? WHERE id = ? AND svar IS NULL");
            $stmt->bind_param("si", $replyC, $replyId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Svar sendt";
                $this->logger->info('Foreleser svarte på en henvendelse.', ['foreleser' => $replyUser, 'id' => $replyId, 'svar' => $replyC]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Svar ble ikke sendt";
                $this->logger->info('Foreleser prøvde å svare på en henvendelse. Forsøk mislykket.', ['foreleser' => $replyUser, 'id' => $replyId, 'svar' => $replyC]);
            }

            $mysqliUpdate->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Tomt tekstfelt";
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Lagrer en kommentar fra en student i databasen.
     */
    public function saveStudentComment(array $comment): Course {

        if (!empty($comment['id']) && !empty($comment['comment']) && !empty($comment['user']) && preg_match('/^[A-Za-z0-9_~\-!@#?.\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\) ]+$/', $comment['comment'])) {

            $commentC = stripslashes(trim(htmlspecialchars($comment['comment'])));
            $commentId = stripslashes(trim(htmlspecialchars($comment['id'])));
            $commentUser = stripslashes(trim(htmlspecialchars($comment['user'])));

            $mysqliInsert = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliInsert->prepare("INSERT INTO kommentar (avsender_student, kommentar_til, kommentar) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $commentUser, $commentId, $commentC);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Kommentar sendt";
                $this->logger->info('En student sendte en kommentar til en henvendelse.', ['brukernavn' => $commentUser, 'kommentar' => $commentC]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Kommentar ble ikke sendt";
                $this->logger->info('En student prøvde å sende en kommentar til en henvendelse. Forsøk mislykket.', ['brukernavn' => $commentUser, 'kommentar' => $commentC]);
            }

            $mysqliInsert->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Tomt tekstfelt";
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Lagrer en kommentar fra en gjest i databasen.
     */
    public function saveGuestComment(array $comment): Course {

        if (!empty($comment['id']) && !empty($comment['comment']) && !empty($comment['user']) && preg_match('/^[A-Za-z0-9_~\-!@#?.\$%\Æ\Ø\Å\æ\ø\å\^&\*\(\) ]+$/', $comment['comment'])) {

            $commentC = stripslashes(trim(htmlspecialchars($comment['comment'])));
            $commentId = stripslashes(trim(htmlspecialchars($comment['id'])));
            $commentUser = stripslashes(trim(htmlspecialchars($comment['user'])));

            $mysqliInsert = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliInsert->prepare("INSERT INTO kommentar (avsender_gjest, kommentar_til, kommentar) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $commentUser, $commentId, $commentC);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Kommentar sendt";
                $this->logger->info('En gjest sendte en kommentar til en henvendelse.', ['brukernavn' => $commentUser, 'kommentar' => $commentC]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Kommentar ble ikke sendt";
                $this->logger->info('En gjest prøvde å sende en kommentar til en henvendelse. Forsøk mislykket.', ['brukernavn' => $commentUser, 'kommentar' => $commentC]);
            }

            $mysqliInsert->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Tomt tekstfelt";
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Rapporterer en henvendelse.
     */
    public function reportInquiry(array $report): Course {

        $response = array();
        if (!empty($report['id']) && !empty($report['user'])) {

            $id = stripslashes(trim(htmlspecialchars($report['id'])));
            $user = stripslashes(trim(htmlspecialchars($report['user'])));

            $mysqliUpdate = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliUpdate->prepare("UPDATE henvendelse SET rapportert = rapportert + 1 WHERE id = ? AND (avsender_gjest IS NULL OR avsender_gjest != ?) AND (avsender_student IS NULL OR avsender_student != ?)");
            $stmt->bind_param("iss", $id, $user, $user);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Henvendelse rapportert";
                $this->logger->info('Bruker rapporterte en henvendelse.', ['brukernavn' => $user, 'id' => $id]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Noe gikk galt";
                $this->logger->info('Bruker prøvde å rapportere en henvendelse. Henvendelse ble ikke rapportert.', ['brukernavn' => $user, 'id' => $id]);
            }

            $mysqliUpdate->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Noe gikk galt";
            $this->warning('Brukernavn eller henvendelse-ID ble ikke oppgitt.', ['brukernavn' => $report['user'], 'id' => $report['id']]);
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Rapporterer en kommentar.
     */
    public function reportComment(array $report): Course {

        $response = array();
        if (!empty($report['id']) && !empty($report['user'])) {

            $id = stripslashes(trim(htmlspecialchars($report['id'])));
            $user = stripslashes(trim(htmlspecialchars($report['user'])));

            $mysqliUpdate = new MySQLi($this->servername, $this->usernameAdd, $this->passwordAdd, $this->dbname);

            $stmt = $mysqliUpdate->prepare("UPDATE kommentar SET rapportert = rapportert + 1 WHERE id = ? AND (avsender_gjest IS NULL OR avsender_gjest != ?) AND (avsender_student IS NULL OR avsender_student != ?)");
            $stmt->bind_param("iss", $id, $user, $user);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['error'] = false;
                $response['message'] = "Kommentar rapportert";
                $this->logger->info('Bruker rapporterte en kommentar.', ['brukernavn' => $user, 'id' => $id]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Noe gikk galt";
                $this->logger->info('Bruker prøvde å rapportere en kommentar. Kommentar ble ikke rapportert.', ['brukernavn' => $user, 'id' => $id]);
            }

            $mysqliUpdate->close();
        }
        else {
            $response['error'] = true;
            $response['message'] = "Noe gikk galt";
            $this->warning('Brukernavn eller kommentar-ID ble ikke oppgitt.', ['brukernavn' => $report['user'], 'id' => $report['id']]);
        }

        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Sjekker om en PIN-kode som er oppgitt av en bruker er riktig, og gir en tilbakemelding.
     */
    public function submitPIN(array $submission): Course {
        if (!empty($submission['user']) && !empty($submission['PIN']) && preg_match("/^[0-9]*$/", $submission['PIN']) && strlen($submission['PIN']) == 4) {

            $pin = $submission['PIN'];
            $user = stripslashes(trim(htmlspecialchars($submission['user'])));

            if ($this->isCorrectPIN($pin)) {
                $response['error'] = false;
                $response['message'] = "Riktig PIN-kode";
                $this->logger->info('Bruker skrev inn riktig PIN-kode for et emne.', ['brukernavn' => $user, 'emnekode' => $this->response['emnekode']]);
            }
            else {
                $response['error'] = true;
                $response['message'] = "Feil PIN-kode";
            }
        }
        else {
            $response['error'] = true;
            $response['message'] = "Feil PIN-kode";
            $this->logger->warning('Bruker prøvde å oppgi PIN-kode for et emne. Noe gikk galt.', ['brukernavn' => $submission['user'], 'emnekode' => $this->response['emnekode']]);
        }
        return new Course($this->logger, $this->response['emnekode'], $response);
    }

    /**
     * Laster inn emnet fra databasen.
     */
    private function loadCourse(string $code): array {

        if (preg_match("/^[A-Z]+(|-[A-Z]+)[0-9]+$/", $code)) {

            $code = stripslashes(trim(htmlspecialchars($code)));

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT emnekode, emnenavn, PIN, navn, epost, bilde FROM emner, foreleser WHERE epost = foreleser AND emnekode = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }

        }

        $this->logger->info('Bruker prøvde å gå til et emne som ikke finnes.', ['emnekode' => $code]);

        return [];
    }

    /**
     * Laster inn henvendelsene til emnet.
     */
    private function loadInquiries(string $code): array {

        $inquiries = [];

        if (preg_match("/^[A-Z]+(|-[A-Z]+)[0-9]+$/", $code)) {

            $code = stripslashes(trim(htmlspecialchars($code)));

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT * FROM henvendelse WHERE emnekode = ? ORDER BY id DESC");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();

            while($inquiry = $result->fetch_assoc()) {
                $inquiry['comments'] = $this->loadComments($inquiry['id']);
                $inquiries[] = $inquiry;
            }
        }
        return $inquiries;
    }

    /**
     * Laster inn kommentarene til en henvendelse.
     */
    private function loadComments(int $id): array {

        $comments = [];

        if (preg_match("/^[0-9]*$/", $id)) {

            $id = stripslashes(trim(htmlspecialchars($id)));

            $mysqliSelect = new MySQLi($this->servername, $this->usernameRead, $this->passwordRead, $this->dbname);

            $stmt = $mysqliSelect->prepare("SELECT * FROM kommentar WHERE kommentar_til = ? ORDER BY id DESC");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $mysqliSelect->close();
            
            while($comment = $result->fetch_assoc()) {
                $comments[] = $comment;
            }
        }
        return $comments;
    }

    /**
     * Sjekker om en foreleser er foreleser for det valgte emnet.
     */
    public function isNotCourseLecturer(array $user) {
        if (isset($user['lecturer'])) {
            if ($user['user'] == $this->response['epost']) {
                return false;
            }

            $this->logger->info('Foreleser prøvde å gå til et emne som de ikke har tilgang til.', ['foreleser' => $user['user'], 'emnekode' => $this->response['emnekode']]);
        
            return true;
        }

        return false;
    }

    /**
     * Sjekker om en PIN-kode er riktig.
     */
    public function isCorrectPIN(int $pin): bool {

        if (preg_match("/^[0-9]*$/", $pin) && strlen($pin) == 4) {

            $pinHash = $this->response['PIN'];
            return password_verify($pin, $pinHash);

        }
        return false;
    }

}

?>