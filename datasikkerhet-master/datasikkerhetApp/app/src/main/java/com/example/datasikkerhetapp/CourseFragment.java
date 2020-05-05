package com.example.datasikkerhetapp;


import android.app.ProgressDialog;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Space;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;

import com.example.datasikkerhetapp.model.Comment;
import com.example.datasikkerhetapp.model.Course;
import com.example.datasikkerhetapp.model.Inquiry;
import com.example.datasikkerhetapp.mysql_connection.PostRequestHandler;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.InputStream;
import java.net.URL;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;

import static com.example.datasikkerhetapp.Links.*;

public class CourseFragment extends Fragment {

    private TextView tv;
    private LinearLayout llLectuter;
    private LinearLayout llComments;
    private Course thisCourse;

    private ArrayList<Inquiry> inquiries;

    public CourseFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_course, container, false);

        thisCourse = getChosenCourse();



        return view;
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        setup(view);
    }

    private void setup(View view) {
        llLectuter = view.findViewById(R.id.lecturerLayout);
        llComments = view.findViewById(R.id.commentLayout);



        if (thisCourse.getLecturer() != null) {
            View lecturerLayout = getLayoutInflater().inflate(R.layout.lecturer_view, llLectuter);
            ImageView imgView = lecturerLayout.findViewById(R.id.imgLecturer);
            TextView nameView = lecturerLayout.findViewById(R.id.lecturerName);
            TextView emailView = lecturerLayout.findViewById(R.id.lecturerEmail);
            final EditText etInquiry = lecturerLayout.findViewById(R.id.msgLecturer);
            Button btnSend = lecturerLayout.findViewById(R.id.btnSendMsg);

            nameView.setText(thisCourse.getLecturer().getName());
            emailView.setText(thisCourse.getLecturer().getEmail());

            loadImageFromServerAndSet(imgView);

            btnSend.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    final String sSenderEmail = Account.getActiveUser().getEmail();
                    final String sRecieverEmail = thisCourse.getLecturer().getEmail();
                    final String sCoursecode = thisCourse.getCode();
                    final String sMessage = etInquiry.getText().toString();

                    if(sMessage.isEmpty()){
                        Toast.makeText(getActivity(), "Du har ikke skrevet en melding", Toast.LENGTH_SHORT).show();
                    }
                    else {
                        class SendInquiry extends AsyncTask<Void, Void, String> {
                            ProgressDialog pdLoading = new ProgressDialog(getActivity());

                            @Override
                            protected void onPreExecute() {
                                super.onPreExecute();

                                pdLoading.setMessage("\tLoading...");
                                pdLoading.setCancelable(false);
                                pdLoading.show();
                            }

                            @Override
                            protected String doInBackground(Void... voids) {
                                //creating request handler object
                                PostRequestHandler requestHandler = new PostRequestHandler();

                                //creating request parameters
                                HashMap<String, String> params = new HashMap<>();
                                params.put("user", sSenderEmail);
                                //params.put("emailTo", sRecieverEmail);
                                //params.put("coursecode", sCoursecode);
                                params.put("inquiry", sMessage);
                                params.put("sendInquiry", "");

                                System.out.println("Emne URL: " + COURSE + thisCourse.getCode());

                                //returing the response
                                return requestHandler.sendPostRequest(COURSE + thisCourse.getCode(), params);
                            }

                            @Override
                            protected void onPostExecute(String s) {
                                super.onPostExecute(s);
                                pdLoading.dismiss();

                                try {
                                    //converting response to json object
                                    JSONObject obj = new JSONObject(s);

                                    // Show respond message
                                    Toast.makeText(getActivity().getApplicationContext(), obj.getString("message"), Toast.LENGTH_LONG).show();

                                    //if no error in response
                                    if (!obj.getBoolean("error")) {
                                        ((MainActivity)getActivity()).showCourse();
                                    }
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            }
                        }

                        SendInquiry sendInquiry = new SendInquiry();
                        sendInquiry.execute();
                    }
                }
            });
        }

        String txtCourse = thisCourse.getCode() + " " + thisCourse.getName();

        tv = view.findViewById(R.id.txtCourseName);
        tv.setText(txtCourse);

        showComments();
    }

    private void loadImageFromServerAndSet(final ImageView imgView) {

        class LoadImage extends AsyncTask<Void, Void, Drawable> {
            ProgressDialog pdLoading = new ProgressDialog(getActivity());

            @Override
            protected void onPreExecute() {
                super.onPreExecute();

                pdLoading.setMessage("\tLoading...");
                pdLoading.setCancelable(false);
                pdLoading.show();
            }

            @Override
            protected Drawable doInBackground(Void... voids) {

                Drawable drawable = null;
                try {
                    InputStream in = (InputStream) new URL(URL_IMG + thisCourse.getLecturer().getImgString()).getContent();
                    drawable = Drawable.createFromStream(in, "src");
                } catch (Exception e) {
                    //Log.e("Error", e.getMessage());
                    e.printStackTrace();
                }
                return drawable;
            }

            @Override
            protected void onPostExecute(Drawable d) {
                super.onPostExecute(d);
                pdLoading.dismiss();

                imgView.setImageDrawable(d);

            }
        }


        LoadImage loadImage = new LoadImage();
        loadImage.execute();
    }

    public void showComments() {
        final MainActivity ma = (MainActivity) getActivity();
        inquiries = ma.getCourseInquiries();

        Collections.sort(inquiries);

        LayoutInflater inflater = LayoutInflater.from(getContext());

        for (final Inquiry inquiry : inquiries) {

            View inquiryView = inflater.inflate(R.layout.inquiry_view, null);

            TextView inquiryBy = inquiryView.findViewById(R.id.txtSentBy);
            Button btnReport = inquiryView.findViewById(R.id.btnReport);
            TextView message = inquiryView.findViewById(R.id.txtInquiry);
            final EditText answer = inquiryView.findViewById(R.id.txtAnswer);
            Button btnSend = inquiryView.findViewById(R.id.btnSendAnswer);
            CardView lecturerResponse = inquiryView.findViewById(R.id.lecturerResponse);
            TextView lecturerMessage = inquiryView.findViewById(R.id.lecturerMessage);
            LinearLayout otherComments = inquiryView.findViewById(R.id.otherResponses);

            if (inquiry.isUser()) {
                inquiryBy.setText("Din kommentar:");
                btnReport.setVisibility(View.GONE);
            }
            else {
                btnReport.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        final String sEmail = Account.getActiveUser().getEmail();
                        final String sInquiryID = Integer.toString(inquiry.getId());

                        System.out.println("Inquiry ID: " + sInquiryID);

                        class ReportInquiry extends AsyncTask<Void, Void, String> {
                            ProgressDialog pdLoading = new ProgressDialog(getActivity());

                            @Override
                            protected void onPreExecute() {
                                super.onPreExecute();

                                //this method will be running on UI thread
                                pdLoading.setMessage("\tLoading...");
                                pdLoading.setCancelable(false);
                                pdLoading.show();
                            }

                            @Override
                            protected String doInBackground(Void... voids) {
                                //creating request handler object
                                PostRequestHandler requestHandler = new PostRequestHandler();

                                //creating request parameters
                                HashMap<String, String> params = new HashMap<>();
                                params.put("id", sInquiryID);
                                params.put("user", sEmail);
                                params.put("reportInquiry", "");

                                //returing the response
                                return requestHandler.sendPostRequest(COURSE + thisCourse.getCode(), params);
                            }

                            @Override
                            protected void onPostExecute(String s) {
                                super.onPostExecute(s);

                                pdLoading.dismiss();

                                try {
                                    JSONObject obj = new JSONObject(s);

                                    // Show response message
                                    Toast.makeText(getActivity().getApplicationContext(), obj.getString("message"), Toast.LENGTH_LONG).show();
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }
                            }
                        }

                        ReportInquiry reportInquiry = new ReportInquiry();
                        reportInquiry.execute();
                    }
                });
            }
            message.setText(inquiry.getInquiry());
            if (inquiry.getResponse() != null) {
                lecturerMessage.setText(inquiry.getResponse());
            }
            else {
                lecturerResponse.setVisibility(View.GONE);
            }

            btnSend.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    final String sSenderEmail = Account.getActiveUser().getEmail();
                    final String sIntParentCommentID = Integer.toString(inquiry.getId());
                    final String sComment = answer.getText().toString();

                    if(sComment.isEmpty()){
                        Toast.makeText(getActivity(), "Du må skrive en melding", Toast.LENGTH_SHORT).show();
                    }

                    else {
                        class SendComment extends AsyncTask<Void, Void, String> {
                            ProgressDialog pdLoading = new ProgressDialog(getActivity());

                            @Override
                            protected void onPreExecute() {
                                super.onPreExecute();

                                //this method will be running on UI thread
                                pdLoading.setMessage("\tLoading...");
                                pdLoading.setCancelable(false);
                                pdLoading.show();
                            }

                            @Override
                            protected String doInBackground(Void... voids) {
                                //creating request handler object
                                PostRequestHandler requestHandler = new PostRequestHandler();

                                //creating request parameters
                                HashMap<String, String> params = new HashMap<>();
                                params.put("user", sSenderEmail);
                                params.put("id", sIntParentCommentID);
                                params.put("comment", sComment);
                                params.put("sendComment", "");

                                //returing the response
                                return requestHandler.sendPostRequest(COURSE + thisCourse.getCode(), params);
                            }

                            @Override
                            protected void onPostExecute(String s) {
                                super.onPostExecute(s);
                                pdLoading.dismiss();

                                System.out.println("JSON-string: " + s);

                                try {
                                    //converting response to json object
                                    JSONObject obj = new JSONObject(s);

                                    // Show response message
                                    Toast.makeText(getActivity().getApplicationContext(), obj.getString("message"), Toast.LENGTH_LONG).show();

                                    //if no error in response
                                    if (!obj.getBoolean("error")) {
                                        ma.showCourse();
                                    }
                                } catch (JSONException e) {
                                    e.printStackTrace();
                                    System.out.println("Exception: " + e);
                                }
                            }
                        }

                        SendComment sendComment = new SendComment();
                        sendComment.execute();
                    }
                }
            });

            Collections.sort(inquiry.getComments());

            for (final Comment comment : inquiry.getComments()) {
                View commentView = inflater.inflate(R.layout.comment_view, null);

                TextView commentBy = commentView.findViewById(R.id.commentSentBy);
                Button btnReportComment = commentView.findViewById(R.id.btnReport2);
                TextView commentMessage = commentView.findViewById(R.id.commentMessage);

                if (comment.isUser()) {
                    commentBy.setText("Din kommentar:");
                    btnReportComment.setVisibility(View.GONE);
                }
                else {
                    btnReportComment.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View v) {
                            final String sInquiryID = Integer.toString(comment.getId());
                            final String sEmail = Account.getActiveUser().getEmail();

                            class ReportComment extends AsyncTask<Void, Void, String> {
                                ProgressDialog pdLoading = new ProgressDialog(getActivity());

                                @Override
                                protected void onPreExecute() {
                                    super.onPreExecute();

                                    pdLoading.setMessage("\tLoading...");
                                    pdLoading.setCancelable(false);
                                    pdLoading.show();
                                }

                                @Override
                                protected String doInBackground(Void... voids) {
                                    //creating request handler object
                                    PostRequestHandler requestHandler = new PostRequestHandler();

                                    //creating request parameters
                                    HashMap<String, String> params = new HashMap<>();
                                    params.put("id", sInquiryID);
                                    params.put("user", sEmail);
                                    params.put("reportComment", "");

                                    //returing the response
                                    return requestHandler.sendPostRequest(COURSE + thisCourse.getCode(), params);
                                }

                                @Override
                                protected void onPostExecute(String s) {
                                    super.onPostExecute(s);
                                    pdLoading.dismiss();

                                    try {
                                        JSONObject obj = new JSONObject(s);

                                        // Show response message
                                        Toast.makeText(getActivity().getApplicationContext(), obj.getString("message"), Toast.LENGTH_LONG).show();

                                    } catch (JSONException e) {
                                        e.printStackTrace();
                                        Toast.makeText(getActivity(), "Exception: " + e, Toast.LENGTH_LONG).show();
                                        System.out.println("Exception: " + e);
                                    }
                                }
                            }

                            ReportComment reportComment = new ReportComment();
                            reportComment.execute();
                        }
                    });
                }
                commentMessage.setText(comment.getComment());

                otherComments.addView(commentView);

                Space space = new Space(getContext());
                space.setMinimumHeight(40);

                otherComments.addView(space);
            }

            llComments.addView(inquiryView);

            Space space = new Space(getContext());
            space.setMinimumHeight(60);

            llComments.addView(space);

        }
        System.out.println("Hei...");
    }

    private Course getChosenCourse() {
        //String code = getArguments().getString("Coursecode");
        MainActivity ma = (MainActivity) getActivity();
        return ma.getChosenCourse();
        /*
        ArrayList<Course> courses = ma.getCourses();
        for (Course course : courses) {
            if (course.getCode().equals(code)) {
                return course;
            }
        }

         */
        //return null;
    }
}
