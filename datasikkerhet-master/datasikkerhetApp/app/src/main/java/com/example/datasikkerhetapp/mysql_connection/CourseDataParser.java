package com.example.datasikkerhetapp.mysql_connection;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;


import com.example.datasikkerhetapp.MainActivity;
import com.example.datasikkerhetapp.model.Course;
import com.example.datasikkerhetapp.model.Lecturer;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class CourseDataParser extends AsyncTask<Void,Void,Integer>{

    Context c;
    String jsonData;

    ProgressDialog pd;
    ArrayList<Course> courses =new ArrayList<>();

    public CourseDataParser(Context c, String jsonData) {
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
            ma.setCourses(courses);

            ma.showCourselist();
        }
    }

    private int parseData() {
        try {
            System.out.println("Dx 1");
            System.out.println("Dx "+jsonData);
            JSONArray ja=new JSONArray(jsonData);
            System.out.println("Dx 2");
            JSONObject jo;
            System.out.println("Dx 3");

            courses.clear();

            for(int i=0;i<ja.length();i++) {
                jo=ja.getJSONObject(i);
                System.out.println("Dx 4");

                String code=jo.getString("emnekode");
                String name=jo.getString("emnenavn");

                String lecturerName;
                String lecturerEmail;
                String lecturerPhoto;

                lecturerName = jo.getString("navn");
                lecturerEmail = jo.getString("foreleser");
                lecturerPhoto = jo.getString("bilde");

                System.out.println("Dx 5");

                Course course = new Course(code, name, new Lecturer(lecturerName, lecturerEmail, lecturerPhoto));

                courses.add(course);

            }

            System.out.println("Dx 6");

            return 1;

        }
        catch (JSONException e) {
            System.out.println("Dx oh no");
            e.printStackTrace();
        }

        System.out.println("Dx fail");

        return 0;
    }
}
