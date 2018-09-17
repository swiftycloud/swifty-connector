#!/bin/bash

source .env

COLOR="\033[1;34m"
NC="\033[0m"

echo -e "${COLOR}# check the repository and update the project${NC}" &&
git pull &&

echo -e "${COLOR}# rebuild the docker images${NC}" &&
docker-compose build &&

echo -e "${COLOR}# restart the containers if required${NC}" &&
docker-compose up -d &&

echo -e "${COLOR}# install or update composer dependency${NC}" &&
docker run --rm -v $(pwd):/app prooph/composer:7.2 install &&

echo -e "${COLOR}# run database migrations${NC}" &&
docker-compose exec app php artisan migrate

if [ $APP_ENV = "local" ]
then

  echo -e "${COLOR}# install or update nodejs dependency${NC}" &&
  docker-compose exec app npm install &&

  echo -e "${COLOR}# build production assets${NC}" &&
  docker-compose exec app npm run prod

fi

echo -e "${COLOR}# all is done!${NC}"