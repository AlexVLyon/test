<?php

include("model/Model.php");
include("view/View.php");
include("controller/Controller.php");

include("logger.php");

// Database
$servername = "localhost";
$username = "root";
$password = "skosaalen!";

/*
$usernameRoot = "root";
$passwordRoot = "skosaalen!";

$usernameAdd = "add";
$passwordAdd = "blokkade";

$usernameRead = "read";
$passwordRead = "lysglimt";
*/

$dbname = "datasikkerhet";

// Oppretter forbindelse med databasen.
$mysqli = new MySQLi($servername,$username,$password,$dbname);

/*
$mysqliRoot = new MySQLi($servername,$usernameRoot,$passwordRoot,$dbname);
$mysqliAdd = new MySQLi($servername,$usernameAdd,$passwordAdd,$dbname);
$mysqliRead = new MySQLi($servername,$usernameRead,$passwordRead,$dbname);
*/

// Starter en sesjon.
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = "Gjest-".uniqid();
    $_SESSION['guest'] = true;
}

$logger->pushProcessor(function ($record) {
    $record['extra']['user'] = $_SESSION['user'];

    return $record;
});

$page = $_GET['page'] ?? '';

// En "router", som sender brukeren til riktig side:

// Hvis det ikke er spesifisert noen side, og man er logget inn som student eller foreleser,
// blir man sendt til hjemmesiden.
if ($page == '' && (isset($_SESSION['student']) || isset($_SESSION['lecturer']))) {
    include("model/Home.php");
    include("view/HomeView.php");

    $model = new Home($logger);
    $view = new HomeView();
}

// Hvis det ikke er spesifisert noen side, og man er logget inn som administrator,
// blir man sendt til administratorsiden.
else if ($page == '' && isset($_SESSION['admin'])) {
    include("model/Admin.php");
    include("view/AdminView.php");
    include("controller/AdminController.php");

    $model = new Admin($logger);
    $view = new AdminView();
    $controller = new AdminController();

    if (isset($_POST['authorize'])) {
        $model = $controller->authorizeLecturer($model);
    }
}

// Hvis man ikke er logget inn, og det ikke er spesifisert noen side, blir man sendt til login-siden.
else if ($page == '') {
    include("model/Login.php");
    include("view/LoginView.php");
    include("controller/LoginController.php");

    $model = new Login($logger);
    $view = new LoginView();
    $controller = new LoginController();

    if (isset($_POST['login'])) {
        $model = $controller->signIn($model);

        $user = $model->getResponse();

        // Logger inn bruker med
        if (isset($user['error']) && $user['error'] == false) {
            $_SESSION['loggedIn'] = true;
            if (isset($user['student'])) {
                $_SESSION['student'] = true;
            }
            else if (isset($user['lecturer'])) {
                $_SESSION['lecturer'] = true;
            }
            else if (isset($user['admin'])) {
                $_SESSION['admin'] = true;
            }
            $_SESSION['user'] = $user['email'];
            $_SESSION['name'] = $user['name'];
        }
    }
}
else if ($page == 'register') {
    include("model/Register.php");
    include("view/RegisterView.php");
    include("controller/RegisterController.php");

    $model = new Register($logger);
    $view = new RegisterView();
    $controller = new RegisterController();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $model = $controller->signUp($model);

        $user = $model->getResponse();

        if ($user['error'] == false) {
            $_SESSION['loggedIn'] = true;
            if (isset($user['student'])) {
                $_SESSION['student'] = true;
            }
            else if (isset($user['lecturer'])) {
                $_SESSION['lecturer'] = true;
            }
            $_SESSION['user'] = $user['email'];
            $_SESSION['name'] = $user['name'];
        }
    }
}

// Hvis man prøver å gå til siden for emner, og er logget inn som foreleser,
// sendes man til en side hvor man kan opprette nye emner og se sine egne emner.
else if ($page == 'courses' && isset($_SESSION['lecturer'])) {
    include("model/CourseCreator.php");
    include("view/CourseCreatorView.php");
    include("controller/CourseCreatorController.php");

    $model = new CourseCreator($logger, $_SESSION['user']);
    $view = new CourseCreatorView();
    $controller = new CourseCreatorController();

    if (isset($_POST['submitCourse'])) {
        $model = $controller->createCourse($model);
    }
}
else if ($page == 'courses') {
    include("model/CourseList.php");
    include("view/CourseListView.php");
    include("controller/CourseListController.php");

    $search = $_GET['search'] ?? '';

    $model = new CourseList($logger, $search);
    $view = new CourseListView();
    $controller = new CourseListController();
}

// Hvis man prøver å gå til en side for et emne, og er logget inn, eller har PIN-kode,
// blir man sendt til emnesiden.
else if ($page == 'course' && isset($_GET['code']) && (isset($_SESSION['loggedIn']) || isset($_SESSION['access'][$_GET['code']]))) {
    include("model/Course.php");
    include("view/CourseView.php");
    include("controller/CourseController.php");

    $model = new Course($logger, $_GET['code']);
    $view = new CourseView();
    $controller = new CourseController();

    if (isset($_POST['sendInquiry'])) {
        $model = $controller->submitInquiry($model);
    }
    else if (isset($_POST['reportInquiry'])) {
        $model = $controller->reportInquiry($model);
    }
    else if (isset($_POST['sendComment'])) {
        $model = $controller->submitComment($model);
    }
    else if (isset($_POST['reportComment'])) {
        $model = $controller->reportComment($model);
    }
}

// Hvis man ikke er logget inn, og ikke har oppgitt riktig PIN-kode, blir man sendt til en side
// hvor man må skrive inn en PIN-kode.
else if ($page == 'course' && isset($_GET['code'])) {
    include("model/Course.php");
    include("view/PinCodeView.php");
    include("controller/PinCodeController.php");

    $model = new Course($logger, $_GET['code']);
    $view = new PinCodeView();
    $controller = new PinCodeController();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $model = $controller->submitPIN($model);

        $response = $model->getResponse();
        if (isset($response['error']) && $response['error'] == false) {
            $_SESSION['access'][$_GET['code']] = $_POST['PIN'];
        }
    }
}
else if ($page == 'settings') {
    include("model/Settings.php");
    include("view/SettingsView.php");
    include("controller/SettingsController.php");

    $model = new Settings($logger);
    $view = new SettingsView();
    $controller = new SettingsController();

    if (isset($_POST['changePassword'])) {
        $model = $controller->changePassword($model);
    }
}
else if ($page == 'app') {
    include("model/App.php");
    include("view/AppView.php");
    include("controller/AppController.php");

    $model = new App($logger);
    $view = new AppView();
    $controller = new AppController();

    if (isset($_POST['download'])) {
        $model = $controller->download($model);
    }
}

// Ødelegger sesjonen hvis man logger ut.
else if ($page == 'logout') {

    $logger->info('Bruker logget ut.', ['brukernavn' => $_SESSION['user']]);

    session_destroy();
    header("location: index.php");
}

// Får feilmelding hvis man prøver å gå til en side som ikke eksisterer.
else {

    $logger->info('Bruker prøvde å gå til en side som ikke eksisterer.', ['side' => $page]);

    http_response_code(404);
    die;
}

// Skriver ut siden.
$view->output($model);

?>