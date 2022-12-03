# connectDB

Utilisation : 

#### init Connexion 

$connexion = new ConnectDb();

$pdo = $connexion->connect();

#### Request

$request = new Request();

$query = $request->query("QUERY", "PARAM")
