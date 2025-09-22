# Error Handling

The NCA API uses standard HTTP status codes to indicate the success or failure of requests.

## HTTP Status Codes

| Code | Title | Description |
|------|-------|-------------|
| 200 | OK | Success |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | The request was invalid |
| 401 | Unauthorized | Authentication failed |
| 403 | Forbidden | Access to the resource is forbidden |
| 404 | Not Found | The requested resource was not found |
| 422 | Unprocessable Entity | The request was well-formed but unable to be followed due to semantic errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | An error occurred on the server |

## Error Response Format

All error responses follow a consistent format:

```json
{
  "success": false,
  "error": {
    "code": "error_code",
    "message": "Human readable error message",
    "details": {
      // Additional error details
    }
  }
}
```

## Common Error Codes

| Code | Description |
|------|-------------|
| `validation_error` | Request data failed validation |
| `authentication_error` | Authentication failed |
| `authorization_error` | Insufficient permissions |
| `not_found` | Resource not found |
| `rate_limit_exceeded` | API rate limit exceeded |
| `internal_error` | Unexpected server error |

## Client-Side Error Handling

When using the NCA client library, errors can be handled as follows:

```javascript
const NcaClient = require('nca-client');

const client = new NcaClient({
  apiKey: 'sk_1234567890abcdef',
  baseUrl: 'https://api.nca.example.com'
});

client.get('/users')
  .then(response => {
    console.log(response.data);
  })
  .catch(error => {
    if (error.code === 'authentication_error') {
      console.log('Invalid API key');
    } else if (error.code === 'rate_limit_exceeded') {
      console.log('Rate limit exceeded. Please wait before making more requests.');
    } else {
      console.log('An error occurred:', error.message);
    }
  });
```