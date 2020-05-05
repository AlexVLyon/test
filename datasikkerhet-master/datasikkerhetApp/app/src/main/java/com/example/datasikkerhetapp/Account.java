package com.example.datasikkerhetapp;

import com.example.datasikkerhetapp.model.Student;

public class Account {

    private static Student activeUser;

    public static void setActiveUser(String name, String email, String fieldOfStudy, String year) {
        activeUser = new Student(name, email, fieldOfStudy, Integer.parseInt(year));
    }

    public static Student getActiveUser() {
        return activeUser;
    }

    static void userLogOut() {
        activeUser = null;
    }
}
