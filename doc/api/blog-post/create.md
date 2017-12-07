# Create Blog Post
Submits blog post to the database.

## Request
- url
  - api/blog-post
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - none
- url queries
  - none
- body (json string)
  - title (string, required)
  - body (string, required)

## Response
- code: 200
  - description: blog post was registered
  - body (json string)
    - none
- code: 401
  - description: client was not authorized
  - body (json string)
    - none
- code: 409
  - description: account already has a blog post with the same title
  - body (json string)
    - error (array, required)
      - 'you already have a blog post with the same title' (string, required)
- code: 422
  - description: the data given by the client did not pass validation
  - body (json string)
    - error (array, required)
      - 'blog post too long' (string, optional)
      - 'blog title too long' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
