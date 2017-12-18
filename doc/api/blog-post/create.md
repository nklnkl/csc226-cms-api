# Create Blog Post
Submits blog post to database.

## Request
- url
  - api/blog-post
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
  - 'session_id' (string, required)
  - 'account_id' (string, required)
- body
  - title (string, required)
  - body (string, required)
  - privacy (integer, required)

## Response
- code: 200
  - description: blog post registered
- code: 401
  - description: client not authorized
  - conditions
    - session_id account_id combo invalid
- code: 403
  - description: client not allowed create blog post
  - conditions:
    - account inactive
- code: 409
  - description: account has blog post with same title
- code: 422
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): title invalid
      - 2 (string, optional): body invalid
      - 3 (integer, optional): privacy invalid
- code: 500
  - description: server error
