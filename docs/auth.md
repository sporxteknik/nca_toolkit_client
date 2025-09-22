# Authentication

The NCA API uses API keys to authenticate requests. You can view and manage your API keys in the NCA Dashboard.

## API Keys

To authenticate with the NCA API, you need to include your API key in the `Authorization` header of your requests.

```
Authorization: Bearer YOUR_API_KEY
```

## Example

```javascript
const NcaClient = require('nca-client');

const client = new NcaClient({
  apiKey: 'sk_1234567890abcdef',
  baseUrl: 'https://api.nca.example.com'
});

// The client will automatically include the API key in requests
client.get('/users')
  .then(response => console.log(response.data))
  .catch(error => console.error(error));
```

## Token Expiration

API keys do not expire unless revoked. You can generate new keys at any time in the NCA Dashboard.

## Rate Limiting

Authenticated requests are subject to rate limiting. The default limit is 1000 requests per hour per API key.

## Revoking API Keys

You can revoke API keys at any time in the NCA Dashboard. Once revoked, the key will no longer be valid for authentication.