<?php

/**
 * En abstrakt klasse for modeller.
 * En modell holder på data som den henter fra databasen, eller skriver data til databasen.
 */
abstract class Model {

    protected $logger;
    protected $response;

    protected $servername = "localhost";
    protected $dbname = "datasikkerhet";

    protected $usernameAdd = "add";
    protected $passwordAdd = "blokkade";

    protected $usernameRead = "read";
    protected $passwordRead = "lysglimt";

    /**
     * En konstruktør for modeller som tar imot en kobling til en database.
     */
    public function __construct(Monolog\Logger $logger, array $response = []) {
        $this->logger = $logger;
        $this->response = $response;
    }

    public function getResponse() {
        return $this->response;
    }

}

?>