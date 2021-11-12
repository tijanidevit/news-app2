<!-- # Friconn-v1.0-API
An API for FRICONN version 1.0.

# Documentation 
[Check out the documentation here](https://github.com/FRICONN/Friconn-v1.0-API/blob/master/doc/DOC.md)
 -->

 # Friconn-v2.0-API
An API for FRICONN version 1.0.

# Documentation 
## All endpoint precede with http://localhost/friconn-api/v2/controller 


# Learners' Endpoints

## Registration

-  http://localhost/friconn-api/v2/learners/register :POST
	
	Requires {email, last_name, other_names, password}
	
	Returns

		{
		    "status": "success",
		    "message": "One more step to go, please verify your email address.",
		    "status_code": 201,
		    "data": {
		        "id": "17",
		        "friconn_id": "22779222",
		        "role_id": "3",
		        "last_name": "Ayoade",
		        "other_names": "Lawal",
		        "email": "thenewxpat234@gmail.com1",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "310194",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-18 17:27:51",
		        "updated_at": "2021-03-18 17:27:51",
		        "institution_id": "0",
		        "department_id": "0",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "institution": 0,
		        "department": 0
		    }
		}

## Login

-  http://localhost/friconn-api/v2/learners/login :POST
	
	Requires {email, password}
	
	Returns

		{
		    "status": "success",
		    "message": "Login successful.",
		    "status_code": 201,
		    "data": {
		        "id": "17",
		        "friconn_id": "22779222",
		        "role_id": "3",
		        "last_name": "Ayoade",
		        "other_names": "Lawal",
		        "email": "thenewxpat234@gmail.com1",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "310194",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-18 17:27:51",
		        "updated_at": "2021-03-18 17:27:51",
		        "institution_id": "0",
		        "department_id": "0",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "institution": 0,
		        "department": 0
		    }
		}

## View all learners' Profile

-  http://localhost/friconn-api/v2/learners/profile :GET
	
	Requires {}
	
	Returns

		{
		    "status": "success",
		    "message": "Learners fetched successfully.",
		    "status_code": 201,
		    "data": [
		        {
		            "friconn_id": "2657367",
		            "role_id": "3",
		            "last_name": "Obadimu",
		            "other_names": "Ismail Idowu",
		            "email": "t@sw.com",
		            "profile_image": "image",
		            "created_at": "2021-03-02 23:52:58",
		            "profile": {
		                "id": "4",
		                "friconn_id": "2657367",
		                "institution_id": "0",
		                "department_id": "0",
		                "dob": "0000-00-00",
		                "phone": "",
		                "points": "0",
		                "created_at": "2021-03-02 23:52:58",
		                "updated_at": "2021-03-02 23:52:58",
		                "institution": 0,
		                "department": 0
		            }
		        },
		        {
		            "friconn_id": "6149188",
		            "role_id": "3",
		            "last_name": "Obadimu",
		            "other_names": "Ismail Idowu",
		            "email": "t@skw.com",
		            "profile_image": "image",
		            "created_at": "2021-03-02 23:58:56",
		            "profile": {
		                "id": "5",
		                "friconn_id": "6149188",
		                "institution_id": "0",
		                "department_id": "0",
		                "dob": "0000-00-00",
		                "phone": "",
		                "points": "0",
		                "created_at": "2021-03-02 23:58:57",
		                "updated_at": "2021-03-02 23:58:57",
		                "institution": 0,
		                "department": 0
		            }
		        },
		        {
		            "friconn_id": "6783699",
		            "role_id": "3",
		            "last_name": "Obadimu",
		            "other_names": "Ismail Idowu",
		            "email": "t@eskw.com",
		            "profile_image": "",
		            "created_at": "2021-03-03 00:03:21",
		            "profile": {
		                "id": "6",
		                "friconn_id": "6783699",
		                "institution_id": "0",
		                "department_id": "0",
		                "dob": "0000-00-00",
		                "phone": "",
		                "points": "0",
		                "created_at": "2021-03-03 00:03:23",
		                "updated_at": "2021-03-03 00:03:23",
		                "institution": 0,
		                "department": 0
		            }
		        }
		    ]
		}

## View a learner's Profile

-  http://localhost/friconn-api/v2/learners/profile/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner fetched successfully.",
		    "status_code": 201,
		    "data": {
		        "id": "17",
		        "friconn_id": "22779222",
		        "role_id": "3",
		        "last_name": "Ayoade",
		        "other_names": "Lawal",
		        "email": "thenewxpat234@gmail.com1",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "310194",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-18 17:27:51",
		        "updated_at": "2021-03-18 17:27:51",
		        "institution_id": "0",
		        "department_id": "0",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "institution": 0,
		        "department": 0
		    }
		}

