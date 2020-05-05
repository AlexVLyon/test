package com.example.datasikkerhetapp;


import android.graphics.Color;
import android.os.Bundle;

import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.os.StrictMode;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.TextView;

import com.example.datasikkerhetapp.model.Course;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class CourseListFragment extends Fragment {


    private LinearLayout linearLayout;

    public CourseListFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_course_list, container, false);

        linearLayout = view.findViewById(R.id.courseList);

        MainActivity ma = (MainActivity) getActivity();

        ArrayList<Course> courses = ma.getCourses();

        for (final Course aCourse : courses) {

            CardView cardView = new CardView(ma);

            LayoutParams layoutParams = new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            );

            cardView.setLayoutParams(layoutParams);
            cardView.setRadius(9);
            cardView.setContentPadding(15,30,15,30);
            cardView.setCardBackgroundColor(Color.parseColor("#80D8FF"));
            cardView.setMaxCardElevation(5);
            cardView.setCardElevation(2);
            cardView.setUseCompatPadding(true);

            cardView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {

                    /*CourseFragment cf = new CourseFragment();

                    FragmentManager fm = getFragmentManager();
                    FragmentTransaction ft = fm.beginTransaction();

                    ((MainActivity) getActivity()).setChosenCourse(aCourse);

                    Bundle args = new Bundle();
                    args.putString("Coursecode", aCourse.getCode());

                    //args.putString("Coursename", aCourse.getName());
                    //args.putString("Course", aCourse.getLecturer().getName());
                    //args.putString("Email", aCourse.getLecturer().getEmail());
                    //args.putString("Photo", aCourse.getLecturer().getImgString());


                    cf.setArguments(args);
                    ft.replace(R.id.fragment_container, cf);
                    ft.commit();*/

                    MainActivity ma = (MainActivity) getActivity();

                    ma.setChosenCourse(aCourse);

                    ma.showCourse();

                }
            });

            TextView textView = new TextView(getActivity());
            textView.setLayoutParams(layoutParams);
            textView.setText(aCourse.toString());
            textView.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 22);
            textView.setTextColor(Color.BLACK);

            cardView.addView(textView);

            linearLayout.addView(cardView);

        }

        return view;
    }
}
