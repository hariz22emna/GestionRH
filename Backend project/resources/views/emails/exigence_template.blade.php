<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Document Template</title>
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
                <p style="font-weight: 700;">Bonjour, {{ $data['username'] }}</p>
                <p>A new document has ben added to your portal under the name of <g style="font-weight: bold;"> {{$data['filename'] }}</g></p>
                <p>Please visit the piximind careers platform to preview and download your documents</p>
                <p>If you have any questions, feel free to reach out.</p>
                <p>Thank you!</p>
                <p style="font-weight: 700;">Visit our website: <a
                        href="https://www.piximind.com/en">www.piximind.com</a></p>
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