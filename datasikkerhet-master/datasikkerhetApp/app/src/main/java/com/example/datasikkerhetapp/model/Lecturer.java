package com.example.datasikkerhetapp.model;

import android.media.Image;

public class Lecturer extends Person {
    //private Image lecturerPhoto;
    private String imgString;

    public Lecturer(String name, String email, String imgString) {
        super(name, email);
        //this.lecturerPhoto = lecturerPhoto;
        this.imgString = imgString;
    }

    public String getImgString() {
        return imgString;
    }

    /*
    public Image getLecturerPhoto() {
        return lecturerPhoto;
    }

     */
}
