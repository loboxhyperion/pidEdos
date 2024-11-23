<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de supervisores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style2.css">
    <script src="https://kit.fontawesome.com/cb0cf16444.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">

        <form class="row g-3" action="" method="post">
            <div class="form-group col-md-4">
                <label for="firma" class="form-label">Firma digital</label>
                <canvas id="canvas" width="600" height="600" style="border:solid black 1px;"></canvas>
                Log: <pre id="log" style="border: 1px solid #ccc;"></pre>
                <br>
                <button class="btn btn-warning" id="btnLimpiar">Limpiar</button>
                <button class="btn btn-danger" id="btnDescargar">Descargar</button>
                <button class="btn btn-primary" id="btnGenerarDocumento">Pasar a documento</button>
            </div>
            <br>
            <div class="form-group col-md-12" style="">
                <input type="submit" class="btn btn-primary" value="Guardar" />
            </div>
        </form>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>    
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript" src="script.js"></script>
</body>
</html>