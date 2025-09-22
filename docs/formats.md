# Request/Response Formats

The NCA API uses JSON for all request and response bodies.

## Request Format

All POST and PUT requests must include a `Content-Type: application/json` header.

### Example Request

```http
POST /v1/users HTTP/1.1
Host: api.nca.example.com
Authorization: Bearer sk_1234567890abcdef
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "role": "admin"
}
```

## Response Format

All responses are returned as JSON objects with a consistent structure.

### Success Response

```json
{
  "success": true,
  "data": {
    "id": "user_123456",
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "admin",
    "created_at": "2023-01-01T12:00:00Z",
    "updated_at": "2023-01-01T12:00:00Z"
  }
}
```

### Error Response

```json
{
  "success": false,
  "error": {
    "code": "validation_error",
    "message": "The provided email is invalid",
    "details": {
      "field": "email",
      "value": "invalid-email"
    }
  }
}
```

## Common Fields

All response objects include these common fields:

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Indicates if the request was successful |
| `data` | object/array | The response data (only present on success) |
| `error` | object | Error details (only present on failure) |

## Pagination

List endpoints return paginated results:

```json
{
  "success": true,
  "data": [...],
  "pagination": {
    "page": 1,
    "per_page": 20,
    "total": 100,
    "total_pages": 5
  }
}
```