## Update a learner's Profile

-  http://localhost/friconn-api/v2/learners/profile :POST
	
	Requires {friconn_id, dob, phone, institution_id, department_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner fetched successfully.",
		    "status_code": 201,
		    "data": {
		        "id": "17",
		        "friconn_id": "22779222",
		        "role_id": "3",
		        "last_name": "Ayoade",
		        "other_names": "Lawal",
		        "email": "thenewxpat234@gmail.com1",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "310194",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-18 17:27:51",
		        "updated_at": "2021-03-18 17:27:51",
		        "institution_id": "0",
		        "department_id": "0",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "institution": 0,
		        "department": 0
		    }
		}

## Update a learner's Point

-  http://localhost/friconn-api/v2/learners/points :POST
	
	Requires {friconn_id, points}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner points updated successfully.",
		    "status_code": 200,
		    "data": "300"
		}

## View a learner's Point

-  http://localhost/friconn-api/v2/learners/points/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner points fetched successfully.",
		    "status_code": 200,
		    "data": "300"
		}

## Add a new learner's Course

-  http://localhost/friconn-api/v2/learners/courses :POST
	
	Requires {friconn_id,course_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner course added successfully.",
		    "status_code": 201,
		    "data": {
		        "id": "7",
		        "friconn_id": "22779222",
		        "course_id": "5",
		        "created_at": "2021-03-18 17:55:22",
		        "updated_at": "2021-03-18 17:55:22"
		    }
		}

## View a learner's Courses

-  http://localhost/friconn-api/v2/learners/courses/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner courses fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "7",
		            "friconn_id": "22779222",
		            "course_id": "5",
		            "created_at": "2021-03-18 17:55:22",
		            "updated_at": "2021-03-18 17:55:22",
		            "learner": "Ayoade Lawal",
		            "course": "Introduction to computer"
		        },
		        {
		            "id": "6",
		            "friconn_id": "22779222",
		            "course_id": "4",
		            "created_at": "2021-03-18 17:52:13",
		            "updated_at": "2021-03-18 17:52:13",
		            "learner": "Ayoade Lawal",
		            "course": "Introduction to Food Technology"
		        }
		    ]
		}

## View a learner's questions

-  http://localhost/friconn-api/v2/learners/questions/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner questions fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "2",
		            "friconn_id": "22779222",
		            "course_id": "2",
		            "subject": "Boring lectures",
		            "question": "This lecture is too boring. How can I help myself",
		            "answered": "0",
		            "points_charged": "0",
		            "active_status": "1",
		            "created_at": "2021-03-13 07:38:08",
		            "updated_at": "2021-03-18 18:09:17",
		            "learner": "Ayoade Lawal",
	            	"course": "Introduction to computer"
		        }
		    ]
		}

## View a learner's answers

-  http://localhost/friconn-api/v2/learners/answers/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Learner answers fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "3",
		            "question_id": "1",
		            "friconn_id": "22779222",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:22:44",
		            "updated_at": "2021-03-18 18:23:11",
		            "learner": "Ayoade Lawal",
		            "question": "FST101"
		        }
		    ]
		}



# eduministers' Endpoints

## Registration

