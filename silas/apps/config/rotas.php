<?php

$rotas['/cursos/(any)/(any)'] = '/cursos/conteudo/$1/$2';
$rotas['/cursos/(any)'] = '/cursos/conteudo/$1';
$rotas['/downloads/(any)'] = "/downloads/categoria/$1";