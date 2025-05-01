<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Venda Alterada.</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #333;">Olá, {{ $seller->name }}</h2>
            <p>Viemos informar que sua venda do dia {{ $oldSale->made_at }} no valor de R$ {{ $oldSale->amount}} acabou de ser alterada pelo administrador {{ $user->name }}!</p>
            <hr style="margin: 30px 0;">

            <h2 style="color: #333;">Atualização:</h2>
            <table>
                <thead>
                    <tr>
                        <td></td>
                        <td>Antes</td>
                        <td>Depois</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Valor</td>
                        <td>{{ $oldSale->amount }}</td>
                        <td>{{ $sale->amount }}</td>
                    </tr>
                    <tr>
                        <td>Comissão</td>
                        <td>{{ $oldSale->commission }}</td>
                        <td>{{ $sale->commission }}</td>
                    </tr>
                    <tr>
                        <td>Realizada em</td>
                        <td>{{ $oldSale->made_at }}</td>
                        <td>{{ $sale->made_at }}</td>
                    </tr>
                </tbody>
            </table>

            <hr style="margin: 30px 0;">
            <p style="font-size: 12px; color: #888;">Este é um e-mail automático. Por favor, não responda.</p>
        </div>
    </body>
</html>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        margin-top: 20px;
    }

    thead {
        background-color: #f4f4f4;
    }

    thead td {
        font-weight: bold;
        padding: 12px;
        border-bottom: 2px solid #ccc;
        text-align: left;
    }

    tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
    }

    td:first-child {
        width: 30%;
    }

    td:nth-child(2),
    td:nth-child(3) {
        width: 35%;
    }
</style>