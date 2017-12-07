# Retrieve Blog Post
Retrieves a blog post from the database. Uses the 'id' parameter in
the url to query the blog post from the database.

## Request
- url
  - api/blog-post/:id
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, optional)
  - 'account-id' (string, optional)
- url parameters
  - id (string, required)
- url queries
  - none
- body
  - none

## Response
- code: 200
  - description: blog post was found
  - body
    - blog-post (key value object, required)
      - title (string, required)
      - body (string, required)
      - id (string, required)
      - account-id (string, required)
      - privacy (integer, required)
      - created (integer, required)
      - updated (integer, required)
- code: 403
  - description: blog was found, but can not be given due to privacy
  - body
    - none
- code: 404
  - description: blog post was not found
  - body
    - none
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body
    - error (array, required)
      - 'server error, please try again later' (string, required)
