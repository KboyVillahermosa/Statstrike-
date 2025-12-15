# React Native Connection Fix Guide

## Problem
React Native app shows "Network Error" when trying to connect to Laravel backend.

## Root Cause
React Native apps **cannot use `localhost` or `127.0.0.1`** to connect to a development server. They need the actual IP address of your development machine.

## Solution

### Step 1: Find Your Computer's IP Address

**Windows:**
```powershell
ipconfig
```
Look for "IPv4 Address" under your active network adapter (usually `192.168.x.x` or `10.x.x.x`)

**Mac/Linux:**
```bash
ifconfig
# or
ip addr
```

From our earlier check, your IP addresses are:
- `192.168.56.1` (VirtualBox adapter - for VM connections)
- `192.168.1.20` (Main network - use this for physical devices)

### Step 2: Update React Native App Configuration

Find your API base URL configuration. Common locations:
- `src/config/api.ts`
- `src/constants/api.ts`
- `.env` file
- `config.ts`
- Environment variables

**Change from:**
```typescript
// ❌ DON'T USE THESE
const API_URL = 'http://localhost:8000/api'
const API_URL = 'http://127.0.0.1:8000/api'
```

**To:**
```typescript
// ✅ USE YOUR ACTUAL IP
const API_URL = 'http://192.168.1.20:8000/api'
```

### Step 3: Different Scenarios

#### Android Emulator
Use: `http://10.0.2.2:8000/api`
- The Android emulator maps `10.0.2.2` to the host machine's `localhost`

#### iOS Simulator  
Use: `http://localhost:8000/api`
- iOS Simulator can access `localhost` directly

#### Physical Device (Same WiFi)
Use: `http://192.168.1.20:8000/api`
- Use your computer's actual IP address
- Device and computer must be on same WiFi network

### Step 4: Ensure Laravel Server is Accessible

The Laravel server should be started with:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

The `--host=0.0.0.0` flag makes the server accessible from your network, not just localhost.

**Current Status:** ✅ Server is running on `0.0.0.0:8000`

### Step 5: Test Connection

1. **Test from Browser (on same network):**
   ```
   http://192.168.1.20:8000/api/health
   ```
   Should return: `{"status":"ok","message":"Laravel API is reachable",...}`

2. **Test from React Native app:**
   - Try the registration again
   - Check `.cursor/debug.log` - you should see API request logs if connection works

### Step 6: Verify Full Endpoint URL

Your registration endpoint should be:
```
POST http://192.168.1.20:8000/api/auth/register
```

With headers:
```
Content-Type: application/json
Accept: application/json
```

With body:
```json
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

## Common Issues

### Still Getting "Network Error"

1. **Firewall blocking port 8000:**
   - Windows: Check Windows Firewall settings
   - Allow incoming connections on port 8000

2. **Wrong IP address:**
   - Make sure you're using the IP of the network adapter that's connected to WiFi
   - Re-run `ipconfig` to verify current IP

3. **Server not accessible:**
   - Ensure server is running with `--host=0.0.0.0`
   - Check `netstat -ano | findstr :8000` shows `0.0.0.0:8000`

4. **Different networks:**
   - React Native device and Laravel server must be on same WiFi network
   - Mobile data won't work - must use WiFi

### CORS Errors

If you see CORS errors, the backend CORS is already configured correctly:
- `config/cors.php` has `'allowed_origins' => ['*']`
- Run `php artisan config:clear` if you changed CORS settings

## Verification Checklist

- [ ] Laravel server running: `php artisan serve --host=0.0.0.0 --port=8000`
- [ ] Server accessible from browser: `http://192.168.1.20:8000/api/health`
- [ ] React Native app uses IP address (not localhost)
- [ ] Device and computer on same WiFi network
- [ ] Firewall allows port 8000
- [ ] Full endpoint URL: `http://192.168.1.20:8000/api/auth/register`
- [ ] Request headers include `Content-Type: application/json`

## Debug Logging

The Laravel backend has been instrumented with logging. Check `.cursor/debug.log` after attempting registration:

- **If logs appear:** Connection is working, check for validation/authentication errors
- **If no logs:** Connection is not reaching Laravel - verify IP address and network settings

## Next Steps After Connection Works

Once you see logs in `.cursor/debug.log`, the connection is working! Then we can:
1. Remove debug instrumentation
2. Test full registration flow
3. Verify token generation
4. Test other API endpoints

