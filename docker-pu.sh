#!/bin/bash

echo "============== To gitlab registry:"

docker build -t registry.gitlab.com/swiftyteam/swifty.connector/app -f app.dockerfile .
docker push registry.gitlab.com/swiftyteam/swifty.connector/app

docker build -t registry.gitlab.com/swiftyteam/swifty.connector/web -f web.dockerfile .
docker push registry.gitlab.com/swiftyteam/swifty.connector/web

echo "============== Completed!"
