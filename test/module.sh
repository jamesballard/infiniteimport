#!/bin/bash
cd $(dirname $0)
curl -k -ssl3 --cert test.cer https://localhost/infiniterooms/api/import/module -T module.csv -v
