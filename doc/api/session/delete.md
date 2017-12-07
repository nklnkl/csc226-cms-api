# Delete Session
Deletes a session from the database.

## Request
- url
  - api/session
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - none
- url queries
  - none
- body (json string)
  - none

## Response
- code: 200
  - description: session was deleted
  - body (json string)
    - none
- code: 401
  - description: user was not authorized
  - body (json string)
    - none
- code: 404
  - description: session was not found
  - body (json string)
    - none
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
