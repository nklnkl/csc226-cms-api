# Delete comment
Delete comment from database.

## Request
- url
  - api/comment/:id
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
  - description: comment was deleted
- code: 401
  - description: client was not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client not allowed to delete this comment
  - conditions:
    - comment not owned
    - account inactive
    - client not admin
- code: 404
  - description: the comment could not be found
- code: 500
  - description: server error
