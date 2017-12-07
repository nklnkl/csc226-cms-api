# Create Blog Post
Submits blog post to database.

## Request
- url
  - api/blog-post
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- body
  - title (string, required)
  - body (string, required)

## Response
- code: 200
  - description: blog post registered
- code: 401
  - description: client not authorized
- code: 409
  - description: account has blog post with same title
- code: 422
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): title invalid
      - 2 (string, optional): body invalid
- code: 500
  - description: server error
