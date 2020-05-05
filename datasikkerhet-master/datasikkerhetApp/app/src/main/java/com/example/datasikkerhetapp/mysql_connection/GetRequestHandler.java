package com.example.datasikkerhetapp.mysql_connection;

import android.app.ProgressDialog;
import android.content.Context;
import android.widget.Toast;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class GetRequestHandler {

    public String downloadData(String urlAddress) {
        try {

            System.out.println("huehue " + urlAddress);

            URL url=new URL(urlAddress);
            HttpURLConnection con= (HttpURLConnection) url.openConnection();

            System.out.println("still there?");

            //SET PROPS
            con.setRequestMethod("GET");
            con.setConnectTimeout(20000);
            con.setReadTimeout(20000);
            con.setDoInput(true);

            InputStream is = null;
            try {
                System.out.println("pleasework");
                is = new BufferedInputStream(con.getInputStream());
                BufferedReader br = new BufferedReader(new InputStreamReader(is));

                String line;
                StringBuffer response = new StringBuffer();

                while ((line = br.readLine()) != null) {
                    response.append(line + "\n");
                }

                br.close();

                System.out.println("Response-stringen: " + response.toString());

                return response.toString();

            } catch (IOException e) {
                e.printStackTrace();
            } finally {
                if (is != null) {
                    try {
                        is.close();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }
        }
        catch (MalformedURLException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return null;
    }
}
