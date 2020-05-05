<?php

class App extends Model {

    const dir = "./app/";
    const filename = "datasikkerhetApp.apk";

    public function __construct(Monolog\Logger $logger, array $response = []) {
        parent::__construct($logger, $response);
    }

    public function download(): App {

        $file = App::dir.App::filename;

        $response = array();
        if (file_exists($file)) {
            header("Content-Description: File Transfer");
            header("Content-Type: application/vnd.android.package-archive");
            header("Content-Disposition: attachment; filename=".App::filename);
            header("Content-Transfer-Encoding: binary");
            header("Expires: 0");
            header("Cache-Control: must-revalidate; post-check: 0, pre-check=0");
            header("Pragma: public");
            header("Content-Length: ".filesize($file));
            ob_clean();
            flush();

            readfile($file);

            $response['error'] = false;
            $response['message'] = "Nedlasting vellykket";

            $this->logger->info('Bruker lastet ned appen.', ['brukernavn' => $_SESSION['user']]);
        }
        else {
            $response['error'] = false;
            $response['message'] = "Nedlasting mislykket";
            $this->logger->info('Bruker prøvde å laste ned appen. Nedlasting mislykket.', ['brukernavn' => $_SESSION['user']]);
        }

        return new App($this->mysqli, $this->logger, $response);
    }

}

?>