<?php
declare(strict_types = 1);

namespace Tests\Integration\Http;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetFullChainTest extends WebTestCase
{

    /**
     * @test
     */
    public function certificate_exists(): void
    {
        $client = self::createClient();

        $expected =<<<EOF
-----BEGIN CERTIFICATE-----
MIID2zCCA4KgAwIBAgIQbRsU5FkUKsy33NgXXHDlDDAKBggqhkjOPQQDAjBQMR4w
HAYDVQQKExV0cmFlZmlrLWNlcnQtcHJvdmlkZXIxLjAsBgNVBAMTJXRyYWVmaWst
Y2VydC1wcm92aWRlciBJbnRlcm1lZGlhdGUgQ0EwHhcNMjEwMzI2MTE1MDAzWhcN
MjEwMzI3MTE1MTAzWjAUMRIwEAYDVQQDEwlsb2NhbGhvc3QwggIiMA0GCSqGSIb3
DQEBAQUAA4ICDwAwggIKAoICAQDCX0KMfeR9zsr/NeCmrZqCjGKyrbYRfbMuZzYf
JZi//PztqM9++CxSB9an7qwDlBrPxgGl2MYgu1p8CkLcviBl6XwfwW/g/kX+dpX9
rgMyWYi63/FrFEs2UxkcEdd3SEwwkaqZ3ZQAdQl2rDHC3p4LQGosCATvRcuRIy/0
yI72gWeLKZznfOrhiF4aeiyABNLvAU70shQn1+3Tt4eskgP772MkwcQrCvLNYW0r
zUgEE8/cYMJFrBIeIvy9jwfCh/DErLJI1cj7X6z/NVQkaA1NptZ7PHspxksN6Are
Fj8I+QD/miu8AhGxSzqWgd+FJRbGN5YtAaI9znns22VgWTzYq3L14gk0mIRVaU1r
Kz8/9zXHluwOQtEwP+lGtOnhfnjATMow6DC4Py/Zg4ySS5lmYNNjrTjnch4iCQJU
NF7DQLrQq6TihGcEeAW9p4AOH400PQL8qTrCDdZcENsgPk85lKhET2B7d+8S1rgE
CHPr3StVEVV/DL33ify2kiBubdq3zt1BmsvW+kDRrIlxwSF7pH6pl8VAlmaMn3XL
gv9PnRV0PuZF98EfIRDOyDVxLEvKblI7MXm8HctrErXvSYEKBHITgrHhdVfYOxOj
JuEEPUl8ptkg7dAZlUGCDOSxVos6KFGJqF5EjvOyTArsuVakrNsCp+Dy1UUOrjoh
9V0FKQIDAQABo4GuMIGrMA4GA1UdDwEB/wQEAwIFoDAdBgNVHSUEFjAUBggrBgEF
BQcDAQYIKwYBBQUHAwIwHQYDVR0OBBYEFIdK92fgOWnrNZeI7zv1M5vMNUbFMB8G
A1UdIwQYMBaAFAhz3FMiIMy1PXIPihqNnZ2bU7ObMBQGA1UdEQQNMAuCCWxvY2Fs
aG9zdDAkBgwrBgEEAYKkZMYoQAEEFDASAgEGBAtsZXRzZW5jcnlwdAQAMAoGCCqG
SM49BAMCA0cAMEQCICaI3t4L/mN6ARjuMVVTEJQemgu8X4011ACJcXGs+190AiBz
9codyjifbqK/m1B7Rv+pPOOheIOyVUio0xuGdq4sRQ==
-----END CERTIFICATE-----
-----BEGIN CERTIFICATE-----
MIIB/DCCAaKgAwIBAgIQbHLHH9OI33YAWlwyxBSGTTAKBggqhkjOPQQDAjBIMR4w
HAYDVQQKExV0cmFlZmlrLWNlcnQtcHJvdmlkZXIxJjAkBgNVBAMTHXRyYWVmaWst
Y2VydC1wcm92aWRlciBSb290IENBMB4XDTIxMDMyNjA5MjkxMVoXDTMxMDMyNDA5
MjkxMVowUDEeMBwGA1UEChMVdHJhZWZpay1jZXJ0LXByb3ZpZGVyMS4wLAYDVQQD
EyV0cmFlZmlrLWNlcnQtcHJvdmlkZXIgSW50ZXJtZWRpYXRlIENBMFkwEwYHKoZI
zj0CAQYIKoZIzj0DAQcDQgAE3AwugRopShYC+eN9Q71AsAyLnB3aAMTc1EwEpl6b
t29bE9/eWm7mvF3S0w0NlV8Gn+1IkFPw/CJAxA35A54MwqNmMGQwDgYDVR0PAQH/
BAQDAgEGMBIGA1UdEwEB/wQIMAYBAf8CAQAwHQYDVR0OBBYEFAhz3FMiIMy1PXIP
ihqNnZ2bU7ObMB8GA1UdIwQYMBaAFCdYY53E7aEAa5lTSjYinDniyveSMAoGCCqG
SM49BAMCA0gAMEUCIQCLJbVHXeRShBSaHR/yDARhyhFtcndqPos91VRue25ovwIg
DnOvFI9RHoKnWBsJM3lsHBV4ulB3FdmGEl34oUZwfuQ=
-----END CERTIFICATE-----
EOF;

        $client->request('GET', '/letsencrypt/localhost.dev.local/fullchain.pem');

        self::assertResponseStatusCodeSame(200);
        self::assertSame($expected, trim($client->getResponse()->getContent()));
    }

    /**
     * @test
     */
    public function certificate_does_not_exist(): void
    {
        $client = self::createClient();
        $client->request('GET', '/letsencrypt/localhost.dev.local2/fullchain.pem');

        self::assertResponseStatusCodeSame(404);
    }

}
