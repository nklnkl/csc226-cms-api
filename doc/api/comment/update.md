# Update comment
Updates comment from database.

## Request
- url
  - api/comment/:id
- method
  - PATCH
- headers
  - 'Content-Type' : 'application/json'
  - 'session_id' (string, required)
  - 'account_id' (string, required)
- url parameters
  - id (string, required)
- body
  - body (string, required)

## Response
- code: 200
  - description: comment was updated
- code: 401
  - description: client was not authorized
  - conditions
    - session_id account_id combo invalid
- code: 403
  - description: client not allowed to update this comment
  - conditions:
    - comment not owned
- code: 404
  - description: comment was not found
- code: 422
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): blog post id invalid
      - 2 (string, optional): body invalid
- code: 500
  - description: server error
