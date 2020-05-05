package com.example.datasikkerhetapp.mysql_connection;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;

import com.example.datasikkerhetapp.Account;
import com.example.datasikkerhetapp.CourseFragment;
import com.example.datasikkerhetapp.MainActivity;
import com.example.datasikkerhetapp.R;
import com.example.datasikkerhetapp.model.Comment;
import com.example.datasikkerhetapp.model.Inquiry;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class CommentDataParser extends AsyncTask<Void, Void, Integer> {

    Context c;
    String jsonData;

    ProgressDialog pd;
    ArrayList<Inquiry> inquiries = new ArrayList<>();

    public CommentDataParser(Context c, String jsonData) {
        this.c = c;
        this.jsonData = jsonData;
    }

    @Override
    protected void onPreExecute() {
        super.onPreExecute();

        pd=new ProgressDialog(c);
        pd.setTitle("Parse");
        pd.setMessage("Parsing...Please wait");
        pd.show();
    }

    @Override
    protected Integer doInBackground(Void... params) {

        System.out.println("hello");
        System.out.println("JSON DATA" + jsonData);

        return this.parseData();
    }

    @Override
    protected void onPostExecute(Integer result) {
        super.onPostExecute(result);

        pd.dismiss();
        if(result==0)
        {
            Toast.makeText(c,"Unable to parse",Toast.LENGTH_SHORT).show();
        }else {
            MainActivity ma = (MainActivity) c;
            ma.setCourseInquiries(inquiries);
            ma.getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new CourseFragment()).commit();
        }
    }

    private int parseData() {
        System.out.println("jsonData: " + jsonData);

        try {
            //converting response to json object
            //JSONArray ja = new JSONArray(jsonData);
            JSONObject data = new JSONObject(jsonData);
            JSONArray jaInquiries = data.getJSONArray("inquiries");

            JSONObject joInquiry;

            for (int i = 0; i < jaInquiries.length(); i++) {
                joInquiry = jaInquiries.getJSONObject(i);

                int inquiryID = joInquiry.getInt("id");
                String sender;

                if (joInquiry.getString("avsender_student").equals("null")) {
                    sender = joInquiry.getString("avsender_student");
                }
                else {
                    sender = joInquiry.getString("avsender_gjest");
                }
                String message = joInquiry.getString("henvendelse");
                String response = joInquiry.getString("svar");

                ArrayList<Comment> comments = new ArrayList<>();

                if (joInquiry.has("comments")) {
                    JSONArray jaComments = joInquiry.getJSONArray("comments");

                    System.out.println("NESTED: " + comments.toString());

                    JSONObject joComment;
                    for (int j = 0; j < jaComments.length(); j++) {
                        joComment = jaComments.getJSONObject(j);

                        System.out.println("JSON OBJECT: " + joComment.toString());

                        int commentID = joComment.getInt("id");
                        String commenter;
                        if (joComment.getString("avsender_student").equals("null")) {
                            commenter = joComment.getString("avsender_student");
                        }
                        else {
                            commenter = joComment.getString("avsender_gjest");
                        }
                        String comment = joComment.getString("kommentar");

                        comments.add(new Comment(commentID, comment, isUser(commenter)));
                    }
                }

                Inquiry inquiry;
                if (response.equals("null")) {
                    inquiry = new Inquiry(inquiryID, isUser(sender), message, comments);
                }
                else {
                    inquiry = new Inquiry(inquiryID, isUser(sender), message, comments, response);
                }
                inquiries.add(inquiry);
            }

            return 1;

        } catch (JSONException e) {
            e.printStackTrace();
            //Toast.makeText(c, "Exception: " + e, Toast.LENGTH_LONG).show();
            System.out.println("Exception: " + e);
        }

        return 0;
    }

    private boolean isUser(String email) {
        return email.equals(Account.getActiveUser().getEmail());
    }

}
