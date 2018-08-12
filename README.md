# socialnetworking-laravel-cassandra
Simple social networking system using Laravel Cassanda RabbitMQ and Redis


#### Specification:

* Laravel Framework 5.6.12
* PHP 7.2.3
* Cassandra 3.11.3
* Redis 5.0-rc4
* RabbitMQ 3.7.7
* Docker 18.06.0


#### Installation:
```
cd socialnetworking-laravel-cassandra/
docker-compose up -d
```

#### API Documentation:

(Swagger version coming soon!)

##### Note
* Every email address must be registered in advance. 
* Make friend will also automatically subscribe to an update.

<details>
<summary><b>Register User<b></summary>
<p>

* **URL**

  POST /api/user
*  **Body (raw)**

    ```
    {
        "email": "smile@example.com"
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true    
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/user" -H "accept: application/json" -d '{"email": "smile@example.com"}'
  ```
  * Response:
  ```
    { 
        "success": true    
    }
  ```
</p>
</details>

<details>
<summary><b>Make Friend<b></summary>
<p>

* **URL**

  POST /api/friend
*  **Body (raw)**

    ```
    {
      "friends":
        [
          "smile@example.com",
          "laugh@example.com"
        ]
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true    
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/friend" -H "accept: application/json" -d '{
                                                                                         "friends":
                                                                                           [
                                                                                             "smile@example.com",
                                                                                             "laugh@example.com"
                                                                                           ]
                                                                                       }'
  ```
  * Response:
  ```
    { 
        "success": true    
    }
  ```
</p>
</details>

<details>
<summary><b>Friend List<b></summary>
<p>

* **URL**

  POST /api/friend/list
*  **Body (raw)**

    ```
    {
        "email": "smile@example.com"
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true,
        "friends": [
            "laugh@example.com"
        ],
        "count": 1
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/friend/list" -H "accept: application/json" -d '{"email": "smile@example.com"}'
  ```
  * Response:
  ```
    { 
        "success": true,
        "friends": [
            "laugh@example.com"
        ],
        "count": 1
    }
  ```
</p>
</details>

<details>
<summary><b>Common Friends<b></summary>
<p>

* **URL**

  POST /api/friend/common
*  **Body (raw)**

    ```
    {
        "friends": [
            "smila@example.com",
            "random@roar.com"
        ]
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true,
        "friends": [
            "laugh@example.com"
        ],
        "count": 1
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/friend/common" -H "accept: application/json" -d '{
                                                                                                "friends":
                                                                                                  [
                                                                                                    "smila@example.com",
                                                                                                    "random@roar.com"
                                                                                                  ]
                                                                                              }'
  ```
  * Response:
  ```
    { 
        "success": true,
        "friends": [
            "laugh@example.com"
        ],
        "count": 1
    }
  ```
</p>
</details>

<details>
<summary><b>Subscribe<b></summary>
<p>

* **URL**

  POST /api/subscribe
*  **Body (raw)**

    ```
    {
      "requestor": "smile@example.com",
      "target": "chanel@bb.com"
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true,
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/subscribe" -H "accept: application/json" -d '{
                                                                                            "requestor": "smile@example.com",
                                                                                            "target": "chanel@bb.com"
                                                                                          }'
  ```
  * Response:
  ```
    { 
        "success": true,
    }
  ```
</p>
</details>

<details>
<summary><b>Block<b></summary>
<p>

* **URL**

  POST /api/block
*  **Body (raw)**

    ```
    {
      "requestor": "smile@example.com",
      "target": "badtzmaru@nobody.com"
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    { 
        "success": true,
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/block" -H "accept: application/json" -d '{
                                                                                        "requestor": "smile@example.com",
                                                                                        "target": "badtzmaru@nobody.com"
                                                                                      }'
  ```
  * Response:
  ```
    { 
        "success": true,
    }
  ```
</p>
</details>

<details>
<summary><b>Feed<b></summary>
<p>

To retrieve the list of email addresses that will receive the feed.

Note: the recipient email address must be a registered email address.

* **URL**

  POST /api/feed
*  **Body (raw)**

    ```
    {
      "requestor": "smile@example.com",
      "text": "Hi all! unknown@somewhere.com"
    }
    ```
* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```
    {
        "success": true,
        "recipients": [
            "laugh@example.com",
            "unknown@somewhere.com",
        ]
    }
    ```
* **Error Response:**

  * **Code:** 400 / 500<br />
    **Content:** 
    ```
    { 
        "success": false, 
        "error" : ... 
    }
    ```

* **Sample Call:**

   * Request:

  ```
    curl -X POST "http://localhost:12001/api/feed" -H "accept: application/json" -d '{
                                                                                        "requestor": "smile@example.com",
                                                                                        "text": "Hi all! unknown@somewhere.com"
                                                                                      }'
  ```
  * Response:
  ```
    {
        "success": true,
        "recipients": [
            "laugh@example.com",
            "unknown@somewhere.com",
        ]
    }
  ```
</p>
</details>
