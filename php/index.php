<?php
$estudiante = "";

$materiasConfig = [
    "naturales" => "Ciencias Naturales",
    "sociales" => "Ciencias Sociales",
    "espanol" => "Lengua Española",
    "matematicas" => "Matemáticas"
];

$notas = [];
foreach ($materiasConfig as $clave => $nombre) {
    $notas[$clave] = ["", "", ""];
}

$resultadosMaterias = [];
$promedioGeneral = null;
$estadoGeneral = "";
$aprobadasCount = 0;
$reprobadasCount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estudiante = trim($_POST['estudiante'] ?? '');

    foreach ($materiasConfig as $clave => $nombre) {
        $n1 = floatval($_POST[$clave . '_1'] ?? 0);
        $n2 = floatval($_POST[$clave . '_2'] ?? 0);
        $n3 = floatval($_POST[$clave . '_3'] ?? 0);

        $notas[$clave] = [$n1, $n2, $n3];
        $prom = ($n1 + $n2 + $n3) / 3;
        $aprobada = $prom >= 70;

        if ($aprobada) {
            $estadoMat = "Aprobada";
            $aprobadasCount++;
        } else {
            $estadoMat = "Reprobada";
            $reprobadasCount++;
        }

        $resultadosMaterias[$clave] = [
            "nombre" => $nombre,
            "notas" => [$n1, $n2, $n3],
            "promedio" => $prom,
            "estado" => $estadoMat,
            "aprobada" => $aprobada
        ];
    }

    $sumaPromedios = 0;
    foreach ($resultadosMaterias as $item) {
        $sumaPromedios += $item["promedio"];
    }
    $promedioGeneral = $sumaPromedios / count($resultadosMaterias);

    if ($promedioGeneral >= 70 && $reprobadasCount === 0) {
        $estadoGeneral = "Aprobado";
    } elseif ($reprobadasCount > 0) {
        $estadoGeneral = "Requiere Recuperación";
    } else {
        $estadoGeneral = "Reprobado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Nataniel Contín | Calificaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #ec4899;
            --accent: #f59e0b;
            --teal: #06b6d4;
            --bg-gradient: linear-gradient(135deg, #1e1b4b 0%, #3b0764 45%, #831843 100%);
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --success: #10b981;
            --danger: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            padding: 30px 16px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: var(--card-bg);
            border-radius: 22px;
            box-shadow: 0 25px 55px rgba(0, 0, 0, 0.45);
            overflow: hidden;
        }

        .header-banner {
            background: linear-gradient(120deg, #2563eb 0%, #7c3aed 50%, #ec4899 100%);
            color: #ffffff;
            padding: 34px 24px;
            text-align: center;
        }

        .header-banner i.school-icon {
            font-size: 2.9rem;
            background: rgba(255, 255, 255, 0.22);
            padding: 16px;
            border-radius: 50%;
            margin-bottom: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        }

        .header-banner h1 {
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .header-banner p {
            font-size: 0.9rem;
            opacity: 0.95;
            margin-top: 5px;
        }

        form {
            padding: 28px;
        }

        .estudiante-box {
            margin-bottom: 24px;
            background: linear-gradient(135deg, #eff6ff 0%, #fdf2f8 100%);
            padding: 16px;
            border-radius: 14px;
            border: 1.5px solid #dbeafe;
        }

        .estudiante-box label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 13px;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: color 0.25s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 11px 12px 11px 38px;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.92rem;
            color: var(--text-dark);
            background: #ffffff;
            outline: none;
            transition: all 0.25s ease;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.16);
        }

        .input-wrapper input:focus + i {
            color: var(--primary);
        }

        .materias-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .materia-card {
            border-radius: 15px;
            padding: 16px;
            border: 2px solid transparent;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .materia-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.08);
        }

        .materia-naturales {
            background: #ecfdf5;
            border-color: #6ee7b7;
        }

        .materia-naturales .materia-titulo i {
            color: #059669;
        }

        .materia-sociales {
            background: #fffbeb;
            border-color: #fcd34d;
        }

        .materia-sociales .materia-titulo i {
            color: #d97706;
        }

        .materia-espanol {
            background: #fdf2f8;
            border-color: #f9a8d4;
        }

        .materia-espanol .materia-titulo i {
            color: #db2777;
        }

        .materia-matematicas {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .materia-matematicas .materia-titulo i {
            color: #2563eb;
        }

        .materia-titulo {
            font-size: 0.98rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notas-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .notas-row .input-wrapper input {
            padding: 10px 8px 10px 30px;
            font-size: 0.88rem;
        }

        .notas-row .input-wrapper i {
            left: 10px;
            font-size: 0.82rem;
        }

        .btn-calcular {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(120deg, #2563eb 0%, #7c3aed 50%, #ec4899 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 12px 22px -5px rgba(124, 58, 237, 0.45);
            transition: all 0.25s ease;
        }

        .btn-calcular:hover {
            transform: translateY(-2px);
            filter: brightness(1.06);
        }

        .resultados-section {
            padding: 0 28px 30px 28px;
            animation: fadeIn 0.4s ease;
        }

        .resumen-general {
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 7px solid;
        }

        .resumen-general.ok {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: var(--success);
            color: #065f46;
        }

        .resumen-general.warn {
            background: linear-gradient(135deg, #fef2f2 0%, #ffedd5 100%);
            border-color: var(--danger);
            color: #991b1b;
        }

        .resumen-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .resumen-top h2 {
            font-size: 1.18rem;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #ffffff;
        }

        .badge.ok {
            background: #059669;
        }

        .badge.warn {
            background: #dc2626;
        }

        .resumen-datos {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 0.92rem;
        }

        .tabla-materias {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        }

        .tabla-materias th,
        .tabla-materias td {
            padding: 12px 14px;
            text-align: left;
            font-size: 0.9rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .tabla-materias th {
            background: linear-gradient(90deg, #2563eb 0%, #7c3aed 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
        }

        .tabla-materias tr:nth-child(even) td {
            background: #f8fafc;
        }

        .estado-aprobada {
            color: #047857;
            font-weight: 700;
        }

        .estado-reprobada {
            color: #b91c1c;
            font-weight: 700;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 680px) {
            .materias-grid {
                grid-template-columns: 1fr;
            }
            form {
                padding: 20px;
            }
            .resultados-section {
                padding: 0 20px 24px 20px;
            }
            .tabla-materias th,
            .tabla-materias td {
                padding: 10px 8px;
                font-size: 0.82rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-banner">
        <i class="fa-solid fa-graduation-cap school-icon"></i>
        <h1>Colegio Futuro del Saber </h1>
        <p>Registro de Calificaciones - Materias Básicas (Escala 0 a 100, Mínimo 70)</p>
    </div>

    <form method="POST">
        <div class="estudiante-box">
            <label>Nombre del Estudiante</label>
            <div class="input-wrapper">
                <input type="text" name="estudiante" placeholder="Ej: Carlos Martínez" value="<?= htmlspecialchars($estudiante) ?>" required>
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <div class="materias-grid">
            <?php foreach ($materiasConfig as $clave => $nombre): ?>
                <div class="materia-card materia-<?= $clave ?>">
                    <div class="materia-titulo">
                        <i class="fa-solid fa-book"></i>
                        <span><?= htmlspecialchars($nombre) ?></span>
                    </div>
                    <div class="notas-row">
                        <div class="input-wrapper">
                            <input type="number" step="0.1" min="0" max="100" name="<?= $clave ?>_1" placeholder="N1" value="<?= htmlspecialchars((string)$notas[$clave][0]) ?>" required>
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" min="0" max="100" name="<?= $clave ?>_2" placeholder="N2" value="<?= htmlspecialchars((string)$notas[$clave][1]) ?>" required>
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div class="input-wrapper">
                            <input type="number" step="0.1" min="0" max="100" name="<?= $clave ?>_3" placeholder="N3" value="<?= htmlspecialchars((string)$notas[$clave][2]) ?>" required>
                            <i class="fa-solid fa-pen"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn-calcular">
            <i class="fa-solid fa-calculator"></i> Calcular Promedios y Estado
        </button>
    </form>

    <?php if ($promedioGeneral !== null): ?>
        <?php $esGeneralOk = ($promedioGeneral >= 70 && $reprobadasCount === 0); ?>
        <div class="resultados-section">
            <div class="resumen-general <?= $esGeneralOk ? 'ok' : 'warn' ?>">
                <div class="resumen-top">
                    <h2>Boletín de <?= htmlspecialchars($estudiante) ?></h2>
                    <span class="badge <?= $esGeneralOk ? 'ok' : 'warn' ?>"><?= htmlspecialchars($estadoGeneral) ?></span>
                </div>
                <div class="resumen-datos">
                    <span><strong>Promedio General:</strong> <?= number_format($promedioGeneral, 2) ?> / 100</span>
                    <span><strong>Aprobadas:</strong> <?= $aprobadasCount ?> de 4</span>
                    <span><strong>Reprobadas:</strong> <?= $reprobadasCount ?> de 4</span>
                </div>
            </div>

            <table class="tabla-materias">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Notas</th>
                        <th>Promedio</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultadosMaterias as $res): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($res["nombre"]) ?></strong></td>
                            <td><?= htmlspecialchars($res["notas"][0] . ", " . $res["notas"][1] . ", " . $res["notas"][2]) ?></td>
                            <td><strong><?= number_format($res["promedio"], 2) ?></strong></td>
                            <td class="<?= $res["aprobada"] ? 'estado-aprobada' : 'estado-reprobada' ?>">
                                <?= htmlspecialchars($res["estado"]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>