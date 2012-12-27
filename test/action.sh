#!/bin/bash
cd $(dirname $0)
curl -k -ssl3 --cert test.cer https://localhost/infiniterooms/api/import/action -T action.csv -v