-  http://localhost/friconn-api/v2/eduministers/register :POST
	
	Requires {email, last_name, other_names, password}
	
	Returns

		{
		    "status": "success",
		    "message": "One more step to go, please verify your email address.",
		    "status_code": 201,
		    "data": {
		        "id": "17",
		        "friconn_id": "22779222",
		        "role_id": "3",
		        "last_name": "Ayoade",
		        "other_names": "Lawal",
		        "email": "thenewxpat234@gmail.com1",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "310194",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-18 17:27:51",
		        "updated_at": "2021-03-18 17:27:51",
		        "institution_id": "0",
		        "department_id": "0",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "institution": 0,
		        "department": 0
		    }
		}

## Login

-  http://localhost/friconn-api/v2/eduministers/login :POST
	
	Requires {email, password}
	
	Returns

		{
		    "status": "success",
		    "message": "Login successful.",
		    "status_code": 201,
		    "data": {
		        "id": "1",
		        "friconn_id": "69494519",
		        "role_id": "4",
		        "last_name": "AyoadeY",
		        "other_names": "Lawal",
		        "email": "thenewxpat@gmail.com",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "820800",
		        "profile_image": "",
		        "approved": "0",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-03 11:06:15",
		        "updated_at": "2021-03-03 11:06:15",
		        "employment_status_id": "0",
		        "state_id": "0",
		        "gender_id": "0",
		        "linked_in_url": "",
		        "dob": "0000-00-00",
		        "phone": "",
		        "points": "0",
		        "balance": "0",
		        "bio": "",
		        "employment_status": 0,
		        "gender": 0,
		        "state": 0
		    }
		}

## View all eduministers' Profile

-  http://localhost/friconn-api/v2/eduministers/profile :GET
	
	Requires {}
	
	Returns

		{
		    "status": "success",
		    "message": "eduministers fetched successfully.",
		    "status_code": 201,
		    "data": {
		        {
		            "friconn_id": "69494519",
		            "role_id": "4",
		            "last_name": "AyoadeY",
		            "other_names": "Lawal",
		            "email": "thenewxpat@gmail.com",
		            "profile_image": "",
		            "created_at": "2021-03-03 11:06:15",
		            "profile": {
		                "id": "1",
		                "friconn_id": "69494519",
		                "employment_status_id": "0",
		                "state_id": "0",
		                "gender_id": "0",
		                "linked_in_url": "",
		                "dob": "0000-00-00",
		                "phone": "",
		                "points": "0",
		                "balance": "0",
		                "bio": "",
		                "created_at": "2021-03-03 11:06:15",
		                "updated_at": "2021-03-03 11:06:15",
		                "employment_status": 0,
		                "gender": 0,
		                "state": 0
		            }
		        },
		        {
		            "friconn_id": "84365720",
		            "role_id": "4",
		            "last_name": "Ayoade",
		            "other_names": "Lawal",
		            "email": "thenewxpat@gmail.com1",
		            "profile_image": "",
		            "created_at": "2021-03-03 11:26:25",
		            "profile": {
		                "id": "2",
		                "friconn_id": "84365720",
		                "employment_status_id": "1",
		                "state_id": "1",
		                "gender_id": "1",
		                "linked_in_url": "here.com",
		                "dob": "0000-00-00",
		                "phone": "69494519175272",
		                "points": "230000000",
		                "balance": "0",
		                "bio": "I love coding",
		                "created_at": "2021-03-03 11:26:25",
		                "updated_at": "2021-03-04 22:09:31",
		                "employment_status": "Self-employed",
		                "gender": "Male",
		                "state": "Abia"
		            }
		        }
		    }
		}

## View an eduminister's Profile

-  http://localhost/friconn-api/v2/eduministers/profile/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "eduminister fetched successfully.",
		    "status_code": 201,
		    "data": {
		        "id": "19",
		        "friconn_id": "69494519",
		        "role_id": "4",
		        "last_name": "AyoadeY",
		        "other_names": "Lawal",
		        "email": "thenewxpat@gmail.com",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "820800",
		        "profile_image": "",
		        "approved": "1",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-03 11:06:15",
		        "updated_at": "2021-03-05 01:55:52"
		    }
		}

## Update an eduminister's Profile

