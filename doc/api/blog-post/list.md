# List Blog Posts
Lists blog posts from database.

## Request
- url
  - api/blog_post/list
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url queries
  - content (string, optional)
  - account_id (string, optional)
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
  - description: blog_posts found
  - body (json array of blog_posts)
    - blog_post (object, required)
      - title (string, required)
      - body (string, required, reduced)
      - id (string, required)
      - account_id (string, required)
      - created (integer, required)
      - updated (integer, required)
- code: 404
  - description: no blog_posts found
- code: 500
  - description: server error
