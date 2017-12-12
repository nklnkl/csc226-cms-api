# List Comments
Lists comments from database.

## Request
- url
  - api/comment/list
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url queries
  - account_id (string, optional)
  - blog_post_id (string, optional)
  - sort (integer, optional)
    - values
      - 0: return newest first
      - 1: return oldest first
  - limit (integer, optional)
    - description: limit the size of the list
  - page (integer, optional, requires limit)
    - description: indicates which page of a limited list to return

## Response
- code: 200
  - description: comments found
  - body (json array of comments)
    - comment (object, optional)
      - blog-post-id (string, required)
      - body (string, required)
      - id (string, required)
      - account-id (string, required)
      - created (integer, required)
      - updated (integer, required)
- code: 404
  - description: no comments found
- code: 500
  - description: server error
