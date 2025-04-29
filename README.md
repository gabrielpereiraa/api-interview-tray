## 1. Descrição do Projeto

API RESTful para gerenciar contas bancárias e transações.

## 2. Observações
Adaptei os endpoints para seguir o padrão RESTful e usei nomenclaturas em inglês. Além disso, adicionei uma autenticação simples via token: ao criar um novo customer, um token é gerado e deve ser usado para acessar a conta e realizar transações.

## 3. Setup
Clone o repositório e execute o script de inicialização:

```bash
git clone https://github.com/gabrielpereiraa/api-interview-tray.git
cd /api-interview-tray
chmod +x start.sh
./setup.sh
```

## 4. Insomnia
O arquivo Insomnia_apis contém as apis do projeto.