# Test API #
---
This project aims to provide a common platform for wireless sensor networks to store and retrieve data.

Base URL: http://localhost/cloudysensor-yii2/api/index.php/

---
## Country ##

---
#### Say Hello ####
This 'say-hello' action returns a "Hello World" message. This is an extra pattern specified in configuration file "api.php". It is set without authentication and access control (customized in behavior function).

- **URL**

    v1/countries/hello

- **Method**

    GET

- **URL Params**

- **URL Header**

    Content-Type: application/json
    Accept: application/json

- **Data Params**

- **Response Success:**
    - **Code:** 200
    - **Content:** {"success":true,"message":"Username available.","data":[{"username":"zqi3"}]}


- **Response Error:**
    - **Code:** 404
    - **Content:** {"name":"Not Found","message":"Page not found.","code":0,"status":404,"type":"yii\\web\\NotFoundHttpException"}


- **Notes**


---
#### Search ####
Search record with properties. Does not support wildcast.

- **URL**

    v1/countries/search?[key=value&key=value]
    - **Sample** v1/countries/search?name=China&code=CN


- **Method**

    GET

- **URL Params**

    Search criteria in key=value pairs format.

- **URL Header**

    Content-Type: application/json
    Accept: application/json

- **Data Params**

- **Response Success:**

    - **Code:** 200
    - **Content:** List of records which match the search criteria.
    {"items":[{"id":"4","code":"CN","name":"China","population":"1277558000","userId":"5","created":null,"modified":"2015-04-27 10:11:18"}]}

- **Response Error:**

    - **Code:** 404
    - **Content:** {"name":"Not Found","message":"No entries found with this query string","code":0,"status":404,"type":"yii\\web\\HttpException"}

- **Notes**
