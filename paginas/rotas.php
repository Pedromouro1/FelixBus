<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $de = htmlspecialchars($_POST["de"]);
    $para = htmlspecialchars($_POST["para"]);
    $data = htmlspecialchars($_POST["data"]);
    $passageiros = htmlspecialchars($_POST["passageiros"]);

    // Simulação de resultados de rotas
    $resultados = [
        [
            "horario" => "04:00 - 12:45",
            "origem" => "Lisboa (Oriente)",
            "destino" => "Castelo Branco (Terminal Rodoviário)",
            "preco" => "€26,48"
        ],
        [
            "horario" => "06:00 - 14:45",
            "origem" => "Lisboa (Oriente)",
            "destino" => "Castelo Branco (Terminal Rodoviário)",
            "preco" => "€24,99"
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - FlixBus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #98c21c;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .route {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>Resultados - FlixBus</header>
    <div class="container">
        <h2>Resultados para: <?php echo "$de → $para em $data"; ?></h2>
        <p><strong>Passageiros:</strong> <?php echo $passageiros; ?></p>
        <div class="result">
            <h3>Rotas disponíveis:</h3>
            <?php if (!empty($resultados)) : ?>
                <?php foreach ($resultados as $rota) : ?>
                    <div class="route">
                        <p><strong>Horário:</strong> <?php echo $rota["horario"]; ?></p>
                        <p><strong>Origem:</strong> <?php echo $rota["origem"]; ?></p>
                        <p><strong>Destino:</strong> <?php echo $rota["destino"]; ?></p>
                        <p><strong>Preço:</strong> <?php echo $rota["preco"]; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Nenhuma rota encontrada.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
