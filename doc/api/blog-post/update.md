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
- url queries
  - none
- body
  - title (string, required)
  - body (string, required)

## Response
- code: 200
  - description: blog post was updated
- code: 401
  - description: client was not authorized
- code: 403
  - description: client not allowed to update this blog post
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
      - 'blog post too long' (string, optional)
      - 'blog title too long' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body
    - error (array, required)
      - 'server error, please try again later' (string, required)
