# Noise Monitoring System
A simple Flight PHP based web app that let's you create sound listening nodes with (Mac ID, Name) and send data on behalf of that node using an API.

# CURL call for posting sound data on behalf of a node
```bash
curl --location --request POST 'https://hostname.com/api/add-sound-level' \
--form 'mac_id=abcd' \
--form 'token=<whatever your token is, from the navbar>' \
--form 'sound=20'
```

