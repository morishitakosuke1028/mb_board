FROM node:current

WORKDIR /app

# 依存関係のキャッシュを効かせるため先にコピー
COPY package*.json ./

RUN npm install

# アプリケーションコードをコピー
COPY . .

EXPOSE 3000

CMD ["npm", "run", "dev"]