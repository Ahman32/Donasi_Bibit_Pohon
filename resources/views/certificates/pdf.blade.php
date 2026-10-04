<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Penghargaan - {{ $certificate->recipient_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 20px;
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8faf6;
            color: #1e293b;
        }
        .cert-outer-border {
            border: 8px double #166534;
            padding: 12px;
            background-color: #ffffff;
            height: 94%;
            position: relative;
        }
        .cert-inner-border {
            border: 2px solid #ca8a04;
            padding: 25px 35px;
            height: 100%;
            text-align: center;
            background-color: #ffffff;
            position: relative;
        }
        .corner-decoration {
            position: absolute;
            font-size: 18px;
            color: #166534;
        }
        .corner-tl { top: 6px; left: 8px; }
        .corner-tr { top: 6px; right: 8px; }
        .corner-bl { bottom: 6px; left: 8px; }
        .corner-br { bottom: 6px; right: 8px; }

        .org-header {
            margin-bottom: 5px;
        }
        .org-title {
            font-size: 13px;
            font-weight: bold;
            color: #166534;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .org-subtitle {
            font-size: 10px;
            color: #64748b;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .cert-title-container {
            margin: 10px 0 5px 0;
        }
        .cert-main-title {
            font-size: 26px;
            font-weight: 800;
            color: #0f5132;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }
        .cert-english-title {
            font-size: 11px;
            font-weight: 600;
            color: #ca8a04;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .cert-number {
            font-size: 10px;
            color: #475569;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        .divider {
            width: 160px;
            height: 2px;
            background-color: #ca8a04;
            margin: 8px auto 14px auto;
        }

        .present-to {
            font-size: 12px;
            color: #475569;
            font-style: italic;
            margin-bottom: 8px;
        }
        .recipient-name {
            font-size: 24px;
            font-weight: bold;
            color: #14532d;
            border-bottom: 2px solid #166534;
            display: inline-block;
            padding: 0 25px 4px 25px;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .cert-body {
            font-size: 11px;
            color: #334155;
            line-height: 1.6;
            max-width: 820px;
            margin: 0 auto 10px auto;
        }

        .donation-highlight {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 6px 20px;
            display: inline-block;
            margin: 5px auto 10px auto;
        }
        .highlight-text {
            font-size: 13px;
            font-weight: bold;
            color: #15803d;
        }

        .appreciation-message {
            font-size: 10px;
            font-style: italic;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto 18px auto;
        }

        .footer-table {
            width: 100%;
            margin-top: 5px;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
        }
        .seal-box {
            text-align: left;
            padding-left: 20px;
        }
        .seal-badge {
            display: inline-block;
            border: 2px solid #166534;
            border-radius: 50%;
            width: 68px;
            height: 68px;
            padding-top: 15px;
            text-align: center;
            background-color: #f0fdf4;
            color: #166534;
            font-size: 8px;
            font-weight: bold;
            line-height: 1.1;
            text-transform: uppercase;
        }
        .signature-box {
            text-align: right;
            padding-right: 25px;
        }
        .sig-location {
            font-size: 10px;
            color: #475569;
            margin-bottom: 4px;
        }
        .sig-title {
            font-size: 10px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 40px;
        }
        .sig-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
        }
        .doc-verification {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<div class="cert-outer-border">
    <div class="cert-inner-border">
        <!-- Corner Ornaments -->
        <div class="corner-decoration corner-tl">✦</div>
        <div class="corner-decoration corner-tr">✦</div>
        <div class="corner-decoration corner-bl">✦</div>
        <div class="corner-decoration corner-br">✦</div>

        <!-- Org Header -->
        <div class="org-header">
            <div class="org-title">🌱 Donasi Bibit Pohon - Desa Labuhan Lombok</div>
            <div class="org-subtitle">Program Pelestarian Lingkungan & Reboisasi Hijau</div>
        </div>

        <!-- Certificate Title -->
        <div class="cert-title-container">
            <h1 class="cert-main-title">Sertifikat Penghargaan</h1>
            <div class="cert-english-title">Certificate of Appreciation</div>
            <div class="cert-number">Nomor: {{ $certificate->certificate_number }}</div>
        </div>

        <div class="divider"></div>

        <!-- Recipient -->
        <div class="present-to">Diberikan dengan penuh hormat dan apresiasi kepada:</div>
        <div class="recipient-name">{{ $certificate->recipient_name }}</div>

        <!-- Citation Body -->
        <div class="cert-body">
            Atas kontribusi nyata dan kepedulian yang luar biasa dalam melestarikan lingkungan serta memulihkan ekosistem bumi dengan mendonasikan:
        </div>

        <!-- Highlight Contribution -->
        <div class="donation-highlight">
            <span class="highlight-text">🌿 {{ $certificate->tree_count }} Bibit Pohon {{ $certificate->seedling_name }}</span>
        </div>

        <!-- Appreciation Note -->
        <div class="appreciation-message">
            "{{ $certificate->appreciation_message ?? 'Setiap bibit yang Anda donasikan merupakan investasi kebaikan yang abadi demi terciptanya bumi yang lebih sejuk, hijau, dan lestari bagi generasi mendatang.' }}"
        </div>

        <!-- Footer / Signatures -->
        <table class="footer-table">
            <tr>
                <td style="width: 50%;" class="seal-box">
                    <table style="border-collapse: collapse;">
                        <tr>
                            <td style="vertical-align: middle; padding-right: 12px;">
                                <div class="seal-badge">
                                    ★ VERIFIED ★<br>DONASI<br>LESTARI
                                </div>
                            </td>
                            <td style="vertical-align: middle; text-align: left;">
                                <div style="font-size: 9px; font-weight: bold; color: #166534;">DOKUMEN RESMI TERVERIFIKASI</div>
                                <div style="font-size: 8px; color: #64748b;">ID Donasi: #DON-{{ $certificate->donation_id }}</div>
                                <div class="doc-verification">Diterbitkan oleh Sistem Informasi Donasi Bibit Pohon</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;" class="signature-box">
                    <div class="sig-location">Labuhan Lombok, {{ \Carbon\Carbon::parse($certificate->issued_date)->isoFormat('D MMMM Y') }}</div>
                    <div class="sig-title">{{ $certificate->signer_title }}</div>
                    <div class="sig-name">{{ $certificate->signer_name }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
