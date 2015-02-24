#!/bin/bash
cd $(dirname $0)
curl -k -ssl3 --cert test.cer https://demo.infiniterooms.co.uk/api/import/last-updated -v
