FROM node:24-alpine

RUN addgroup node www-data

WORKDIR /app

COPY package*.json ./

RUN npm ci

RUN chown -R node:www-data /app

USER node

EXPOSE 5173

CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
