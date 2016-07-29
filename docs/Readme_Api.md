# Cloudy Sensor API #
---
This project aims to provide a common platform for wireless sensor networks to store and retrieve data.

---
## Registration ##

---
#### Check Username Availability ####
Check whether a username is available for registration.

- **URL**

    /api/register/usernameavailable/:username

- **Method**

    GET

- **URL Params**

    username: string

- **URL Header**

    Content-Type: Application/json

- **Data Params**


- **Response Success:**
    - **Code:** 200
    - **Content:** {"success":true,"message":"Username available.","data":[{"username":"zqi3"}]}


- **Response Error:**
    - **Code:** 422
    - **Content:** {"success":false,"message":"Username is taken.","data":[{"username":"zqi2"}]}


- **Sample Call**

- **Notes**


---
#### Register User ####
Register a new user.

* **URL**

  /api/register/user

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  `Content-Type: Application/json`

* **Data Params**

  `{"username":"mop", "password":"abc123", "fullName":"MOP", "email":"mark@gmail.com", "phone":"12345678", "company":"NP MOP"}`

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"success":true,"message":"Registration Successful.","data":[{"id":"19","username":"opq"}]}`


* **Response Error:**

    * **Code:** 409 <br/>
      **Content:** `{"success":false,"message":"Username opq is already taken. Choose another username.","data":{"errorCode":"409","message":"Username opq is already taken. Choose another username."}}`


* **Sample Call**

* **Notes**

---
#### Email Verification ####
Verify a newly registered email address. Verification will only be sent if these two parameters are set to true in configuration file: 'send_verification_email' => false, 'user_verification_required' => false.

* **URL**

  /api/register/verify/:userId/:token

* **Method**

  `GET`

*  **URL Params**

    `userId`: <br/>
    `token`:

* **URL Header**

  `Content-Type: Application/json`

* **Data Params**

  `{"username":"mop", "password":"abc123", "fullName":"MOP", "email":"mark@gmail.com", "phone":"12345678", "company":"NP MOP"}`

* **Response Success**

    * **Code:** 200 <br/>
      **Content:** `{"success":true,"message":"Verification Successful.","data":[{"id":"17","username":"lmo","email":"mark@gmail.com"}]}`


* **Response Error**

    * **Code:** 400 <br/>
      **Content:** `{"success":false,"message":"Token is invalid.","data":[]}`


* **Sample Call**

* **Notes**


---
## User ##

---
#### Login ####
Login as registered user.

* **URL**

  /api/user/login

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  X-REST-USERNAME: [username] </br>
  X-REST-PASSWORD: [password] </br>
  Content-Type: Application/json </br>

* **Data Params**

  None

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"message":"Login successfully.","data":{"totalCount":1,"ticket":[{"token":"863d5187f151f243ee226110d134cb0a","expire":"2015-01-29 09:49:55"}]},"success":true}`


* **Response Error:**

    * **Code:** 401 <br/>
      **Content:** `{"success":false,"message":"Unauthorized: Invalid Username or Password.","data":[]}`


* **Sample Call:**

* **Notes:**

---
#### Change Password ####
Change current user password.

* **URL**

  /api/user/changepassword

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  X-REST-USERNAME: [username] </br>
  X-REST-PASSWORD: [old_password] </br>
  Content-Type: Application/json </br>

* **Data Params**

  {"new_password": "This is new password"}

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"message":"Password is updated","data":[],"success":true}`


* **Response Error:**

    * **Code:** 401 <br/>
      **Content:** `{"success":false,"message":"Unauthorized: Invalid Username or Password.","data":[]}`


* **Sample Call:**

* **Notes:**

---
#### Delete Current Token ####
Delete current token from server (logout)

* **URL**

  /api/user/logoutcurrenttoken

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  X-REST-TOKEN: [token] <br/>
  Content-Type: Application/json

* **Data Params**

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"message":"Deleted current token.","data":{"totalCount":1,"ticket":[{"token":"863d5187f151f243ee226110d134cb0a"}]},"success":true}`


* **Response Error:**

    * **Code:** 400 <br/>
      **Content:** `{"success":false,"message":"Token is invalid.","data":[]}`


* **Sample Call:**

* **Notes:**

---
#### Delete All Tokens ####
Delete current token from server (logout)

* **URL**

  /api/user/logoutalltoken

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  X-REST-USERNAME: [username] <br/>
  X-REST-PASSWORD: [password] <br/>
  Content-Type: Application/json

* **Data Params**

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"message":"Deleted all Tokens.","data":{"totalCount":5,"user":[{"id":"1","username":"zqi2"}]},"success":true}`


* **Response Error:**

    * **Code:** 401 <br/>
      **Content:** `{"success":false,"message":"Unauthorized: Invalid Username or Password.","data":[]}`


* **Sample Call:**

* **Notes:**



---
## NodeRaw ##

---
#### Upload File ####
Upload a file using form data.

* **URL**

  /api/nodeRaw/uploadfile

* **Method**

  `POST`

* **URL Params**

* **URL Header**

  X-REST-TOKEN: [token] <br/>
  Content-Type: Application/json

* **Data Params**

  None

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"success":true,"message":"File upload successful.","data":{"totalCount":1,"NodeRaw":[{"label":"Hello World","nodeId":"2","fileType":"image\/png","fileSize":219042,"fileName":"0002_20150121_104713_70216100.png","locationId":null,"created":"2015-01-21 10:47:13","modified":null,"id":"9"}]}}`


* **Response Error:**

    * **Code:** 401 <br/>
      **Content:** `{"success":false,"message":"Unauthorized: Invalid Username or Password.","data":[]}`


* **Sample Call:**

* **Notes:**

---
#### List All ####
List all NodeRaw items

* **URL**

  /api/nodeRaw

* **Method**

  `GET`

* **URL Params**

* **URL Header**

  X-REST-TOKEN: [token] <br/>
  Content-Type: Application/json

* **Data Params**

  None

* **Response Success:**

    * **Code:** 200 <br/>
      **Content:** `{"success":true,"message":"Record(s) Found","data":{"totalCount":0,"nodeRaw":[]}}`


* **Response Error:**

    * **Code:** 401 <br/>
      **Content:** `{"success":false,"message":"Unauthorized: Invalid Username or Password.","data":[]}`


* **Sample Call:**
    * List all whose ID in [2, 4] and created after "2015-01-20"
    <br/>
    /api/nodeRaw?filter=[{"property":"nodeId","value":[2,4],"operator":"in"},{"property":"created","value":"2015-01-20","operator":">"}]

    * List all items sorted in DESC order [GET]
    <br/>
    /api/nodeRaw?sort=[{"property":"created", "direction":"DESC"}]

* **Notes:**
