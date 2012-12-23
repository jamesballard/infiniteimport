#!/bin/bash
curl -k -ssl3 --cert test.cer https://localhost/infiniterooms/api/import/user -T user.csv -v
