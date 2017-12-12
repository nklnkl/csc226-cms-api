# Retrieve comment
Retrieves a comment from the database. Uses the 'id' parameter in
the url to query the comment from database.

## Request
- url
  - api/comment/:id
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: comment was found
  - body
    - comment (key value object, required)
      - blog-post-id (string, required)
      - body (string, required)
      - id (string, required)
      - account-id (string, required)
      - created (integer, required)
      - updated (integer, required)
- code: 404
  - description: comment was not found
- code: 500
  - description: server error
