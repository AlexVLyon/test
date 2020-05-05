package com.example.datasikkerhetapp.mysql_connection;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.security.GeneralSecurityException;
import java.security.Provider;
import java.security.SecureRandom;
import java.security.cert.X509Certificate;
import java.util.HashMap;
import java.util.Map;

import javax.net.ssl.HostnameVerifier;
import javax.net.ssl.HttpsURLConnection;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSession;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

public class PostRequestHandler {

    static {
        // this part is needed cause Lebocoin has invalid SSL certificate, that cannot be normally processed by Java
        TrustManager[] trustAllCertificates = new TrustManager[]{
                new X509TrustManager() {
                    @Override
                    public X509Certificate[] getAcceptedIssuers() {
                        return null; // Not relevant.
                    }

                    @Override
                    public void checkClientTrusted(X509Certificate[] certs, String authType) {
                        // Do nothing. Just allow them all.
                    }

                    @Override
                    public void checkServerTrusted(X509Certificate[] certs, String authType) {
                        // Do nothing. Just allow them all.
                    }
                }
        };

        HostnameVerifier trustAllHostnames = new HostnameVerifier() {
            @Override
            public boolean verify(String hostname, SSLSession session) {
                return true; // Just allow them all.
            }
        };

        try {
            System.setProperty("jsse.enableSNIExtension", "false");
            SSLContext sc = SSLContext.getInstance("SSL");
            sc.init(null, trustAllCertificates, new SecureRandom());
            HttpsURLConnection.setDefaultSSLSocketFactory(sc.getSocketFactory());
            HttpsURLConnection.setDefaultHostnameVerifier(trustAllHostnames);
        } catch (GeneralSecurityException e) {
            throw new ExceptionInInitializerError(e);
        }
    }

    public String sendPostRequest(String requestURL, HashMap<String, String> postDataParams) {
        URL url;

        System.out.println("Request URL: " + requestURL);

        StringBuilder sb = new StringBuilder();
        try {
            url = new URL(requestURL);

            System.out.println("xD: 1");

            HttpsURLConnection conn = (HttpsURLConnection) url.openConnection();
            System.out.println("xD: 2");
            conn.setRequestMethod("POST");
            System.out.println("xD: 3");
            conn.setRequestProperty("User-Agent", "Mozilla/5.0");
            System.out.println("xD: 4");
            conn.setRequestProperty("Content-Type","application/x-www-form-urlencoded");
            System.out.println("xD: 5");

            //String initialCookies = conn.getHeaderField("Set-Cookie");
            //System.out.println("xD: 6 Cookie: " + initialCookies);

            conn.setRequestProperty("Cookie", "");
            System.out.println("xD: 7");

            String param = getPostDataString(postDataParams);
            System.out.println("xD: 8");

            conn.setDoOutput(true);
            conn.setDoInput(true);

            System.out.println("xD: 9");

            OutputStream os = conn.getOutputStream();

            System.out.println("xD 10");

            BufferedWriter writer = new BufferedWriter(
                    new OutputStreamWriter(os, "UTF-8"));

            System.out.println("xD 11");

            writer.write(getPostDataString(postDataParams));

            System.out.println("xD 12");

            writer.flush();

            System.out.println("xD 13");

            writer.close();

            System.out.println("xD 14");

            os.close();

            //DataOutputStream wr = new DataOutputStream(conn.getOutputStream());
            System.out.println("xD: 15");
            //wr.writeBytes(param);
            //System.out.println("xD: 11");
            //wr.flush();
            //System.out.println("xD: 12");
            //wr.close();
            //System.out.println("xD: 13");

            System.out.println("xD Post req: " + param);

            int responseCode = conn.getResponseCode();

            System.out.println("xD Response code: " + responseCode);

            if (responseCode == HttpsURLConnection.HTTP_OK) {

                BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                sb = new StringBuilder();
                String response;

                while ((response = br.readLine()) != null) {
                    sb.append(response);
                }
            }

        }
        catch (Exception e) {
            System.out.println("The problem: " + e.getMessage());
            e.printStackTrace();
        }

        System.out.println("The response: " + sb.toString());

        return sb.toString();
    }


    //this method is converting keyvalue pairs data into a query string as needed to send to the server
    private String getPostDataString(HashMap<String, String> params) throws UnsupportedEncodingException {
        StringBuilder result = new StringBuilder();
        boolean first = true;
        for (Map.Entry<String, String> entry : params.entrySet()) {
            if (first) {
                first = false;
            }
            else {
                result.append("&");
            }

            result.append(URLEncoder.encode(entry.getKey(), "UTF-8"));
            result.append("=");
            result.append(URLEncoder.encode(entry.getValue(), "UTF-8"));
        }

        System.out.println("ParamString: " + result.toString());

        return result.toString();
    }
}
