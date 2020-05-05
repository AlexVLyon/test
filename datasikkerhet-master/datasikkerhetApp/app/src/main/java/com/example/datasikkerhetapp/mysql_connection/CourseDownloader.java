package com.example.datasikkerhetapp.mysql_connection;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;

public class CourseDownloader extends AsyncTask<Void,Void,String> {

    Context c;
    String urlAddress;

    ProgressDialog pd;

    public CourseDownloader(Context c, String urlAddress) {
        this.c = c;
        this.urlAddress = urlAddress;
    }

    @Override
    protected void onPreExecute() {
        super.onPreExecute();

        pd=new ProgressDialog(c);
        pd.setTitle("Fetch");
        pd.setMessage("Fetching....Please wait");
        pd.show();
    }

    @Override
    protected String doInBackground(Void... params) {
        System.out.println(this.downloadData());
        return this.downloadData();
    }

    @Override
    protected void onPostExecute(String s) {
        super.onPostExecute(s);

        pd.dismiss();

        if(s==null) {
            Toast.makeText(c,"Unsuccessfull,Null returned",Toast.LENGTH_SHORT).show();
        }
        else {
            //CALL DATA PARSER TO PARSE
            CourseDataParser parser=new CourseDataParser(c,s);
            parser.execute();

        }
    }

    private String downloadData() {
        HttpURLConnection con=Connector.connect(urlAddress);
        if(con==null) {
            return null;
        }

        InputStream is=null;
        try {
            is=new BufferedInputStream(con.getInputStream());
            BufferedReader br=new BufferedReader(new InputStreamReader(is));

            String line;
            StringBuffer response=new StringBuffer();

            while ((line=br.readLine()) != null) {
                response.append(line+"\n");
            }

            br.close();

            return response.toString();

        }
        catch (IOException e) {
            e.printStackTrace();
        }
        finally {
            if(is != null) {
                try {
                    is.close();
                }
                catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }

        return null;
    }
}
