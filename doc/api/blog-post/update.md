# Update Blog Post
Updates a blog from the database.

## Request
- url
  - api/blog-post/:id
- method
  - PATCH
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - id (string, required)
- body
  - title (string, required)
  - body (string, required)

## Response
- code: 200
  - description: blog post was updated
- code: 401
  - description: client was not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client not allowed to update this blog post
  - conditions:
    - blog post not owned
- code: 404
  - description: blog post was not found
- code: 409
  - description: account already has a blog post with the same title
  - body
    - error (array, required)
      - 'you already have a blog post with the same title' (string, required)
- code: 422
  - description: the data given by the client did not pass validation
  - body
    - error (array, required)
      - 1 (string, optional): title invalid
      - 2 (string, optional): body invalid
- code: 500
  - description: server error
