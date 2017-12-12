# Create comment
Submits comment to database.

## Request
- url
  - api/comment
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- body
  - blog_post_id (string, required)
  - body (string, required)

## Response
- code: 200
  - description: comment registered
- code: 401
  - description: client not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client not allowed create comment
  - conditions:
    - account inactive
- code: 404
  - description: blog post not found
- code: 422
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): blog post id invalid
      - 2 (string, optional): body invalid
- code: 500
  - description: server error
