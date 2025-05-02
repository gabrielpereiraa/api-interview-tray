<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Nova venda realizada.</title>
    </head>
    <body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="color: #333;">Olá, {{ $seller->name }}</h2>
            <p>Viemos informar que sua venda do dia {{ $sale->made_at_formatted }} no valor de R${{ $sale->amount_formatted }} e com comissão de R${{ $sale->commission_formatted }}, acabou de ser cadastrada pelo usuário {{ $user->name }}!</p>
            <hr style="margin: 30px 0;">
            <p style="font-size: 12px; color: #888;">Este é um e-mail automático. Por favor, não responda.</p>
        </div>
    </body>
</html>