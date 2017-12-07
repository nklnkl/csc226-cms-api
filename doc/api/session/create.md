# Create Session
Authorizes a user for a valid session to the database.

## Request
- url
  - api/session
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
- url parameters
  - none
- url queries
  - none
- body (json string)
  - email (string, required)
  - password (string, required)

## Response
- code: 200
  - description: a session was created
  - body (json string)
    - session-id (string, required)
    - account-id (string, required)
- code: 401
  - description: the credentials given by the client were incorrect
  - body (json strong)
    - none
- code: 422
  - description: the data given by the client did not pass validation
  - body (json string)
    - error (array, required)
      - 'email invalid' (string, optional)
      - 'password invalid' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
