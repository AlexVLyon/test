package com.example.datasikkerhetapp;

import java.net.URL;

public class Links {

    private static final String HTTP = "https://";
    private static final String IP_ADRESS = "158.39.188.221";
    private static final String DIR = "/app_api/";
    private static final String FILE = "app_api.php";
    private static final String PARAM = "?page=";
    private static final String URL = HTTP+IP_ADRESS+DIR+FILE+PARAM;

    static final String URL_IMG = HTTP+IP_ADRESS+"/bilder/";

    static final String LOGIN = URL+"login";
    static final String REGISTER = URL+"register";
    static final String SETTINGS = URL+"settings";
    static final String COURSES = URL+"courses";
    static final String COURSE = URL+"course&code=";

}