-  http://localhost/friconn-api/v2/eduministers/profile :POST
	Requires {friconn_id,employment_status_id, linked_in_url, state_id, dob, gender_id, phone, points, bio}
		{
		    "status": "success",
		    "message": "Eduminister profile updated successfully.",
		    "status_code": 200,
		    "data": {
		        "id": "19",
		        "friconn_id": "69494519",
		        "role_id": "4",
		        "last_name": "AyoadeY",
		        "other_names": "Lawal",
		        "email": "thenewxpat@gmail.com",
		        "password": "MWE3bVh2aXBYdGpDVjdNc2lhWmdrZz09",
		        "verification_code": "820800",
		        "profile_image": "",
		        "approved": "1",
		        "blocked": "0",
		        "password_token": "",
		        "created_at": "2021-03-03 11:06:15",
		        "updated_at": "2021-03-05 01:55:52"
		    }
		}

## Update an eduminister's Point

-  http://localhost/friconn-api/v2/eduministers/points :POST
	
	Requires {friconn_id, points}
	
		Returns

		{
		    "status": "success",
		    "message": "Eduminister points updated successfully.",
		    "status_code": 200,
		    "data": "300"
		}

## View an eduminister's Point

-  http://localhost/friconn-api/v2/eduministers/points/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "eduminister points fetched successfully.",
		    "status_code": 200,
		    "data": "300"
		}

## Add a new eduminister's Course

-  http://localhost/friconn-api/v2/eduministers/courses :POST
	
	Requires {friconn_id,course_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Eduminister course added successfully.",
		    "status_code": 201,
		    "data": {
		        "id": "7",
		        "friconn_id": "22779222",
		        "course_id": "5",
		        "created_at": "2021-03-18 17:55:22",
		        "updated_at": "2021-03-18 17:55:22"
		    }
		}

## View an eduminister's Courses

-  http://localhost/friconn-api/v2/eduministers/courses/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Eduminister courses fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "7",
		            "friconn_id": "22779222",
		            "course_id": "5",
		            "created_at": "2021-03-18 17:55:22",
		            "updated_at": "2021-03-18 17:55:22",
		            "eduminister": "Ayoade Lawal",
		            "course": "Introduction to computer"
		        },
		        {
		            "id": "6",
		            "friconn_id": "22779222",
		            "course_id": "4",
		            "created_at": "2021-03-18 17:52:13",
		            "updated_at": "2021-03-18 17:52:13",
		            "eduminister": "Ayoade Lawal",
		            "course": "Introduction to Food Technology"
		        }
		    ]
		}

## View an eduminister's questions

-  http://localhost/friconn-api/v2/eduministers/questions/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Eduminister questions fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "2",
		            "friconn_id": "22779222",
		            "course_id": "2",
		            "subject": "Boring lectures",
		            "question": "This lecture is too boring. How can I help myself",
		            "answered": "0",
		            "points_charged": "0",
		            "active_status": "1",
		            "created_at": "2021-03-13 07:38:08",
		            "updated_at": "2021-03-18 18:09:17",
		            "eduminister": "Ayoade Lawal",
	            	"course": "Introduction to computer"
		        }
		    ]
		}

## View an eduminister's answers

-  http://localhost/friconn-api/v2/eduministers/answers/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "eduminister answers fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "3",
		            "question_id": "1",
		            "friconn_id": "22779222",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:22:44",
		            "updated_at": "2021-03-18 18:23:11",
		            "eduminister": "Ayoade Lawal",
		            "question": "FST101"
		        }
		    ]
		}

## View an eduminister's payouts history

-  http://localhost/friconn-api/v2/eduministers/payouts/{friconn_id} :GET
	
	Requires {friconn_id}
	
	Returns

		{
		    "status": "success",
		    "message": "Eduminister payouts history fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "3",
		            "friconn_id": "69494519",
		            "conversion_rate_id": "2",
		            "amount": "1201",
		            "deducted_points": "120",
		            "points_balance": "2000",
		            "created_at": "2021-03-05 01:52:19",
		            "updated_at": "2021-03-18 19:42:32",
		            "naira_per_point": "5"
		        },
		        {
		            "id": "2",
		            "friconn_id": "69494519",
		            "conversion_rate_id": "1",
		            "amount": "1000",
		            "deducted_points": "20",
		            "points_balance": "1000",
		            "created_at": "2021-03-05 00:58:14",
		            "updated_at": "2021-03-05 02:37:40",
		            "naira_per_point": "10"
		        }
		    ]
		}

