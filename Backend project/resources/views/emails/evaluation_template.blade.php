<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Evaluation Mail</title>
</head>

<body>
    <table style="width: 100%; max-width: 600px; margin: 0 auto; padding: 20px;">
        <tr>
            <td style="background-color: #64bf59; padding: 20px; text-align: center;">
                <h1 style="color: white">Piximind Careers</h1>
            </td>
        </tr>
        <tr>
            <td style="background-color: #ffffff; padding: 20px; font-family: sans-serif;text-align: justify;">
                <div>
                    <h1>Résultats de l'évaluation</h1>

                    <p>Cher(e) {{ $data['username'] }},</p>

                    <p>Voici les résultats de l'évaluation pour l'année {{ $data['year'] }}:</p>

                    <ul>
                        @foreach ($data['evaluations'] as $resultat)
                        <li>
                            <strong>{{ $resultat['technology']['name']}} : {{$resultat['note']}} / {{$resultat['total']}}</strong>
                        </li>
                        @endforeach
                    </ul>

                    <p>Merci.</p>
                </div>
            </td>
        </tr>

        <tr>
            <td style="color: white;background-color: #64bf59; padding: 20px; text-align: center;">
                <p>&copy; 2023 Piximind. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>