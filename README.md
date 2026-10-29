# ip_web

A tiny "what is my IP" web service. It shows your public IP address and a few
details about your request (OS, browser, mobile, bot).

## Usage

| Request                     | Response                        |
| --------------------------- | ------------------------------- |
| Browser                     | HTML page                       |
| `curl` / `wget`             | `Your IP: 1.2.3.4` (plain text) |
| `Accept: application/json`  | JSON                            |

```bash
# plain text
curl https://ip.dodoslav.eu

# JSON
curl -H "Accept: application/json" https://ip.dodoslav.eu
```

The app lives in [`web/`](web/) and runs on PHP.
