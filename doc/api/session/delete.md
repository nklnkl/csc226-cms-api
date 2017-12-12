# Delete Session
Deletes session from database.

## Request
- url
  - api/session/:id
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: session deleted
- code: 401
  - description: client not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client forbidden to delete session
  - conditions:
    - session not owned
    - client not admin
- code: 404
  - description: session was not found
- code: 500
  - description: server error
