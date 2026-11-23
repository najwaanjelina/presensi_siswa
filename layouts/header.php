
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul ?? 'Presensi Sekolah') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        h2, h3 {
            color: #263b63;
        }

        a {
            color: #315a9b;
            text-decoration: none;
        }

        button {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            background: #315a9b;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #203e70;
        }

        input, select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e8eef8;
        }

        .pesan {
            padding: 10px;
            margin: 12px 0;
            border-radius: 5px;
        }

        .success {
            background: #e2f5e8;
            color: #236b38;
        }

        .error {
            background: #fde8e8;
            color: #a32929;
        }
    </style>
</head>
<body>