## View all eduministers' payouts history

-  http://localhost/friconn-api/v2/eduministers/payouts :GET
	
	Requires {}
	
	Returns

		{
			"status": "success",
			"message": "Eduministers payouts history fetched successfully.",
			"status_code": 200,
			"data": [
		    	{
		            "id": "4",
		            "friconn_id": "84365720",
		            "conversion_rate_id": "1",
		            "amount": "1201",
		            "deducted_points": "120",
		            "points_balance": "2000",
		            "created_at": "2021-03-05 02:04:28",
		            "updated_at": "2021-03-05 02:04:28",
		            "eduminister": "Ayoade Lawal",
		            "naira_per_point": "10"
		        },
		        {
		            "id": "3",
		            "friconn_id": "69494519",
		            "conversion_rate_id": "2",
		            "amount": "1201",
		            "deducted_points": "120",
		            "points_balance": "2000",
		            "created_at": "2021-03-05 01:52:19",
		            "updated_at": "2021-03-18 19:42:32",
		            "eduminister": "AyoadeY Lawal",
		            "naira_per_point": "5"
		        },
		        {
		            "id": "2",
		            "friconn_id": "69494519",
		            "conversion_rate_id": "1",
		            "amount": "1000",
		            "deducted_points": "20",
		            "points_balance": "1000",
		            "created_at": "2021-03-05 00:58:14",
		            "updated_at": "2021-03-05 02:37:40",
		            "eduminister": "AyoadeY Lawal",
		            "naira_per_point": "10"
		        },
		        {
		            "id": "1",
		            "friconn_id": "84365720",
		            "conversion_rate_id": "1",
		            "amount": "1201",
		            "deducted_points": "120",
		            "points_balance": "2000",
		            "created_at": "2021-03-05 00:58:14",
		            "updated_at": "2021-03-05 00:58:14",
		            "eduminister": "Ayoade Lawal",
		            "naira_per_point": "10"
		        }
		    ]
		}

## Process eduminister's payout

-  http://localhost/friconn-api/v2/eduministers/payouts :POST
	
	Requires {friconn_id, deducted_points, amount, conversion_rate_id, points_balance}
	
	Returns

		{
		    "status": "success",
		    "message": "Payout request made successfully.",
		    "status_code": 200,
		    "data": true
		}



# Account Endpoints

## Request Password Reset Token

-  http://localhost/friconn-api/v2/account/password/{action} :POST
	
	Requires {action = request, email}
	
	Returns

		{
		    "message": "Okay. We are good to go! A reset link has been sent to your email address",
		    "status": "success",
		    "data": {
		        "token_data": {
		            "token": "69494519362267",
		            "friconn_id": "69494519",
		            "expires_at": "2021-03-21 08:27:29"
		        },
		        "link": {
		            "url": "http://127.0.0.1/friconn-api/v2/account/password/reset/69494519362267",
		            "token": "69494519362267"
		        }
		    }
		}


## Verify Password Reset Token

-  http://localhost/friconn-api/v2/account/password/{action} :POST
	
	Requires {action = token, token}
	
	Returns

		{
		    "status": "success",
		    "message": "Token Valid",
		    "data": {
		        "id": "1",
		        "friconn_id": "69494519",
		        "token": "69494519362267",
		        "expires_at": "2021-03-21 08:27:29",
		        "status": "1",
		        "created_at": "2021-03-20 08:25:38",
		        "updated_at": "2021-03-20 08:27:29"
		    },
		    "status_code": 200
		}

## Reset Password

-  http://localhost/friconn-api/v2/account/password/{action} :POST
	
	Requires {action = reset, friconn_id,new_password}
	
	Returns

		{
		    "status": "success",
		    "message": "Password resetted successfully",
		    "status_code": 200
		}

## Update Password

