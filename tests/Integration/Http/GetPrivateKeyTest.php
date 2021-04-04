<?php
declare(strict_types = 1);

namespace Tests\Integration\Http;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetPrivateKeyTest extends WebTestCase
{

    /**
     * @test
     */
    public function key_exists(): void
    {
        $client = self::createClient();

        $expected =<<<EOF
-----BEGIN RSA PRIVATE KEY-----
MIIJKAIBAAKCAgEAwl9CjH3kfc7K/zXgpq2agoxisq22EX2zLmc2HyWYv/z87ajP
fvgsUgfWp+6sA5Qaz8YBpdjGILtafApC3L4gZel8H8Fv4P5F/naV/a4DMlmIut/x
axRLNlMZHBHXd0hMMJGqmd2UAHUJdqwxwt6eC0BqLAgE70XLkSMv9MiO9oFniymc
53zq4YheGnosgATS7wFO9LIUJ9ft07eHrJID++9jJMHEKwryzWFtK81IBBPP3GDC
RawSHiL8vY8HwofwxKyySNXI+1+s/zVUJGgNTabWezx7KcZLDegK3hY/CPkA/5or
vAIRsUs6loHfhSUWxjeWLQGiPc557NtlYFk82Kty9eIJNJiEVWlNays/P/c1x5bs
DkLRMD/pRrTp4X54wEzKMOgwuD8v2YOMkkuZZmDTY60453IeIgkCVDRew0C60Kuk
4oRnBHgFvaeADh+NND0C/Kk6wg3WXBDbID5POZSoRE9ge3fvEta4BAhz690rVRFV
fwy994n8tpIgbm3at87dQZrL1vpA0ayJccEhe6R+qZfFQJZmjJ91y4L/T50VdD7m
RffBHyEQzsg1cSxLym5SOzF5vB3LaxK170mBCgRyE4Kx4XVX2DsToybhBD1JfKbZ
IO3QGZVBggzksVaLOihRiaheRI7zskwK7LlWpKzbAqfg8tVFDq46IfVdBSkCAwEA
AQKCAgBo8XXE1NPg+R2w3S4F0AV0JsfDIPcESpHLo/4covft8HG+/z3Il8WWJJVI
5hwJcpVnSMjq+jc+xOj9v6yJ8j0byQwzOQVmvOKdmPkylhkzvhOMkgyOe85/7i3I
cfmbdXVtY7hwiqm19Xq+kAnt5LP/7y9jxpCjf1OBcTYbO420mfmFsVn9wfwu+KmW
MhCErhYU5Of0RZGMUZEeliCyk8OC6qTk25lGhArDyK5gz9zqFqhLnBJH+7dsfgdn
cSoHZc4g4f0ARizVS7rjr69WWLeWDVYQA3+b9mvffkEf13MBLS/U0TB7ucJDrxJa
4JQGFxhMvQZgFlm6dRhvQaVid8vZXbiKp9j6evMA8ySGGGoPwW2YDyTRT4Df/IVp
uureeR6/KnD0129pf5sNwdEE2I/y8DJqcQF4sotVlpg0bHJVPiqfkk1ArrQq9ovR
ldCFCESOlvTRWKE0Q1rICiD9ZodpTSYWtPsquafJU0mOuxdmCNB1Jh8W1iBAwmQS
hBfmNlnl8xFC6ppOeFCbNJMu1LtcLFjSekXnrt2soDxOZKlvFupKPqSBiaWgYfm4
0ktIq17H+qyuD+7n6q0lPNbO5Hj+JrbpiZl9VH3SkUZM6XlePbjdsBa338vz5HeF
EsHdqXff+BiO181hlYymrsVtAaO58yuuU38UL+1J0fSJLduqYQKCAQEAyH7Su6l5
Ux2kg/SPioH4T8mUwIobABuZaMxxuapjJ5BQp1U7DluCZLfbx7GYIWHz8RaRGMlM
NG0cZYpZBqKSsdJOWzQm5uIaZLFBwwupb+voQ1fMHOy/UQ1WdB9Trp/Gfa/G14Qc
dwPNhDSW9WW44i7kBBJ22D1yaeKNrmO9yxo+4I7hTLkXJImdlzHJ9DHl/yG2/VE/
Ok8nJn4QHfKN3DlTp/hQHb0XfPAoNpQAhyRcWN2oYCf3hxPciCnJiWqiK4pYcvMt
1Ke59cl2OXjenMW+98PSNPey3YRl0Et0RpPabWCSqCs9rpHgph3vv6uTULtzaAna
GDbuZKksGSFZfwKCAQEA+C56FO0OTKd80WWj6YzkQAL2m9TWWPofOQFaGfLZ7voW
VHufJRpejLQ0ZPzJ0wQGX/SoL/y0KGuuhCwFkDJd7pVpKLNFvpdO0tx/0P3zpGPW
VtRlwfC7Vt3kS8+Y4Om8N1Rf3qQOhDqaCQsyPEdu/+qv+hK5kTswUCo28/vR0C+v
as9nVpoBZKBQz9dP/5B7fJR2Xz1pN++bqaMuKY0bHc3SnpQhY+PkZUFj3ObfqtSF
zcRiOTwQdP6iFwyN95g1udhkELT8VrII+kWdvFZwH8wuXjGrKn4VOH7SMVHez5CD
GD0kQn+XUmrCdYJKE8Q4JZ4/F7DXOfKtv/D1nIflVwKCAQBlu6RmQJolSHc/1Yo4
N7atlZV7FRiaN2biPErAcOqbGqkyHxc2M8y4GmrUqxKMbc1/2XmKYCnguDSeeVKv
ATL6M8I6mP3cTYIi8aZyt6oj9koGUbhqPN7e26uwhGzLwJrRD64PkSN4aF+R47Vt
k036xtxADLZtBtDVVXWatVWKhyi8fQNpEMnjakiifUsCM4eGq67UXrN8fMlayMCY
0YiV5CjEpETSsi4G4T0mZHbYG+UX2nEjSWRaEAPou3td1NOw0CQNP8NTkj/TrYPl
qaUbW2OJCL3SzpTdvd6DkKXRYhiFQ2lnF3nW6IJbB/mtAfxgDJ2EfutBOi5AuW58
2eU1AoIBAF9Q6k4zJTTHdqatVKCUHe1/tdrviUsu9OLd5A7nOFAR+JWFzP9g+0Mq
ypymgZTrjM85lYaolUCY6rOUfKZ+0ZiaBYM+JuEmF33gda58eiB+v4kllddAb5LT
MaT0HBD1c/YED7XgaDrcjuA+TpLcFINxTRjMLDzc8Jc5P1XBNzmvtr1c3qFiEHrn
H2JTnUPX57VXB4dYfJqhCbXDGC2ZJfryW5vP5GsK0WMaFBJ5ERn4+VKnw9mbee6V
BQe5KKUY7jlsJDJOcEOTqOMF7aixwepUbtJ6XPS668eA3ZVXji1FoTOj99MaUbGz
ku+ylGS3DaFU/4JHxsFYtOZs0sfFYY0CggEBAJON4l1nvOg9DH05Khb704xHddSU
oUK9yxgvyeFOlU7GV91gB/+KpoduYEh5MrbV252+jaR5pgtXmlfTEE0uxpQuLRzd
43BfnBEY6fOpDXLSMcmX8dXNEmG+Bl+UGyMvX0MmkKOri4LWVGuhNPt2cNjoniGZ
XMmk9Pqfrqwrw+o8UDnPWu6svpkW9vJMPxdQp9147rtdH4sKgWKzEs0206fgfnte
sSH18DQkmi/54FUd0vpZGnyqHg5N4RWgKbGwki3FYm77V8/VnD9qh/tbcyhcJNuJ
RZZi3kqKCtm+E2z2eTpPZvhX47QhbvH1cCHyRxFta8TMPRVCtqbIRAxpgGw=
-----END RSA PRIVATE KEY-----
EOF;

        $client->request('GET', '/letsencrypt/localhost.dev.local/privkey.pem');

        self::assertResponseStatusCodeSame(200);
        self::assertSame($expected, trim($client->getResponse()->getContent()));
    }

    /**
     * @test
     */
    public function key_does_not_exist(): void
    {
        $client = self::createClient();
        $client->request('GET', '/letsencrypt/localhost.dev.local2/privkey.pem');

        self::assertResponseStatusCodeSame(404);
    }

}
