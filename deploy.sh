#!/bin/bash
#docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d
docker run -d -p 80:80 --name fight_club-latest tufeng/fight_club:latest