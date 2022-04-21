#!/bin/bash

[[ ! -z "$REF" ]] || REF=$1

echo "$(echo -n "${REF}" | sed -e 's/[^[:alnum:]]/-/g' \
     | tr -s '-' | tr A-Z a-z)"
