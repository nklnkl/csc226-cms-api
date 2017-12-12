# Create Session
Submits session to database.

## Request
- url
  - api/session
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
- body
  - email (string, required)
  - password (string, required)

## Response
- code: 200
  - description: session created
  - body
    - session-id (string, required)
    - account-id (string, required)
- code: 401
  - description: request body data incorrect
  - body
    - error (array, required)
      - 1 (string, optional): email invalid
      - 2 (string, optional): password invalid
- code: 403
  - description: client forbidden to create session
  - conditions:
    - account inactive
- code: 500
  - description: server error
