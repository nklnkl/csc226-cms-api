# Delete blog post
Delete a blog from the database.

## Request
- url
  - api/blog-post/:id
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session_id' (string, required)
  - 'account_id' (string, required)
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: blog post was deleted
- code: 401
  - description: client was not authorized
  - conditions
    - session_id account_id combo invalid
- code: 403
  - description: client not allowed to delete this blog post
  - conditions:
    - blog post not owned
    - client not admin
- code: 404
  - description: the blog post could not be found
- code: 500
  - description: server error
