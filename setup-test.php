<?php
session_start();

// Adicionar mangás de teste
$_SESSION['mangas'] = [
    [
        'id' => 'test1',
        'nome' => 'One Piece',
        'status' => 'lendo',
        'capitulos_total' => 1000,
        'capitulo_atual' => 50,
        'em_lancamento' => true,
        'finalizado' => false,
        'generos' => ['ação', 'aventura'],
        'data_criacao' => date('Y-m-d H:i:s'),
        'data_atualizacao' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 'test2', 
        'nome' => 'Naruto',
        'status' => 'completado',
        'capitulos_total' => 700,
        'capitulo_atual' => 700,
        'em_lancamento' => false,
        'finalizado' => true,
        'generos' => ['ação', 'ninja'],
        'data_criacao' => date('Y-m-d H:i:s'),
        'data_atualizacao' => date('Y-m-d H:i:s')
    ],
    [
        'id' => 'test3',
        'nome' => 'Dragon Ball',
        'status' => 'pretendo',
        'capitulos_total' => 500,
        'capitulo_atual' => 0,
        'em_lancamento' => false,
        'finalizado' => false,
        'generos' => ['ação', 'luta'],
        'data_criacao' => date('Y-m-d H:i:s'),
        'data_atualizacao' => date('Y-m-d H:i:s')
    ]
];

echo "<h2>Mangás de teste criados!</h2>";
echo "<p>Total: " . count($_SESSION['mangas']) . " mangás</p>";
echo "<a href='index-mangas.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Ir para a lista</a>";
echo "<br><br>";
echo "<a href='test-sortable.html' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Testar sistema de ordem</a>";
?>
