This project was born from the wish to re-use the certificates,
the HTTP reverse proxy [traefik](https://github.com/traefik/traefik) generates. Traefik
stores its certificates in a json file, for which i wanted a simple interface for.

Traefiks storage needs to have the access-mode 600 and normally runs as root to bind port 80,
which makes it hard to share this file with other unprivileged containers. This container also
is privileged AND has access to your certificates, so you should make sure, that only services
from trusted networks have access.

# Usage
Start the latest image and run it like this:
```
docker run --rm -it \
    -v /path/to/your/acme.json:/acme.json:ro \
    --name cert-provider \
    --network my-network \
    ghcr.io/rejinka/cert-provider:latest
```

Any container attached to ```my-network``` is now able to connect to ```http://cert-provider```,
so you can fetch certificates and keys with curl like this:
* ```curl http://cert-provider/letsencrypt/example.com/fullchain.pem```
* ```curl http://cert-provider/letsencrypt/example.com/privkey.pem```

In the example above "letsencrypt" is your configured resolver and "example.com" your configured
primary domain.
