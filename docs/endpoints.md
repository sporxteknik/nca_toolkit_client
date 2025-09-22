# API Endpoints

The NCA API is organized around REST principles and uses standard HTTP response codes.

## Base URL

All API requests should be made to:

```
https://api.nca.example.com/v1
```

## Endpoints

### Users

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/users` | GET | List all users |
| `/users` | POST | Create a new user |
| `/users/{id}` | GET | Retrieve a user |
| `/users/{id}` | PUT | Update a user |
| `/users/{id}` | DELETE | Delete a user |

### Devices

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/devices` | GET | List all devices |
| `/devices` | POST | Register a new device |
| `/devices/{id}` | GET | Retrieve a device |
| `/devices/{id}` | PUT | Update a device |
| `/devices/{id}` | DELETE | Remove a device |

### Sessions

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/sessions` | GET | List all sessions |
| `/sessions` | POST | Create a new session |
| `/sessions/{id}` | GET | Retrieve a session |
| `/sessions/{id}` | DELETE | End a session |

## HTTP Status Codes

The NCA API uses standard HTTP status codes:

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 500 | Internal Server Error |