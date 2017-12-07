# Delete blog post
Delete a blog from the database.

## Request
- url
  - api/blog-post/:id
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - id (string, required)
- url queries
  - none
- body (json string)
  - none

## Response
- code: 200
  - description: blog post was deleted
  - body (json string)
    - none
- code: 401
  - description: client was not authorized
  - body (json string)
    - none
- code: 403
  - description: client not allowed to delete this blog post
  - body (json string)
    - none
- code: 404
  - description: the blog post could not be found
  - body (json string)
    - none
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
