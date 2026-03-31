# DevOps Guide: Enabling WebSockets on Production Gateway 🏗️

This guide provides the necessary steps to enable WebSocket support for the MindBalance API at `https://services-api.a2zhealth.in`.

## 1. Current Diagnostic (The Warning)
Our tests confirm that the backend is 100% functional, but the **Nginx Gateway** is currently dropping WebSocket handshakes because it is missing the protocol "Upgrade" headers.

- **URL**: `https://services-api.a2zhealth.in/v1/mental-health/socket.io/`
- **Result**: `❌ websocket error` (400/502/504)
- **Status**: Blocking real-time consultations and AI chat.

---

## 2. Implementation Steps

### Step 1: Locate the Site Configuration
Connect to the production gateway and open the Nginx configuration file for `services-api.a2zhealth.in`.
Typical location: `/etc/nginx/sites-available/services-api`

### Step 2: Add the Socket.io Location Block
Insert the following block **above** your general `/v1/` location or within the same server context. This specifically handles the Persistent connection requirements for Socket.io.

```nginx
# --- START WebSocket Support for Mental Health API ---
location /v1/mental-health/socket.io/ {
    # Point to your internal application port (e.g., 5000)
    # Ensure the path ends with /socket.io/
    proxy_pass http://127.0.0.1:5000/socket.io/;

    # Critical Header: Required for WebSocket Handshake
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";

    # Standard Headers
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;

    # Timeouts: Do not drop clinical sessions prematurely
    proxy_read_timeout 86400; # 24 hours
    proxy_send_timeout 86400;
}
# --- END WebSocket Support ---
```

### Step 3: Test and Reload
Validate the configuration syntax and reload the Nginx service.
```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## 3. Special Troubleshooting: The "301 Moved Permanently" Error ⚠️

If the test script returns **`Unexpected server response: 301`**, it means Nginx is forcing a redirect, which kills the WebSocket handshake.

**Common Causes:**
1.  **Trailing Slash Enforcement**: Nginx might be redirecting `/socket.io` to `/socket.io/`.
2.  **HTTPS Enforcement**: If the proxy is redirecting back to itself for SSL.

**The Fix**: Ensure your `location` block is specific and does not trigger global rewrites.
```nginx
location /v1/mental-health/socket.io/ {
    # ... (headers as above)
    # Explicitly break rewrites for this path
    rewrite ^/v1/mental-health/socket.io/(.*) /socket.io/$1 break;
    proxy_pass http://127.0.0.1:5000;
}
```

---

## 4. Post-Deployment Verification
Once Nginx is reloaded, verify the connectivity from a client machine using the provided test script.

**Test Credentials (already validated on production):**
- **User**: `johndoe`
- **Password**: `Password@123`
- **Role**: `patient`

**Command to Verify:**
```bash
node scripts/tests/tes.js
```

### Expected Successful Output:
```text
🌐 Starting Production WebSocket Test...
🔑 Authenticating as johndoe on PRODUCTION...
✅ Production Auth success.
🔌 Connecting to services-api.a2zhealth.in...
🚀 SUCCESS: Connected to Production Socket.io!
✨ Received pong_test from server.
```

If the connection still fails after these steps, please check the Nginx error logs (`/var/log/nginx/error.log`) for "permission denied" errors related to `proxy_pass`.