-  http://localhost/friconn-api/v2/account/password/{action} :POST
	
	Requires {action = update, friconn_id, old_password, new_password}
	
	Returns

		{
		    "status": "success",
		    "message": "Password updated successfully",
		    "status_code": 200
		}


## Revoke Password Reset Token

-  http://localhost/friconn-api/v2/account/password/{action} :POST
	
	Requires {action = revoke, token}
	
	Returns

		{
		    "status": "success",
		    "message": "Token revoked successfully.",
		    "status_code": 200
		}


## Verify Email

-  http://localhost/friconn-api/v2/account/email/{action} :POST
	
	Requires {action = verify, friconn_id, verification_code}
	
	Returns

		{
		    "status": "success",
		    "message": "Email address verified successfully.",
		    "status_code": 200
		}

## Request Email Verification Code

-  http://localhost/friconn-api/v2/account/email/{action} :POST
	
	Requires {action = request, friconn_id, verification_code}
	
	Returns

		{
		    "status": "success",
		    "message": "Verification code sent successfully",
		    "status_code": 200
		}


# Questions' Endpoints

## Ask New Question

-  http://localhost/friconn-api/v2/questions/new/ :POST
	
	Requires {course_id, friconn_id, subject, question}
	
	Returns

		{
		    "status": "success",
		    "message": "Question asked successfully. Await an answer soon",
		    "status_code": 201,
		    "data": {
		        "id": "3",
		        "friconn_id": "69494519",
		        "course_id": "4",
		        "subject": "How to write a proposal",
		        "question": "Can someone please teach me how to write a proposal?",
		        "answered": "0",
		        "points_charged": "0",
		        "active_status": "1",
		        "created_at": "2021-03-20 10:20:18",
		        "updated_at": "2021-03-20 10:20:18",
		        "course": "Introduction to Food Technology"
		    }
		}


## View All Questions' Records

-  http://localhost/friconn-api/v2/questions/view/ :GET
	
	Requires {}
	
	Returns

		{
		    "status": "success",
		    "message": "Questions fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "3",
		            "friconn_id": "69494519",
		            "course_id": "4",
		            "subject": "How to write a proposal",
		            "question": "Can someone please teach me how to write a proposal?",
		            "answered": "0",
		            "points_charged": "0",
		            "active_status": "1",
		            "created_at": "2021-03-20 10:20:18",
		            "updated_at": "2021-03-20 10:20:18",
		            "user": "AyoadeY Lawal",
		            "course": "Introduction to Food Technology"
		        },
		        {
		            "id": "2",
		            "friconn_id": "22779222",
		            "course_id": "5",
		            "subject": "Boring lectures",
		            "question": "This lecture is too boring. How can I help myself",
		            "answered": "0",
		            "points_charged": "0",
		            "active_status": "1",
		            "created_at": "2021-03-13 07:38:08",
		            "updated_at": "2021-03-18 18:12:45",
		            "user": "Ayoade Lawal",
		            "course": "Introduction to computer"
		        }
		    ]
		}


## View a Question's Records

-  http://localhost/friconn-api/v2/questions/view/{id} :GET
	
	Requires {id}
	
	Returns

		{
		    "status": "success",
		    "message": "Questions fetched successfully.",
		    "status_code": 200,
		    "data": {
			    "status": "success",
			    "message": "Question fetched successfully.",
			    "status_code": 200,
			    "data": {
			        "id": "3",
			        "friconn_id": "69494519",
			        "course_id": "4",
			        "subject": "How to write a proposal",
			        "question": "Can someone please teach me how to write a proposal?",
			        "answered": "0",
			        "points_charged": "0",
			        "active_status": "1",
			        "created_at": "2021-03-20 10:20:18",
			        "updated_at": "2021-03-20 10:20:18",
			        "user": "AyoadeY Lawal",
			        "course": "Introduction to Food Technology"
			    }
			}
		}



## View a Question's Answers

