FROM node:18
WORKDIR /usr/src/app
COPY package.json .
RUN npm install
RUN chmod a+rw .
COPY . .
USER node