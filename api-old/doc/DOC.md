
# API DOCUMENTATION

## API End Points
As a contributor, make sure you update any added endpoint added as itemized below

* Auth
  * Login user using -  http://localhost/friconn/api/v1/auth/login/{user_type}
    * User Type
        * Learner
        * Mod
        * Expert
        * Admin
    * Login Parameter - `email` and `password`

* Utility
  * Fetch Entity User - http://localhost/friconn/api/v1/user/
    ```
        // Post Data sample (Required fields)
        {
            "tbl": "learners",
            "user": 36475,
            "field": [
                "surname",
                "email"
            ]
            
        }
        // Post Data sample (all fields)
        {
            "tbl": "learners",
            "user": 36475
        }

        */
    ```
  * Fetch Entity Users- http://localhost/friconn/api/v1/user/{entity}
    * Entity
        * Learner
        * Mod
        * Expert
        * Admin
