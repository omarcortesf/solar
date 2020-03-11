<?php
$conn = new mysqli("127.0.0.1", "root", "", "ElectricalSystem");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//$conn = mysqli_connect("localhost","root","", "ElectricalSystem");
$sql = "SELECT Instantanea, Diaria, Mensual, Acumulada, Arboles, Co2, Hogares  FROM Fotovoltaico ORDER BY id DESC LIMIT 1";

print_r($conn);

$address = '127.0.0.1';
$port = 12345;

// Create WebSocket.
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($server, $address, $port);
socket_listen($server);
$client = socket_accept($server);

// Send WebSocket handshake headers.
$request = socket_read($client, 5000);
preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
$key = base64_encode(pack(
    'H*',
    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
));
$headers = "HTTP/1.1 101 Switching Protocols\r\n";
$headers .= "Upgrade: websocket\r\n";
$headers .= "Connection: Upgrade\r\n";
$headers .= "Sec-WebSocket-Version: 13\r\n";
$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
socket_write($client, $headers, strlen($headers));

// Send messages into WebSocket in a loop.
$seconds = 1;


while (true) {
    sleep(1);
    $seconds = $seconds + 1;
    
    if($seconds % 3 === 0) {
        $seconds = 0;
        // $content = "The time is " . date("h:i:sa");
        $content = $resutCheck['Instantanea'].'||'.$resutCheck['Diaria'].'||'.$resutCheck['Mensual'].'||'.$resutCheck['Acumulada'].'||'.$resutCheck['Arboles'].'||'.$resutCheck['Co2'].'||'.$resutCheck['Hogares'];
        $result = mysqli_query($conn, $sql);
        $resutCheck = mysqli_fetch_assoc($result);

    	print($content).'\n';
	    $response = chr(129) . chr(strlen($content)) . $content;
	    socket_write($client, $response);
    }
    
}






?>