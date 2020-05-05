package com.example.datasikkerhetapp.model;

import java.util.Collections;

public class Comment implements Comparable<Comment> {

    private int id;
    private String comment;
    private boolean user;

    public Comment(int id, String comment, boolean user) {
        this.id = id;
        this.user = user;
        this.comment = comment;
    }

    public int getId() {
        return id;
    }

    public String getComment() {
        return comment;
    }

    public boolean isUser() {
        return user;
    }

    @Override
    public int compareTo(Comment c) {
        return c.id - id;
    }
}
