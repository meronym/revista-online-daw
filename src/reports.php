<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

// FPDF scrie octeti, nu UTF-8: fontul e generat pentru cp1250, deci textul trebuie
// convertit inainte de a ajunge pe pagina
function pdfText(string $text): string
{
    // Virgula dedesubt nu exista in cp1250, folosim sedila
    $text = strtr($text, ['ș' => 'ş', 'ț' => 'ţ', 'Ș' => 'Ş', 'Ț' => 'Ţ']);

    return iconv('UTF-8', 'CP1250//TRANSLIT', $text) ?: $text;
}

const PDF_FONT = 'NimbusSans';

class ArticlePdf extends FPDF
{
    function Footer(): void
    {
        $this->SetY(-16);
        $this->SetFont(PDF_FONT, '', 8);
        $this->SetTextColor(150);

        $half = ($this->w - $this->lMargin - $this->rMargin) / 2;
        $this->Cell($half, 8, pdfText('Revistă Online'), 0, 0, 'L');
        $this->Cell($half, 8, (string) $this->PageNo(), 0, 0, 'R');
    }

    // Linie subtire pe toata latimea textului
    function Rule(): void
    {
        $y = $this->GetY();
        $this->SetDrawColor(215);
        $this->SetLineWidth(0.2);
        $this->Line($this->lMargin, $y, $this->w - $this->rMargin, $y);
    }
}

function articlePdf(array $article): string
{
    $pdf = new ArticlePdf();
    $pdf->AddFont(PDF_FONT, '', 'NimbusSans-Regular.php');
    $pdf->AddFont(PDF_FONT, 'B', 'NimbusSans-Bold.php');
    $pdf->AddFont(PDF_FONT, 'I', 'NimbusSans-Italic.php');

    $pdf->SetTitle($article['titlu'], true);
    $pdf->SetAuthor($article['autor'], true);

    $pdf->SetMargins(25, 25, 25);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();

    $pdf->SetFont(PDF_FONT, '', 8.5);
    $pdf->SetTextColor(140);
    $pdf->Cell(0, 5, pdfText(mb_strtoupper($article['rubrica'], 'UTF-8')), 0, 1);

    $pdf->Ln(2);
    $pdf->SetFont(PDF_FONT, 'B', 21);
    $pdf->SetTextColor(25);
    $pdf->MultiCell(0, 9.5, pdfText($article['titlu']), 0, 'L');

    $pdf->Ln(1);
    $pdf->SetFont(PDF_FONT, 'I', 10);
    $pdf->SetTextColor(140);
    $pdf->Cell(0, 6, pdfText($article['autor'] . '  ·  ' . formatDate($article['publicat_la'])), 0, 1);

    $pdf->Ln(3);
    $pdf->Rule();
    $pdf->Ln(7);

    if ($article['rezumat'] !== null) {
        $pdf->SetFont(PDF_FONT, '', 12);
        $pdf->SetTextColor(90);
        $pdf->MultiCell(0, 6.5, pdfText($article['rezumat']), 0, 'L');
        $pdf->Ln(5);
    }

    // Aliniere la stanga, fara despartire in silabe
    $pdf->SetFont(PDF_FONT, '', 10.5);
    $pdf->SetTextColor(35);

    foreach (preg_split('/\n\s*\n/', trim($article['continut'])) as $paragraph) {
        $pdf->MultiCell(0, 6, pdfText(trim($paragraph)), 0, 'L');
        $pdf->Ln(3.5);
    }

    return $pdf->Output('S');
}

function articlesCsv(array $articles): string
{
    $out = fopen('php://temp', 'r+');
    fputcsv($out, ['Titlu', 'Rubrica', 'Autor', 'Stare', 'Creat', 'Publicat']);

    foreach ($articles as $article) {
        fputcsv($out, [
            $article['titlu'],
            $article['rubrica'],
            $article['autor'],
            $article['stare'],
            $article['creat_la'],
            $article['publicat_la'] ?? '',
        ]);
    }

    rewind($out);

    // BOM: altfel Excel deschide fisierul ca latin1 si strica diacriticele
    return "\xEF\xBB\xBF" . stream_get_contents($out);
}

function sendDownload(string $content, string $filename, string $type, string $disposition): never
{
    // Curatam buffer-ul paginii: tipul raspunsului e fisier nu HTML
    ob_clean();

    header('Content-Type: ' . $type);
    header(sprintf('Content-Disposition: %s; filename="%s"', $disposition, $filename));
    header('Content-Length: ' . strlen($content));

    echo $content;
    exit;
}
