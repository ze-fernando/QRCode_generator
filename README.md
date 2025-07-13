# QR Code Generator API

API REST em Symfony para geração, armazenamento e consulta de QR Codes.

---

## Visão Geral

Este projeto permite criar QR Codes a partir de URLs, armazená-los em banco de dados e consultá-los via API.

Utiliza Doctrine ORM para persistência e a biblioteca [Endroid QR Code](https://github.com/endroid/qr-code) para geração dos códigos.

---

## Endpoints

| Rota           | Método | Descrição                                    | Retorno                                  |
|----------------|--------|----------------------------------------------|-----------------------------------------|
| `/generate`    | POST   | Gera e salva um novo QR Code                  | JSON com objeto QR Code criado (imagem base64) |
| `/find`        | GET    | Lista todos os QR Codes salvos                 | JSON array com QR Codes (imagens base64) |
| `/find/{id}`   | GET    | Busca um QR Code pelo ID                       | JSON do QR Code solicitado (imagem base64) |

---

## Uso

### Criar QR Code

**POST** `/generate`

Corpo JSON esperado:

```json
{
  "title": "Título do QR",
  "url": "https://exemplo.com"
}
```
Resposta (201 Created):
```json
{
  "message": "Qr Code gerado com sucesso",
  "object": {
    "id": 1,
    "title": "Título do QR",
    "url": "https://exemplo.com",
    "image": "iVBORw0KGgoAAAANSUhEUgAA..."
  }
}
```

Listar todos QR Codes

**GET** /find

Resposta (200 OK):
```json
[
  {
    "id": 1,
    "title": "Título do QR",
    "url": "https://exemplo.com",
    "image": "iVBORw0KGgoAAAANSUhEUgAA..."
  },
  {
    "id": 2,
    "title": "Outro QR",
    "url": "https://outroexemplo.com",
    "image": "iVBORw0KGgoAAAANSUhEUgAA..."
  }
]
```
Buscar QR Code por ID

**GET** /find/{id}

Resposta (200 OK):
```json
{
  "id": 1,
  "title": "Título do QR",
  "url": "https://exemplo.com",
  "image": "iVBORw0KGgoAAAANSUhEUgAA..."
}
```
Resposta se não encontrado (404 Not Found):
```json
{
  "message": "QrCode não encotrado"
}
```
## Observações Técnicas

- O campo `image` é codificado em base64 para facilitar transporte via JSON.
- O banco armazena o QR Code em formato binário (blob).
- O projeto usa injeção de dependências e serviços para separar a lógica do controller.
- O serviço principal está em `App\Service\QrCodeService`.
- O controller está em `App\Controller\QrCodeController`.

## Como Rodar

1. Configure seu banco no arquivo `.env` (ex: SQLite ou MySQL).
2. Rode as migrações:

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

3. Inicie o servidor Symfony:

   ```bash
   symfony server:start
   ```

4. Use o Postman ou `curl` para testar os endpoints.

