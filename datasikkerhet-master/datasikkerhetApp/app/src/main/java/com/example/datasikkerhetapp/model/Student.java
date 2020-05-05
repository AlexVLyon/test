package com.example.datasikkerhetapp.model;

public class Student extends Person {

    private String fieldOfStudy;
    private int year;

    public Student(String name, String email, String fieldOfStudy, int year) {
        super(name, email);
        this.fieldOfStudy = fieldOfStudy;
        this.year = year;
    }

    public String getFieldOfStudy() {
        return fieldOfStudy;
    }

    public int getYear() {
        return year;
    }
}
