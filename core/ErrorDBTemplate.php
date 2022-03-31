<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro no Sistema</title>
    <style>
        *{
           font-family: Arial, Helvetica, sans-serif;
           padding: 0;
           margin: 0;
        }
        html,body{
           height: 100%;
        }

        #layout-error{
            padding: 0;
            background: #fff;
            position: relative;
            height: 100%;
        }

        .main-top{
            background: #333;
            padding: 3%;
            border-bottom: 4px solid olive;
        }

        #exception{
            background: #333;
            float: left;
            padding: 2%;
            color:#fff;
            width: 62%;
        }

        #traces{
            float: left;
            background: #ccc;
            width:34%;
            height: 50em;
            position: relative;
            font-size: 1rem;
        }

        .trace{
            background: #f1f1f1;
            padding: 2%;
            border-bottom: 2px solid #ccc;
            max-width: 100%;
            word-break: break-all;
        }

        .text-white{
            color:white;
        }

        .code{
            padding: 1%;
            position: relative;
            margin: 0px;
            float: left;
            background: #112233;
            width: 64%;
            font-size: 100%;
        }

        .line-error{
            color: #ff9933 !important;
        }

        .arquivo{
            color:#3f8f03;
        }

        .linha{
            color:#355;
        }

        .line{
            color:#999;
        }

        .funcao{
            color:#ff3333;
        }
        .php{
            color:#09a0e0;
        }

        .public{
            color:#04bfb9;
        }
        
        .common-line{
            color:#09DEE9;
        }
    </style>
</head>
<body>
   <div id="layout-error" scrollable>
        <div class="main-top">
            <h1 class="text-white">Erro ao executar a query</h1>
        </div>
        <div id="traces">
            {traces}
        </div>
        <div id="exception">
           <p>Erro: {message}</p>
           <p>Pasta: {folder}</p>
           <p>Arquivo: {class}</p>
           <p>Linha: {mainline}</p>
        </div>
        <div class="code">
           <pre>{code}</pre> 
        </div>
   </div>
</body>
</html>