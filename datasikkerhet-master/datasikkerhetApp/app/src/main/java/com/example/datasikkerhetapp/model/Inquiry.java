package com.example.datasikkerhetapp.model;

import java.util.ArrayList;

public class Inquiry implements Comparable<Inquiry> {

    private int id;
    private boolean user;
    private String inquiry;
    private String response;
    private ArrayList<Comment> comments;

    public Inquiry(int id, boolean user, String inquiry, ArrayList<Comment> comments) {
        this.id = id;
        this.user = user;
        this.inquiry = inquiry;
        this.comments = comments;
        response = null;
    }

    public Inquiry(int id, boolean user, String inquiry, ArrayList<Comment> comments, String response) {
        this.id = id;
        this.user = user;
        this.inquiry = inquiry;
        this.response = response;
        this.comments = comments;
    }

    public int getId() {
        return id;
    }

    public boolean isUser() {
        return user;
    }

    public String getInquiry() {
        return inquiry;
    }

    public String getResponse() {
        return response;
    }

    public ArrayList<Comment> getComments() {
        return comments;
    }

    @Override
    public int compareTo(Inquiry i) {
        return i.id - id;
    }
}
