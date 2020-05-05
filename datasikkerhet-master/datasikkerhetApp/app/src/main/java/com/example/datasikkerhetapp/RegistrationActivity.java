package com.example.datasikkerhetapp;

import androidx.appcompat.app.AppCompatActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.example.datasikkerhetapp.mysql_connection.PostRequestHandler;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;

import static com.example.datasikkerhetapp.Links.*;

public class RegistrationActivity extends AppCompatActivity {

    private EditText name, email, fieldOfStudy, year, password;
    private Button btnRegister;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registration);

        name = findViewById(R.id.txtName);
        email = findViewById(R.id.txtEmail);
        fieldOfStudy = findViewById(R.id.txtFieldOfStudy);
        year = findViewById(R.id.numYear);
        password = findViewById(R.id.txtPassword);
        btnRegister = findViewById(R.id.btnRegister2);

        System.out.println("Heisveis");

        System.out.println("Input-verdier: " + name.toString() + ", " + email.toString() + ", " + fieldOfStudy.toString() +
                ", " + year.toString() + " og " + password.toString());

        btnRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                final String sName = name.getText().toString().trim();
                final String sEmail = email.getText().toString().trim();
                final String sFieldOfStudy = fieldOfStudy.getText().toString().trim();
                final String sYear = year.getText().toString().trim();
                final String sPassword = password.getText().toString().trim();

                System.out.println("Parametere: " + sName + ", " + sEmail + ", " + sFieldOfStudy +
                        ", " + sYear + " og " + sPassword);

                if(sName.isEmpty() || sEmail.isEmpty() || sFieldOfStudy.isEmpty() || sYear.isEmpty() || sPassword.isEmpty()){
                    Toast.makeText(RegistrationActivity.this, "Alle feltene må fylles ut", Toast.LENGTH_SHORT).show();
                }
                else if (sYear.length() != 4) {
                    Toast.makeText(RegistrationActivity.this, "Ugyldig kull", Toast.LENGTH_SHORT).show();
                }

                else {
                    class Login extends AsyncTask<Void, Void, String> {
                        ProgressDialog pdLoading = new ProgressDialog(RegistrationActivity.this);

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
                            params.put("name", sName);
                            params.put("email", sEmail);
                            params.put("fieldOfStudy", sFieldOfStudy);
                            params.put("year", sYear);
                            params.put("password", sPassword);

                            //returing the response
                            return requestHandler.sendPostRequest(REGISTER, params);
                        }

                        @Override
                        protected void onPostExecute(String s) {
                            super.onPostExecute(s);
                            pdLoading.dismiss();

                            System.out.println("Dette er JSON-strengen: " + s);

                            try {
                                //converting response to json object
                                JSONObject obj = new JSONObject(s);

                                Toast.makeText(getApplicationContext(), obj.getString("message"), Toast.LENGTH_LONG).show();

                                //if no error in response
                                if (!obj.getBoolean("error")) {
                                    Account.setActiveUser(sName, sEmail, sFieldOfStudy, sYear);
                                    finish();
                                    startActivity(new Intent(RegistrationActivity.this, MainActivity.class));
                                }
                            } catch (JSONException e) {
                                e.printStackTrace();
                                Toast.makeText(RegistrationActivity.this, "Exception: " + e.getMessage(), Toast.LENGTH_LONG).show();
                            }
                        }
                    }

                    Login login = new Login();
                    login.execute();
                }
            }
        });
    }
}
