#!/bin/sh

/bin/cat <<'EOF' > /etc/traefik.toml
[http.routers.localhost]
    rule = "Host(`localhost`)"
    service = "noop@internal"

    [http.routers.localhost.tls]
        certResolver = "letsencrypt"
EOF

step-ca \
    --password-file ~/.step/secrets/password-file \
    ~/.step/config/ca.json \
        1>&2 \
        &

traefik \
    --log.level=DEBUG \
    --entrypoints.https.address=:443 \
    --providers.file.filename=/etc/traefik.toml \
    --certificatesresolvers.letsencrypt.acme.caserver=https://127.0.0.1:8443/acme/letsencrypt/directory \
    --certificatesresolvers.letsencrypt.acme.storage=/acme.json \
    --certificatesresolvers.letsencrypt.acme.tlschallenge=true \
       1>&2 \
       &

for i in $(seq 1 20); do
    curl https://localhost 1>&2 && break

    sleep 5
done

if [ "printacme" == "$1" ]; then

    if [ ! -f /acme.json ]; then
        echo "No acme.json generated" > /dev/stderr

        exit 1
    else
        cat /acme.json
    fi
else
    exec "$@"
fi