-  http://localhost/friconn-api/v2/questions/answers/{id} :GET
	
	Requires {id}
	
	Returns

		{
		    "status": "success",
		    "message": "Question answers fetched successfully.",
		    "status_code": 200,
		    "data": {
		        "0": {
		            "id": "5",
		            "question_id": "1",
		            "friconn_id": "88837618",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:27:54",
		            "updated_at": "2021-03-13 09:27:54",
		            "user": "Ade ff"
		        },
		        "1": {
		            "id": "4",
		            "question_id": "1",
		            "friconn_id": "88837618",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:27:37",
		            "updated_at": "2021-03-13 09:27:37",
		            "user": "Ade ff"
		        },
		        "2": {
		            "id": "3",
		            "question_id": "1",
		            "friconn_id": "22779222",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:22:44",
		            "updated_at": "2021-03-18 18:23:11",
		            "user": "Ayoade Lawal"
		        }
		        "question": "FST101"
		    }
		}


## Answer a Question

-  http://localhost/friconn-api/v2/questions/answers/ :POST
	
	Requires {question_id, friconn_id, answer}
	
	Returns

		{
		    "status": "success",
		    "message": "Answer submitted successfully.",
		    "status_code": 200,
		    "data": {
		        "id": "6",
		        "question_id": "3",
		        "friconn_id": "69494519",
		        "answer": "Writing of proposal is very easy. To write proposal, you must first know how to write a journal or write a complete book",
		        "is_answer": "0",
		        "points_earned": "0",
		        "created_at": "2021-03-20 10:51:44",
		        "updated_at": "2021-03-20 10:51:44",
		        "question": "How to write a proposal",
		        "user": "AyoadeY Lawal"
		    }
		}


# Answers' Endpoints

## View all Answers' Records

-  http://localhost/friconn-api/v2/answers/view :GET
	
	Requires {}
	
	Returns
	
		{
		    "status": "success",
		    "message": "Answers fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "8",
		            "question_id": "3",
		            "friconn_id": "69494519",
		            "answer": "Writing of proposal is very easy. To write proposal, you must first know how to write a journal or write a complete book",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-20 10:57:26",
		            "updated_at": "2021-03-20 10:57:26",
		            "user": "AyoadeY Lawal",
		            "question": "How to write a proposal"
		        },
		        {
		            "id": "4",
		            "question_id": "1",
		            "friconn_id": "88837618",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:27:37",
		            "updated_at": "2021-03-13 09:27:37",
		            "user": "Ade ff",
		            "question": "FST101"
		        },
		        {
		            "id": "3",
		            "question_id": "1",
		            "friconn_id": "22779222",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:22:44",
		            "updated_at": "2021-03-18 18:23:11",
		            "user": "Ayoade Lawal",
		            "question": "FST101"
		        }
		    ]
		}

## View an Answer's Record

-  http://localhost/friconn-api/v2/answers/view/{id} :GET
	
	Requires {id}
	
	Returns
	
		{
		    "status": "success",
		    "message": "Answers fetched successfully.",
		    "status_code": 200,
		    "data": [
		        {
		            "id": "8",
		            "question_id": "3",
		            "friconn_id": "69494519",
		            "answer": "Writing of proposal is very easy. To write proposal, you must first know how to write a journal or write a complete book",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-20 10:57:26",
		            "updated_at": "2021-03-20 10:57:26",
		            "user": "AyoadeY Lawal",
		            "question": "How to write a proposal"
		        },
		        {
		            "id": "4",
		            "question_id": "1",
		            "friconn_id": "88837618",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:27:37",
		            "updated_at": "2021-03-13 09:27:37",
		            "user": "Ade ff",
		            "question": "FST101"
		        },
		        {
		            "id": "3",
		            "question_id": "1",
		            "friconn_id": "22779222",
		            "answer": "Well, you need to read a lot",
		            "is_answer": "0",
		            "points_earned": "0",
		            "created_at": "2021-03-13 09:22:44",
		            "updated_at": "2021-03-18 18:23:11",
		            "user": "Ayoade Lawal",
		            "question": "FST101"
		        }
		    ]
		}

## Vote an Answer

-  http://localhost/friconn-api/v2/answers/votes :POST
	
	Requires {friconn_id, answer_id,points_earned}
	
	Returns
	
		{
		    "status": "success",
		    "message": "Answer voted successfully.",
		    "status_code": 200,
		    "data": true
		}